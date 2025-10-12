<?php

namespace App\Exceptions;

use Exception;

class FeedNotFoundException extends Exception
{
    public function __construct(string $feedUrl)
    {
        parent::__construct("Feed with url {$feedUrl} not found.");
    }
}
