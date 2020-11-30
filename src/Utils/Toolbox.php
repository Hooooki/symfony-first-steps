<?php


namespace App\Utils;


class Toolbox
{

    /**
     * @param string $text
     * @return int
     */
    public function getWordsNumber(string $text): int
    {
        return str_word_count($text);
    }

    public function countLinks(string $text) : int
    {

        preg_match_all("<a href=\x22(.+?)\x22>", $text, $matches);

        return sizeof($matches);

    }

}