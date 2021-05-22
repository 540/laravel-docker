<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\CoinIdNotFoundInWalletException;
use PHPUnit\Framework\TestCase;

class CoinIdNotFoundInWalletExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(CoinIdNotFoundInWalletException::class);
        throw new CoinIdNotFoundInWalletException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new CoinIdNotFoundInWalletException();
        }catch(CoinIdNotFoundInWalletException $exception)
        {
            $this->assertEquals(Errors::COIN_ID_NOT_FOUND, $exception->getMessage());
        }

    }

}
