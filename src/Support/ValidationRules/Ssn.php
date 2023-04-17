<?php

namespace App\Rules;

/*
namespace App\Validators;
use Illuminate\Validation\Validator;
*/

use Illuminate\Contracts\Validation\Rule;
use Personnummer\Personnummer;

class Ssn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not valid.';

        return trans('validation.invalidSSN');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function passes($attribute, $value)
    {
        if (is_null($value)) {
            return true;
        }

        return Personnummer::valid($value);
    }
}
