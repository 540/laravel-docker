<?php

namespace Tests\Unit\Services\Wallet;

use App\DataSource\Database\EloquentUserDataSource;
use App\DataSource\Database\EloquentWalletDataSource;
use App\Services\Wallet\PostWalletOpenService;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class PostWalletOpenServiceTest extends TestCase
{
    /**
     * @var EloquentWalletDataSource
     * @var EloquentUserDataSource
     */
    private $eloquentWalletDataSource;
    private $eloquentUserDataSource;

    /**
     * @var PostWalletOpenService
     */
    private $postWalletOpenService;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();
        $prophet = new Prophet();
        $this->eloquentWalletDataSource = $prophet->prophesize(EloquentWalletDataSource::class);
        $this->eloquentUserDataSource = $prophet->prophesize(EloquentUserDataSource::class);

        $this->postWalletOpenService = new PostWalletOpenService(
            $this->eloquentWalletDataSource->reveal(),
            $this->eloquentUserDataSource->reveal()
        );
    }

    /**
     * @test
     */
    public function postWalletOpenUserNotFound()
    {
        $userId = 'error-user';
        $expectedExistsByUserIdDatabaseUserReturn = false;
        $expectedCreateWalletByUserIdDatabaseWalletReturn = 'wallet-000000001';
        $expectedResult = "User not found";

        $this->eloquentUserDataSource
            ->existsByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByUserIdDatabaseUserReturn);
        $this->eloquentWalletDataSource
            ->createWalletByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCreateWalletByUserIdDatabaseWalletReturn);

        try {
            $this->postWalletOpenService->execute($userId);
        } catch (Exception $exception) {
            $this->assertEquals($expectedResult, $exception->getMessage());
            return;
        }
        $this->fail('Exception not catch!');
    }

    /**
     * @test
     */
    public function postWalletOpenWorking()
    {
        $userId = 'test-user';
        $expectedExistsByUserIdDatabaseUserReturn = true;
        $expectedCreateWalletByUserIdDatabaseWalletReturn = 'wallet-000000001';
        $expectedResult = "wallet-000000001";

        $this->eloquentUserDataSource
            ->existsByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedExistsByUserIdDatabaseUserReturn);
        $this->eloquentWalletDataSource
            ->createWalletByUserId($userId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCreateWalletByUserIdDatabaseWalletReturn);

        try {
            $result = $this->postWalletOpenService->execute($userId);
        } catch (Exception $exception) {
            $this->fail("Failure by exception catch! - " . $exception->getMessage());
        }
        $this->assertEquals($expectedResult, $result);
    }
}
