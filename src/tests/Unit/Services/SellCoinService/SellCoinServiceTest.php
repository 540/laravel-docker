<?php

namespace Tests\Services\SellCoinService;

use App\Infraestructure\Database\DatabaseManager;
use App\Models\Coin;
use App\Services\OpenWalletService\OpenWalletService;
use App\Services\SellCoinService\SellCoinService;
use Exception;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class SellCoinServiceTest extends TestCase
{
    private $databaseManager;
    private SellCoinService $sellCoinService;

    protected function setUp():void
    {
        parent::setUp();
        $prophet = new Prophet;
        $this->databaseManager = $prophet->prophesize(DatabaseManager::class);
        $this->sellCoinService = new SellCoinService($this->databaseManager->reveal());
    }

    /**
     * @test
     * @throws Exception
     */
    public function getsErrorWhenCoinDoesNotExist()
    {
        $coinId = "invalidCoinId";
        $walletId = "validWalletId";
        $amountUSD = 0;

        $this->expectException(Exception::class);
        $this->databaseManager->set("coinId", $coinId)->willThrow(new Exception("Error"));

        $this->sellCoinService->execute($coinId, $walletId, $amountUSD);
    }
}
