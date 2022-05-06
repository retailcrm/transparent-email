<?php

declare(strict_types=1);

namespace bkrukowski\TransparentEmail\Emails;

class Email implements EmailInterface
{
    use EmailTrait;

    const PATTERN_WITH_SUPPORT_CYRILLIC = '/^[a-zA-Zа-яА-Я0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Zа-яА-Я0-9](?:[a-zA-Zа-яА-Я0-9-]{0,61}[a-zA-Zа-яА-Я0-9])?(?:\.[a-zA-Zа-яА-Я0-9](?:[a-zA-Zа-яА-Я0-9-]{0,61}[a-zA-Zа-яА-Я0-9])?)+$/u';

    public function __construct(string $email, bool $caseSensitive = false, bool $cyrillicAllowed = false)
    {
        $this->validateEmail($email, $cyrillicAllowed);
        list($this->localPart, $this->domain) = explode('@', $email);
        if (!$caseSensitive) {
            $this->localPart = mb_strtolower($this->localPart);
        }
        $this->domain = mb_strtolower($this->domain);
    }

    private function validateEmail(string $email, bool $cyrillicAllowed)
    {
        if ($cyrillicAllowed && !preg_match(self::PATTERN_WITH_SUPPORT_CYRILLIC, $email)) {
            throw new InvalidEmailException("Email '{$email}' is not valid!");
        }

        if (!$cyrillicAllowed && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Email '{$email}' is not valid!");
        }
    }
}