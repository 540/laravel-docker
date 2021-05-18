<?php


namespace App\Errors;


abstract class Errors
{
    public const ERROR_FIELD = 'Error';
    public const WALLET_ALREADY_EXISTS_FOR_THIS_USER = 'User with the specified ID already has a wallet.';
    public const BAD_REQUEST = 'Request fields have some errors.';
    public const WALLET_NOT_FOUND = 'A wallet with the specified ID was not found.';
    public const WRONG_COIN_ID = 'A coin with a wrong ID was found in the wallet';
    public const COIN_ID_NOT_FOUND = 'A coin ID was not found in the wallet';
    public const COIN_COULD_NOT_BE_CREATED_OR_UPDATED = 'A coin could not be created or updated';
    public const COIN_SPICIFIED_ID_NOT_FOUND = 'A coin with the specified ID was not found';
    public const BAD_REQUEST_ERROR = 'bad request error';
}
