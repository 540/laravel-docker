<?php

namespace Tests\app\Infrastructure\Controller;

use Mockery;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use Mockery\Exception;
use Tests\TestCase;

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
        $this->walletDataSource
            ->expects('add')
            ->once()
            ->andThrow(new Exception("Service unavailable"));

        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletCreated()
    {
        $wallet = new Wallet(1,[]);

        $this->walletDataSource
            ->expects('add')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['wallet_id' => '1']);
    }

    /**
     * @test
     */
    public function multipleWalletsCreated()
    {
        $wallet = new Wallet(2,[]);

        $this->walletDataSource
            ->expects('add')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['wallet_id' => '2']);
    }

}

