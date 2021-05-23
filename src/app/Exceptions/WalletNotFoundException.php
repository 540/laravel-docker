<?php

namespace App\Exceptions;

use App\Errors\Errors;
use Exception;
use Throwable;

class WalletNotFoundException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = Errors::WALLET_NOT_FOUND;
        parent::__construct($message, $code, $previous);
    }
}
