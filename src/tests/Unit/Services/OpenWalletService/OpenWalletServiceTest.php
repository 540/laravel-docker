<?php

namespace Tests\Unit\Services\OpenWalletService;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Http\Controllers\OpenWalletController;
use App\Infraestructure\Database\DatabaseManager;
use App\Models\Wallet;
use App\Services\OpenWalletService\OpenWalletService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class OpenWalletServiceTest extends TestCase
{
    private Prophet $prophet;

    protected function setUp():void
    {
        parent::setUp();
        $this->prophet = new Prophet;
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function getsErrorWhenAUserDoesNotExist ()
    {
        $userId = 'invalid_id';

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->createWalletByUserId($userId)->shouldBeCalledOnce()->willReturn(null);

        $openWalletService = new OpenWalletService($eloquentWalletDataSource->reveal());

        $this->expectException(Exception::class);

        $openWalletService->execute($userId);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function getsSuccessfulOperationWhenUserIdIsFound ()
    {
        $userId = 1;
        $wallet = new Wallet();

        $wallet->id = 1;
        $wallet->user_id = $userId;

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->createWalletByUserId($userId)->shouldBeCalledOnce()->willReturn($wallet);

        $openWalletService = new OpenWalletService($eloquentWalletDataSource->reveal());

        $result = $openWalletService->execute($userId);

        $this->assertEquals($wallet->id, $result);
    }

}
