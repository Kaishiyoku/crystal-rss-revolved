<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class ValidFeedUrl implements Rule
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
        $heraRssCrawler = new HeraRssCrawler();

        return $heraRssCrawler->checkIfConsumableFeed($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.valid_feed_url');
    }
}
