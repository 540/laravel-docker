<?php

namespace Tests\Unit\Services\OpenWalletService;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Services\OpenWallet\OpenWalletService;
use Exception;
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
    public function getsErrorWhenUserIdIsInvalid ()
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
    public function getsSuccessfulOperationWhenUserIdIsValid ()
    {
        $userId = 'validUserId';
        $walletId = 1;

        $eloquentWalletDataSource = $this->prophet->prophesize(EloquentWalletDataSource::class);
        $eloquentWalletDataSource->createWalletByUserId($userId)->shouldBeCalledOnce()->willReturn($walletId);

        $openWalletService = new OpenWalletService($eloquentWalletDataSource->reveal());

        $result = $openWalletService->execute($userId);

        $this->assertEquals($walletId, $result);
    }

}
