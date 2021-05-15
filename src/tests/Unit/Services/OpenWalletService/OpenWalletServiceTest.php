<?php

namespace Tests\Unit\Services\OpenWalletService;

use App\DataSource\Database\EloquentWalletDataSource;
use App\Exceptions\WalletAlreadyExistsForUserException;
use App\Services\OpenWallet\OpenWalletService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class OpenWalletServiceTest extends TestCase
{
    private $eloquentWalletDataSource;
    private OpenWalletService $openWalletService;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->openWalletService = new OpenWalletService($this->eloquentWalletDataSource->reveal());
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function walletIsNotOpenedGivenAnInvalidUserId ()
    {
        $userId = 'invalid_id';

        $this->eloquentWalletDataSource->createWalletByUserId($userId)->shouldBeCalledOnce()->willThrow(WalletAlreadyExistsForUserException::class);

        $this->expectException(WalletAlreadyExistsForUserException::class);

        $this->openWalletService->execute($userId);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function walletIsOpenedGivenAValidUserId ()
    {
        $userId = 'validUserId';
        $walletId = 1;

        $this->eloquentWalletDataSource->createWalletByUserId($userId)->shouldBeCalledOnce()->willReturn($walletId);

        $result = $this->openWalletService->execute($userId);

        $this->assertEquals($walletId, $result);
    }

}
