<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ArrayOfIntegers implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return collect($value)->every(function ($value) {
            return is_int($value);
        });
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.array_of_integers');
    }
}
