<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Services;

use bkrukowski\TransparentEmail\Emails\EditableEmail;
use bkrukowski\TransparentEmail\Emails\EmailInterface;

class YandexRu implements ServiceInterface
{
    public function getPrimaryEmail(EmailInterface $email) : EmailInterface
    {
        return (new EditableEmail($email))
            ->removeSuffixAlias('+')
            ->replaceInLocalPart('.', '-')
            ->lowerCaseLocalPartIf(true)
            ->setDomain($this->mapDomain($email->getDomain()));
    }

    public function isSupported(EmailInterface $email) : bool
    {
        return in_array($email->getDomain(), ['ya.ru', 'yandex.com', 'yandex.ru', 'yandex.by', 'yandex.kz', 'yandex.ua']);
    }

    protected function getDomainMapping() : array
    {
        return [
            'ya.ru' => 'yandex.ru',
            'yandex.com' => 'yandex.ru',
            'yandex.by' => 'yandex.ru',
            'yandex.kz' => 'yandex.ru',
            'yandex.ua' => 'yandex.ru'
        ];
    }

    private function mapDomain(string $domain) : string
    {
        return $this->getDomainMapping()[$domain] ?? $domain;
    }
}