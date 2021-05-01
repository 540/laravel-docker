<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\OpenWalletController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    /**
     * @test
     **/
    public function getErrorMessageWhenAInvalidUserIdIsReceived ()
    {
        Event::fake();

        $openWalletController = new OpenWalletController();

        $request = Request::create('/wallet/open', 'POST',[
            'title'     =>     'foo',
            'text'     =>     'bar',
        ]);

        $response = $openWalletController->openWallet($request);
        $json = json_encode(array('error' => "Error while creating the wallet"));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals($json, $response->getContent());
    }
}
