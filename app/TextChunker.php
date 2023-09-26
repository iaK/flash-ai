<?php

namespace App;

class TextChunker
{
    public function __construct(private int  $chunkSize = 7400)
    { }

    public function chunk(string $rawText)
    {
        $originalChunks = explode(PHP_EOL, $rawText);

        return collect($originalChunks)
            ->map(fn ($chunk) => trim($chunk))
            ->reduce(function ($carry, $chunk, $index) use ($originalChunks) {
                [$characters, $currentChunk, $chunks] = $carry;

                if (($index +1) === count($originalChunks)) {
                    $chunks[] = $currentChunk;

                    return $chunks;
                }

                if (strlen($currentChunk) > $this->chunkSize) {
                    $chunks[] = $currentChunk;

                    return [0, '', $chunks];
                }

                $currentChunk .= $chunk;

                return [$characters + strlen($chunk), $currentChunk, $chunks];
            }, [0, '', []]);
    }
}
