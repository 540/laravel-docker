<?php


namespace App\Errors;


abstract class Errors
{
    public const ERROR_FIELD = 'Error';
    public const USER_ALREADY_HAS_A_WALLET = 'A user with the specified ID already has a wallet.';
    public const BAD_REQUEST = 'Request fields have some errors.';
}
