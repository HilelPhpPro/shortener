<?php

namespace Hillel\Shortener3;

use Hillel\Shortener3\Exceptions\DataNotFoundException;
use Hillel\Shortener3\Interfaces\{ICodeRepository, IUrlDecoder, IUrlEncoder, IUrlValidator};
use Hillel\Shortener3\ValueObjects\UrlCodePair;
use InvalidArgumentException;

class UrlConverter implements IUrlEncoder, IUrlDecoder
{
    const CODE_LENGTH = 6;
    const CODE_CHAIRS = '0123456789abcdefghijklmnopqrstuvwxyz';

    /**
     * @param ICodeRepository $repository
     * @param IUrlValidator $validator
     * @param int $codeLength
     */
    public function __construct(
        protected ICodeRepository $repository,
        protected IUrlValidator   $validator,
        protected int             $codeLength = self::CODE_LENGTH
    )
    {

    }

    /**
     * @param string $url
     * @return string
     * @throws InvalidArgumentException
     */
    public function encode(string $url): string
    {
        $this->validateUrl($url);
        try {
            $code = $this->repository->getCodeByUrl($url);
        } catch (DataNotFoundException) {
            $code = $this->generateAndSaveCode($url);
        }
        return $code;
    }

    /**
     * @param string $code
     * @return string
     * @throws InvalidArgumentException
     */
    public function decode(string $code): string
    {
        try {
            $code = $this->repository->getUrlByCode($code);
        } catch (DataNotFoundException $e) {
            throw new InvalidArgumentException(
                $e->getMessage(),
                $e->getCode(),
                $e->getPrevious()
            );
        }
        return $code;
    }

    /**
     * @description цей метод робить тето
     * @param string $url
     * @return string
     */
    protected function generateAndSaveCode(string $url): string
    {
        $code = $this->generateUniqueCode();
        $this->repository->saveEntity(new UrlCodePair($code, $url));
        return $code;
    }

    protected function validateUrl(string $url): bool
    {
        $result = $this->validator->validateUrl($url);
        $this->validator->checkRealUrl($url);
        return $result;
    }

    protected function generateUniqueCode(): string
    {
        $date = new \DateTime();
        $str = static::CODE_CHAIRS . mb_strtoupper(static::CODE_CHAIRS) . $date->getTimestamp();
        $code = substr(str_shuffle($str), 0, $this->codeLength);
        if ($this->repository->codeIsset($code)) {
            $code = $this->generateUniqueCode();
        }
        return $code;
    }
}