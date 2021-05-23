<?php


namespace App\Exceptions;


use App\Errors\Errors;
use Exception;
use Throwable;


class CannotDeleteACoinException extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = Errors::COIN_COULD_NOT_BE_DELETED;
        parent::__construct($message, $code, $previous);
    }

}
