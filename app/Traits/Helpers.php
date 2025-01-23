<?php

namespace App\Traits;

abstract class Helpers
{
    /**
     * @param string $value
     * @return array|string|string[]|null
     */
    public static function clearTags(string $value) : array|string|null
    {
        return preg_replace('(<(/?[^\>]+)>)', '', $value);
    }

    /**
     * @param string $value
     * @return array|string|null
     */
    public static function clearMask(string $value): array|string|null
    {
        return preg_replace('/\D+/', '', $value);
    }
}
