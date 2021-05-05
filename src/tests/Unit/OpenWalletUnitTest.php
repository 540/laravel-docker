<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class OpenWalletUnitTest extends TestCase
{
    /**
     * @var OpenWalletService|WalletDataSource|\Prophecy\Prophecy\ObjectProphecy
     */
    private $walletDataSource;
    /**
     * @var OpenWalletService
     */
    private $openWalletService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->walletDataSource = $prophet->prophesize(WalletDataSource::class);

        $this->openWalletService = new OpenWalletService($this->walletDataSource->reveal());
    }

    /**
     * @test
     */
    public function insertedUserIsNull_BadRequestIsGiven()
    {
        $idUser = "";
        try {
            $this->openWalletService->execute($idUser);
        }catch (\Exception $exception) {
            $this->assertEquals("Bad request error",$exception->getMessage());
        }
    }

    /**
     * @test
     */
    public function insertedUserIsOk_WalletIsCreated()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id_user' => $idUser, 'id_wallet' => "1"]);

        $this->walletDataSource->insertById($idUser)->shouldBeCalledOnce()->willReturn($wallet);
        $walletData = $this->openWalletService->execute($idUser);

        $this->assertEquals("1", $walletData['id_wallet']);
    }
}
