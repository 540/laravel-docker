<?php

namespace Tests\Unit\Exceptions;

use App\Errors\Errors;
use App\Exceptions\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class UserNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     **/
    public function exceptionIsThrownI()
    {
        $this->expectException(UserNotFoundException::class);
        throw new UserNotFoundException();
    }

    /**
     * @test
     **/
    public function exceptionIsThrownII()
    {
        try {
            throw new UserNotFoundException();
        }catch(UserNotFoundException $exception)
        {
            $this->assertEquals(Errors::USER_NOT_FOUND, $exception->getMessage());
        }

    }

}
