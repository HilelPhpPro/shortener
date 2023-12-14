<?php

namespace Hillel\Shortener3\Interfaces;

use InvalidArgumentException;
interface IUrlValidator
{
    /**
     * @param string $url
     * @throws InvalidArgumentException
     * @return bool
     */
    public function validateUrl(string $url): bool;

    /**
     * @param string $url
     * @throws InvalidArgumentException
     * @return bool
     */
    public function checkRealUrl(string $url): bool;
}