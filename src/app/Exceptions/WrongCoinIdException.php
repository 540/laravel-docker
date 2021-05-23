<?php

namespace App\Exceptions;

use App\Errors\Errors;
use Exception;
use Throwable;

class WrongCoinIdException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = Errors::WRONG_COIN_ID;
        parent::__construct($message, $code, $previous);
    }
}
