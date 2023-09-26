<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\TextChunker;
use OpenAI\Resources\Chat;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\File;

class ExampleTest extends TestCase
{
    public string $template = <<<EOT
Q: Vilken är sveriges huvudstad?
A: Malmö
B: Göteborg
C: Stockholm
Correct: C 
EOT;

    public string $flashCardTemplate = <<<EOT
Front: Vilken är sveriges huvudstad?
Back: Stockholm
EOT;


    /**
     * A basic test example.
     */
    public function flashcards(): void
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Gör två stycken "flashcards" av följande text. Ett flashcard består av relavant fråga, och ett pedagogiskt svar. Båda bör vara kortfattade och lätta att förstå. Texten: "' . $this->text() . '"'],
            ],
        ]);
        
        dd($result['choices'][0]['message']['content']);
    }

    /** @test */
    public function alternatives()
    {
        $result = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Skapa 2 stycken frågor av följande text. Frågorna ska ha 3 st alternativ där bara ett alternativ är rätt. Det är viktigt att svarsalternativen är olika. Markera det rätta svaret enligt mallen. Samtliga alternativ ska vara relevanta. Svara enligt följande mall ' . $this->template . '. Texten: "' . $this->text() . '"'],
            ],
        ]);
        
        dd($result['choices'][0]['message']['content']);
    }


    /** @test */
    public function summarize()
    {
        $chapterA = File::get(base_path('tests/Chapters/mikroekonomi/Chapter2.md'));   
        $chapterB = File::get(base_path('tests/Chapters/mikroekonomi/Chapter3.md'));

        $chunks = (new TextChunker(4000))->chunk($chapterA . PHP_EOL . $chapterB);

        $flashCards = collect($chunks)
            ->map(function ($chunk) {
                $result = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => 'Gör tio stycken "flashcards" av följande text. Ett flashcard består av relavant fråga, och ett pedagogiskt svar. Båda bör vara kortfattade och lätta att förstå. Använd Denna mall: "' . $this->flashCardTemplate . '". Numrera inte svaren. Texten: "' . $chunk . '"'],
                    ],
                ]);

                return $result['choices'][0]['message']['content'];
            });

        dd($flashCards);
    }
}
