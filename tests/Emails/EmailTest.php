<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Tests\Emails;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Emails\InvalidEmailException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @dataProvider providerConstructor
     *
     * @param string $email
     * @param bool $expectedException
     * @param string $localPart
     * @param string $domain
     * @param bool $caseSensitive
     * @param bool $cyrillicAllowed
     */
    public function testConstructor(
        string $email,
        bool $expectedException,
        string $localPart = '',
        string $domain = '',
        bool $caseSensitive = false,
        bool $cyrillicAllowed = false
    ) {
        if ($expectedException) {
            $this->expectException(InvalidEmailException::class);
        }
        $object = new Email($email, $caseSensitive, $cyrillicAllowed);
        $this->assertSame($localPart, $object->getLocalPart());
        $this->assertSame($domain, $object->getDomain());
    }

    public function providerConstructor() : array
    {
        return [
            ['john doe@example.com', true],
            ['.johndoe@example.com', true],
            ['Jane.Doe@Example.COM', false, 'Jane.Doe', 'example.com', true],
            ['Jane.Doe@Example.COM', false, 'jane.doe', 'example.com'],
            // Cyrillic use
            ['тест тест@example.com', true],
            ['.тест@example.com', true],
            ['Тест.Тест@Example.COM', false, 'Тест.Тест', 'example.com', true, true],
            ['Тест.Тест@Example.COM', false, 'тест.тест', 'example.com', false, true],
            ['Тест.Тест@Тест.РУ', false, 'Тест.Тест', 'тест.ру', true, true],
            ['Тест.Тест@Тест.РУ', false, 'тест.тест', 'тест.ру', false, true],
        ];
    }
}