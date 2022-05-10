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
        // SetUp sin terminar

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);

        $this->openNewWalletService  = new OpenNewWalletService($this->walletDataSource);
    }

    /**
     * @test
     */
    public function userIdNotFound()
    {
        $idUser = "";
        $this->expectExceptionMessage("A user with the specified ID was not found");
        $this->openNewWalletService->execute($idUser);
    }
    /**
     * @test
     */
    /**
    public function walletNotCreated()
    {
        $idUser = "1";
        $this->expectExceptionMessage("Error: response status is 404");
        $this->openNewWalletService->execute($idUser);
    }*/

    /**
     * @test
     */
    public function walletServiceUnavailable()
    {
        $idUser = "1";
        $this->expectExceptionMessage("Service unavailable");
        $this->openNewWalletService->execute($idUser);
    }
    /**
     * @test
     */
    public function walletCreated()
    {
        $user_id = "2";
        $wallet = new Wallet();
        $wallet->data['wallet_id'] = 1;
        $wallet->data['user_id'] = $user_id;

        $this->walletDataSource
            ->expects('addById')
            ->with($user_id)
            ->once()
            ->andReturn($wallet);

        $walletData = $this->openNewWalletService->execute($user_id);

        $this->assertEquals(1, $walletData->data['wallet_id']);
    }

}

