<?php

namespace bkrukowski\TransparentEmail\Tests\Services;

use bkrukowski\TransparentEmail\Emails\Email;
use bkrukowski\TransparentEmail\Services\IcloudCom;
use PHPUnit\Framework\TestCase;

class IcloudComTest extends TestCase
{
    /**
     * @dataProvider providerGetPrimaryEmail
     *
     * @param string $inputEmail
     * @param string $outputEmail
     */
    public function testGetPrimaryEmail(string $inputEmail, string $outputEmail)
    {
        $this->assertEquals($outputEmail, (new IcloudCom())->getPrimaryEmail(new Email($inputEmail)));
    }

    public function providerGetPrimaryEmail() : array
    {
        return [
            ['foobar@ICLOUD.COM', 'foobar@icloud.com'],
            ['foobar+alias@icloud.com', 'foobar@icloud.com'],
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
        $this->assertSame($result, (new IcloudCom())->isSupported(new Email('Jane.Doe@' . $domain)));
    }

    public function providerIsSupported() : array
    {
        return [
            ['icloud.com', true],
            ['ICLOUD.COM', true],
            ['i.cloud.com', false],
        ];
    }
}