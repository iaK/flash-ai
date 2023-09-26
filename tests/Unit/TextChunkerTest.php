<?php

namespace Tests\Unit;

use Tests\TestCase;

class TextChunkerTest extends TestCase
{
    /** @test */
    public function it_chunks_stuff()
    {
        $chunker = new \App\TextChunker(200);
        $chunk = $chunker->chunk($this->generateLoremIpsum(8000, 50));

        dd($chunk);
    }

    function generateLoremIpsum($length = 8000, $lineLength = 100) {
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    
        $loremText = '';
    
        while(strlen($loremText) < $length) {
            $loremText .= ' ' . $lorem;
        }
    
        $loremText = substr($loremText, 0, $length);
    
        // Adding new lines
        $formattedText = '';
        while(strlen($loremText) > $lineLength) {
            $formattedText .= substr($loremText, 0, $lineLength) . "\n";
            $loremText = substr($loremText, $lineLength);
        }
        $formattedText .= $loremText; // Append the last part
    
        return $formattedText;
    }
}
