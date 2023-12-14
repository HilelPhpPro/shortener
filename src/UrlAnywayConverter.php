<?php

namespace Hillel\Shortener3;

class UrlAnywayConverter extends UrlConverter
{
    /**
     * @param string $url
     * @return string
     */
    public function encode(string $url): string
    {
        $this->validateUrl($url);
        return $this->generateAndSaveCode($url);
    }
}