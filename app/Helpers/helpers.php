<?php

declare(strict_types=1);

use AshAllenDesign\ShortURL\Classes\Builder;
use Illuminate\Support\Str;

if (!function_exists('username')) {
    function username(string $name): string
    {
        $parts = explode(' ', $name);
        $firstName = array_shift($parts);
        $lastName = array_pop($parts);

        return Str::ucfirst($firstName) . ' ' . Str::ucfirst($lastName);
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
