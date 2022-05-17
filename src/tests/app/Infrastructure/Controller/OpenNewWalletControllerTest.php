<?php

namespace Tests\app\Infrastructure\Controller;

use Mockery;
use App\Application\UserDataSource\WalletDataSource;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;

class OpenNewWalletControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;


    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);

        $this->app->bind(WalletDataSource::class, fn () => $this->walletDataSource);
    }


    /**
     * @test
     */
    public function walletServiceUnavailable()
    {
        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletCreated()
    {
        $wallet = new Wallet();
        $wallet->data['wallet_id'] = 1;

        $this->walletDataSource
            ->expects('add')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['wallet_id' => '1']);
    }

}

