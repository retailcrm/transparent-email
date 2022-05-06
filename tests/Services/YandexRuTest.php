<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\YandexRu;
use PHPUnit\Framework\TestCase;

class YandexRuTest extends TestCase
{
    /**
     * @dataProvider providerGetPrimaryEmail
     *
     * @param string $inputEmail
     * @param string $outputEmail
     * @param bool $cyrillicAllowed
     */
    public function testGetPrimaryEmail(string $inputEmail, string $outputEmail, bool $cyrillicAllowed = false)
    {
        $this->assertEquals($outputEmail, (new YandexRu())->getPrimaryEmail(new Email($inputEmail, false, $cyrillicAllowed)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['foobar@YANDEX.RU', 'foobar@yandex.ru'],
            ['fOObar@YAndEX.ru', 'foobar@yandex.ru'],
            ['foobar+alias@yandex.ru', 'foobar@yandex.ru'],
            ['JaneDoe@ya.ru', 'janedoe@yandex.ru'],
            ['Jane.Doe@ya.ru', 'jane-doe@yandex.ru'],
            ['foobar@yandex.com', 'foobar@yandex.ru'],
            ['foobar@yandex.by', 'foobar@yandex.ru'],
            ['foobar@yandex.kz', 'foobar@yandex.ru'],
            ['foobar@yandex.ua', 'foobar@yandex.ru'],
            // Cyrillic use
            ['иванов@YANDEX.RU', 'иванов@yandex.ru', true],
            ['иванОВ@YAndEX.ru', 'иванов@yandex.ru', true],
            ['иванов+alias@yandex.ru', 'иванов@yandex.ru', true],
            ['ИвановИван@ya.ru', 'ивановиван@yandex.ru', true],
            ['Иванов.Иван@ya.ru', 'иванов-иван@yandex.ru', true],
            ['иванов@yandex.com', 'иванов@yandex.ru', true],
            ['иванов@yandex.by', 'иванов@yandex.ru', true],
            ['иванов@yandex.kz', 'иванов@yandex.ru', true],
            ['иванов@yandex.ua', 'иванов@yandex.ru', true],
        ];
    }

    /**
     * @dataProvider providerIsSupported
     *
     * @param string $domain
     * @param bool $result
     */
    public function testIsSupported(string $domain, bool $result)
    {
        $this->assertSame($result, (new YandexRu())->isSupported(new Email('Jane.Doe@' . $domain)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['yandex.ru', true],
            ['yandex.com', true],
            ['yandex.by', true],
            ['yandex.kz', true],
            ['yandex.ua', true],
            ['yandex.RU', true],
            ['yan.dex.ru', false],
            ['YANDEX.RU', true],
        ];
    }
}