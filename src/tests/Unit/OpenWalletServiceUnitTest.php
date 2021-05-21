<?php

namespace Tests\Unit;

use App\Http\Services\Adopter\OpenWalletService;
use App\Infrastructure\Database\WalletDataSource;
use App\Models\Wallet;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class OpenWalletServiceUnitTest extends TestCase
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
     * @throws \Exception
     */
    public function insertedUserIsNull_BadRequestIsGiven()
    {
        $idUser = "";
        $this->expectExceptionMessage("Bad request error");
        $this->openWalletService->execute($idUser);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function insertedUserIsOk_WalletIsCreated()
    {
        $idUser = "2";
        $wallet= new Wallet();
        $wallet->fill(['id' => "1", 'id_user' => $idUser]);

        $this->walletDataSource->insertById($idUser)->shouldBeCalledOnce()->willReturn($wallet);
        $walletData = $this->openWalletService->execute($idUser);

        $this->assertEquals("1", $walletData['id']);
    }
}
