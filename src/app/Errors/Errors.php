<?php


namespace App\Errors;


abstract class Errors
{
    public const ERROR_FIELD = 'Error';
    public const WALLET_ALREADY_EXISTS_FOR_THIS_USER = 'User with the specified ID already has a wallet.';
    public const BAD_REQUEST = 'Request fields have some errors.';
    public const WALLET_NOT_FOUND = 'A wallet with the specified ID was not found.';
    public const WRONG_COIN_ID = 'A coin with a wrong ID was found in the wallet';
}
