<?php

namespace Hillel\Short\Interfaces;

use Hillel\Short\ValueObjects\UrlCodePair;
use Hillel\Short\Exceptions\DataNotFoundException;

interface ICodeRepository
{
    /**
     * @param UrlCodePair $urlUrlCodePair
     * @return bool
     */
    public function saveEntity(UrlCodePair $urlUrlCodePair): bool;

    /**
     * @param string $code
     * @return bool
     */
    public function codeIsset(string $code): bool;

    /**
     * @param string $code
     * @throws DataNotFoundException
     * @return string url
     */
    public function getUrlByCode(string $code): string;

    /**
     * @param string $url
     * @throws DataNotFoundException
     * @return string code
     */
    public function getCodeByUrl(string $url): string;

}