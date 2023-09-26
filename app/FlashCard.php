<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class FlashCard
{
    public function __construct(public string $front, public string $back)
    { }

    public static function fromString($rawFlashCards)
    {
        return Str::of($rawFlashCards)->explode('Front:')
            ->filter()
            ->map(function ($rawFlashCard) {
                $front = Str::of($rawFlashCard)->before('Back:')->trim();
                $back = Str::of($rawFlashCard)->between('Back:', PHP_EOL)->trim();
                return new self($front->__toString(), $back->__toString());
            })
            ->slice(1);

    }
}
