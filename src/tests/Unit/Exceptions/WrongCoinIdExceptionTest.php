<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\WrongCoinIdException;
use PHPUnit\Framework\TestCase;

class WrongCoinIdExceptionTest extends TestCase
{

    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(WrongCoinIdException::class);
        throw new WrongCoinIdException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new WrongCoinIdException();
        }catch(WrongCoinIdException $exception)
        {
            $this->assertEquals(Errors::WRONG_COIN_ID, $exception->getMessage());
        }

    }

}
