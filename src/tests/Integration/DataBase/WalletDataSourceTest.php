<?php

namespace Tests\Integration\DataBase;

use App\Infrastructure\Database\WalletDataSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

use App\Models\Wallet;
use App\Models\Transaction;

class WalletDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function createWalletInsertingById(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->insertById(35);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function findWalletDataByWalletId(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->findWalletDataByWalletId(3);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function findWalletByUserId(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->findWalletByUserId(2);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function insertTransaction(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->insertTransaction('90', 3, 50000, 1, 50000, 'buy');
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function selectAmountBoughtCoins(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->selectAmountBoughtCoins(90, 3);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function selectAmountSoldCoins(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->selectAmountSoldCoins(90, 3);
        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function findTypeCoinsByIdWallet(){
        $walletDataSource = new WalletDataSource();
        $result = $walletDataSource->findTypeCoinsbyIdWallet(3);
        $this->assertNotNull($result);
    }
}
