<?php

namespace Tests\app\Infrastructure\Controller;

use Mockery;
use App\Application\UserDataSource\WalletDataSource;
use App\Domain\Wallet;
use Exception;
use Tests\TestCase;

class GetWalletControllerTest extends TestCase
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
    public function walletNotFound()
    {
        $wallet_id = 1;
        $this->walletDataSource
            ->expects('get')
            ->once()
            ->with($wallet_id)
            ->andThrow(new Exception("A wallet with the specified ID was not found."));

        $response = $this->get('/api/wallet/' . $wallet_id);
        $response->assertExactJson(['error' => 'A wallet with the specified ID was not found.']);
    }


    /**
     * @test
     */
    public function walletServiceUnavailable()
    {
        $wallet_id = 1;
        $this->walletDataSource
            ->expects('get')
            ->once()
            ->with($wallet_id)
            ->andThrow(new Exception("Service unavailable"));

        $response = $this->get('/api/wallet/' . $wallet_id);
        $response->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletFound()
    {
        /*$wallet = new Wallet(1,[]);

        $this->walletDataSource
            ->expects('add')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/wallet/open');
        $response->assertExactJson(['wallet_id' => '1']);*/
    }


}

