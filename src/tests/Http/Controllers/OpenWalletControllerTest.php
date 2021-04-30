<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OpenWalletController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;

class OpenWalletControllerTest extends TestCase
{
    /**
     * @test
     **/
    public function getErrorMessageWhenAInvalidUserIdIsReceived ()
    {
        $wrongUserId = "wrongUserId";

        $openWalletController = new OpenWalletController();
        $response = $openWalletController->openWallet($wrongUserId);

        $expectedResponseArray = array('error' => "Error while creating the wallet");

        $expectedResponse = json_encode($expectedResponseArray);

        $this->assertJson($expectedResponse, $response);
    }

}
