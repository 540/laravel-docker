<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\WalletNotFoundException;
use PHPUnit\Framework\TestCase;

class WalletNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(WalletNotFoundException::class);
        throw new WalletNotFoundException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new WalletNotFoundException();
        }catch(WalletNotFoundException $exception)
        {
            $this->assertEquals('A wallet with the specified ID was not found.', $exception->getMessage());
        }

    }
}
