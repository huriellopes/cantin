<?php

declare(strict_types=1);

if (!function_exists('maskPhone')) {
    /**
     * @param string $phone
     * @param string $type
     * @return string
     */
    function maskPhone(string $phone, string $type = "cel"): string
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);

        $matches = [];

        if ($type !== "cel") {
            preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

            if ($matches) {
                return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
            }
        }

        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);

        if ($matches) {
            return '('.$matches[1].') 9 '.$matches[2].'-'.$matches[3];
        }

        return $phone;
    }
}

if (! function_exists('readingTime')) {
    function readingTime(string $text, $wordsPerMinute = 200): string
    {
        $words = str_word_count(strip_tags($text));
        $minutes = ceil($words / $wordsPerMinute);

        return $minutes.__(' minute(s) of reading.');
    }
}
