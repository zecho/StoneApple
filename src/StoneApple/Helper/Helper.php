<?php

namespace StoneApple\Helper;

class Helper
{
    /**
     * Removes all linebreaks
     *
     * @link http://antoine.goutenoir.com/blog/2010/10/11/php-slugify-a-string/
     * @param string $string The text to be processed.
     * @return string The given text without any linebreaks.
     */
    public static function removeLinebreaks ($string)
    {
        return (string) str_replace(array("\r", "\r\n", "\n"), '', $string);
    }

    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     * Note : Works with UTF-8
     * @link http://antoine.goutenoir.com/blog/2010/10/11/php-slugify-a-string/
     * @param  string $string The text to slugify
     * @return string The slugified text
     */
    public static  function slugify($string)
    {
        $string = strip_tags($string); // remove htmls tags 
        $string = trim($string);
        $string = utf8_decode($string);
        $string = html_entity_decode($string);

        $a = 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ';
        $b = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn';
        $string = strtr($string, utf8_decode($a), $b);

        $unwanted = array("(", ")", "?", ".", "!", ",");
        $string = str_replace($unwanted, "", $string);

        $string = preg_replace('/([^a-z0-9]+)/i', '-', $string);
        $string = strtolower($string);
        $string = static::removeLinebreaks($string);
        if (empty($string)) return 'n-a';

        return utf8_encode($string);
    }
}