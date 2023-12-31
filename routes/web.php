<?php

use App\FlashCard;
use App\TextChunker;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $chapterA = File::get(base_path('tests/Chapters/mikroekonomi/Chapter2.md'));   
    $chapterB = File::get(base_path('tests/Chapters/mikroekonomi/Chapter3.md'));

    $cacheKey = sha1($chapterA . PHP_EOL . $chapterB);

    $flashCards = Cache::has($cacheKey) 
        ? Cache::get($cacheKey) : 
        getBook($chapterA . PHP_EOL . $chapterB);

    return view('welcome', [
        'flashCards' => $flashCards,
    ]);
});


function getBook($book) {
    $chunks = (new TextChunker(4000))->chunk($book);
    $template = <<<EOT
Front: Vilken är sveriges huvudstad?
Back: Stockholm

Front: Vilken är sveriges näst största stad?
Back: Göteborg
EOT;
    $cards = collect($chunks)
        ->map(function ($chunk) use ($template) {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => 'Gör tio stycken "flashcards" av följande text. Ett flashcard består av relavant fråga, och ett pedagogiskt svar. Båda bör vara kortfattade och lätta att förstå. Använd Denna mall: "' . $template . '". Numrera inte svaren. Texten: "' . $chunk . '"'],
                ],
            ]);

            return FlashCard::fromString($result['choices'][0]['message']['content']);
        })
        ->flatten();

    Cache::forever(sha1($book), $cards);

    return $cards;
}
