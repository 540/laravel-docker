<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\CannotDeleteACoinException;
use PHPUnit\Framework\TestCase;

class CannotDeleteACoinExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(CannotDeleteACoinException::class);
        throw new CannotDeleteACoinException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new CannotDeleteACoinException();
        }catch(CannotDeleteACoinException $exception)
        {
            $this->assertEquals(Errors::COIN_COULD_NOT_BE_DELETED, $exception->getMessage());
        }

    }

}
