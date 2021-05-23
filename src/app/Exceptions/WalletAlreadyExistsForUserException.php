<?php

namespace App\Exceptions;

use App\Errors\Errors;
use Exception;
use Throwable;

class WalletAlreadyExistsForUserException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = Errors::WALLET_ALREADY_EXISTS_FOR_THIS_USER;
        parent::__construct($message, $code, $previous);
    }
}
