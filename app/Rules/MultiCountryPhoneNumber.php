<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultiCountryPhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(\+?\d{1,3})\d{9,12}$/', $value);
    }

    public function message()
    {
        return 'Format nomor telepon tidak valid.';
    }
}
