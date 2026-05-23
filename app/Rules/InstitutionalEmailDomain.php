<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InstitutionalEmailDomain implements ValidationRule
{
    private const ALLOWED_DOMAINS = [
        'telkomuniversity.ac.id',
        'ittelkom-pwt.ac.id',
    ];

    /**
     * Run the validation rule.
     *
     * Extracts the domain portion of the email (after '@') and checks it
     * against the exact allowed domain list. Subdomain variations such as
     * mail.telkomuniversity.ac.id are intentionally rejected.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = strtolower(substr(strrchr($value, '@'), 1));
        if (!in_array($domain, self::ALLOWED_DOMAINS, true)) {
            $fail('Email domain is not permitted. Only telkomuniversity.ac.id and ittelkom-pwt.ac.id are allowed.');
        }
    }
}
