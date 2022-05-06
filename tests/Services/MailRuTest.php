<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\MailRu;
use PHPUnit\Framework\TestCase;

class MailRuTest extends TestCase
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
        $this->assertEquals($outputEmail, (new MailRu())->getPrimaryEmail(new Email($inputEmail, false, $cyrillicAllowed)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['foobar@MAIL.RU', 'foobar@mail.ru'],
            ['fOObar@MaiL.Ru', 'foobar@mail.ru'],
            ['foobar+alias@mail.ru', 'foobar@mail.ru'],
            // Cyrillic use
            ['иванов@MAIL.RU', 'иванов@mail.ru', true],
            ['иванОВ@MaiL.Ru', 'иванов@mail.ru', true],
            ['иванов+alias@mail.ru', 'иванов@mail.ru', true],
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
        $this->assertSame($result, (new MailRu())->isSupported(new Email('Jane.Doe@' . $domain)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['mail.ru', true],
            ['mail.RU', true],
            ['MAIL.RU', true],
            ['ma.il.ru', false],
        ];
    }
}