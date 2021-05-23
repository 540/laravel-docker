<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\CannotCreateACoinException;
use PHPUnit\Framework\TestCase;

class CannotCreateACoinExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(CannotCreateACoinException::class);
        throw new CannotCreateACoinException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new CannotCreateACoinException();
        }catch(CannotCreateACoinException $exception)
        {
            $this->assertEquals(Errors::COIN_COULD_NOT_BE_CREATED, $exception->getMessage());
        }

    }

}
