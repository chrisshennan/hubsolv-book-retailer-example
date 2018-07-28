<?php

namespace App\Exception;

class InvalidApiToken extends \Exception
{
    protected $message = 'Invalid API Token';
}