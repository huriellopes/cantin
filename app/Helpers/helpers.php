<?php

declare(strict_types=1);

use AshAllenDesign\ShortURL\Classes\Builder;
use Illuminate\Support\Str;

if (!function_exists('username')) {
    /**
     * Retorna apenas o primeiro e o último nome (ex.: "Maria da Silva" -> "Maria Silva").
     * Com um único nome, devolve-o sozinho (sem espaço sobrando).
     */
    function username(string $name): string
    {
        $parts = array_values(array_filter(explode(' ', mb_trim($name)), fn (string $part): bool => $part !== ''));

        if ($parts === []) {
            return '';
        }

        $first = Str::ucfirst($parts[0]);

        if (count($parts) === 1) {
            return $first;
        }

        return $first . ' ' . Str::ucfirst(end($parts));
    }
}

if (!function_exists('shortURl')) {
    function shortURl(string $url): string
    {
        $shortURLObject = resolve(Builder::class)
            ->destinationUrl($url)
            ->trackVisits()
            ->trackIPAddress()
            ->singleUse()
            ->secure(false)
            ->trackRefererURL()
            ->make();

        return $shortURLObject->default_short_url;
    }
}

if (!function_exists('maskPhone')) {
    function maskPhone(string $phone, string $type = 'cel'): string
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);

        $matches = [];

        if ($type !== 'cel') {
            preg_match('/^(\d{2})(\d{4,5})(\d{4})$/', (string) $formatedPhone, $matches);

            if ($matches !== []) {
                return '(' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3];
            }
        }

        preg_match('/^(\d{2})(\d{4,5})(\d{4})$/', (string) $formatedPhone, $matches);

        if ($matches !== []) {
            return '(' . $matches[1] . ') 9 ' . $matches[2] . '-' . $matches[3];
        }

        return $phone;
    }
}

if (!function_exists('readingTime')) {
    function readingTime(string $text, $wordsPerMinute = 200): string
    {
        $words = str_word_count(strip_tags($text));
        $minutes = ceil($words / $wordsPerMinute);

        return $minutes . __(' minute(s) of reading.');
    }
}
