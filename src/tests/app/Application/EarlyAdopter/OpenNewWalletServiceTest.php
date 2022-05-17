<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\EarlyAdopter\OpenNewWalletService;
use App\Application\UserDataSource\WalletDataSource;
use App\Domain\Wallet;
use Mockery;
use PHPUnit\Framework\TestCase;

class OpenNewWalletServiceTest extends TestCase
{
    private OpenNewWalletService $openNewWalletService;
    private WalletDataSource $walletDataSource;


    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);

        $this->openNewWalletService  = new OpenNewWalletService($this->walletDataSource);
    }


    /**
     * @test
     */
    public function walletServiceUnavailable()
    {
        $this->expectExceptionMessage("Service unavailable");
        $this->openNewWalletService->execute();
    }
    /**
     * @test
     */
    public function walletCreated()
    {
        $wallet = new Wallet();
        $wallet->data['wallet_id'] = 1;

        $this->walletDataSource
            ->expects('addById')
            ->once()
            ->andReturn($wallet);

        $walletData = $this->openNewWalletService->execute();

        $this->assertEquals(1, $walletData->data['wallet_id']);
    }

}

