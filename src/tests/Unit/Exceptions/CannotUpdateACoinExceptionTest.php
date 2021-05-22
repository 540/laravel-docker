<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\CannotUpdateACoinException;
use PHPUnit\Framework\TestCase;

class CannotUpdateACoinExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(CannotUpdateACoinException::class);
        throw new CannotUpdateACoinException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new CannotUpdateACoinException();
        }catch(CannotUpdateACoinException $exception)
        {
            $this->assertEquals(Errors::COIN_COULD_NOT_BE_UPDATED, $exception->getMessage());
        }

    }

}
