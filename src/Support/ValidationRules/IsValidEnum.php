<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Support\BaseClasses\BaseEnum;

class IsValidEnum implements Rule
{
    private BaseEnum $enum;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(BaseEnum $enum)
    {
        $this->enum = $enum;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid. Possible values: "'.implode('", "', $this->enum::all()).'".';
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
        return in_array($value, $this->enum::all());
    }
}
