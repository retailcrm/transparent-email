<?php

namespace bkrukowski\TransparentEmail\Services;

use bkrukowski\TransparentEmail\Emails\EditableEmail;
use bkrukowski\TransparentEmail\Emails\EmailInterface;

class IcloudCom implements ServiceInterface
{
    public function getPrimaryEmail(EmailInterface $email) : EmailInterface
    {
        return (new EditableEmail($email))
            ->removeSuffixAlias('+')
            ->lowerCaseLocalPartIf(true);
    }

    public function isSupported(EmailInterface $email) : bool
    {
        return in_array($email->getDomain(), ['icloud.com']);
    }
}