<?php

namespace App\Services\Libraries;

/**
 * Service to convert any string into a properly alphabetized sting
 * i.e. moving short articles (A, And, The) to the end of the string separated by a comma
 */
class MakeAlphaService
{
    public static function alphabetize(string $string)
    {
        $articles = ['A', 'An', 'And', 'The'];
        $parts = array_map('trim', explode(' ', $string));

        while (in_array($parts[0], $articles)) {
            $article = $parts[0];
            array_shift($parts);
            $string = implode(' ', $parts) . ', ' . $article;
            return self::alphabetize($string); //recursion
        }

        return $string;
    }
}
