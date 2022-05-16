<?php

namespace Tests\app\Application\EarlyAdopter;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\EarlyAdopter\CoinService;
use App\Domain\Coin;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class CoinServiceTest extends TestCase
{
    private CoinService $coinService;
    private CoinDataSource $coinDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDataSource = Mockery::mock(CoinDataSource::class);

        $this->coinService = new CoinService($this->coinDataSource);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $id = 'not_existing_email@email.com';

        $user = new User(9999, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andThrow(new Exception('User not found'));

        $this->expectException(Exception::class);

        $this->isEarlyAdopterService->execute($email);
    }

    /**
     * @test
     */
    public function userIsNotEarlyAdopter()
    {
        $email = 'not_early_adopter@email.com';

        $user = new User(9999, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andReturn($user);

        $isUserEarlyAdopter = $this->isEarlyAdopterService->execute($email);

        $this->assertFalse($isUserEarlyAdopter);
    }

    /**
     * @test
     */
    public function userIsAnEarlyAdopter()
    {
        $email = 'not_early_adopter@email.com';

        $user = new User(300, $email);

        $this->userDataSource
            ->expects('findByEmail')
            ->with($email)
            ->once()
            ->andReturn($user);

        $isUserEarlyAdopter = $this->isEarlyAdopterService->execute($email);

        $this->assertTrue($isUserEarlyAdopter);
    }
}
