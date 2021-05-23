<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\WalletAlreadyExistsForUserException;
use PHPUnit\Framework\TestCase;

class WalletAlreadyExistsForUserExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(WalletAlreadyExistsForUserException::class);
        throw new WalletAlreadyExistsForUserException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new WalletAlreadyExistsForUserException();
        }catch(WalletAlreadyExistsForUserException $exception)
        {
            $this->assertEquals('User with the specified ID already has a wallet.', $exception->getMessage());
        }

    }
}
