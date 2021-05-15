<?php

namespace Tests\Integration\DataSources;

use App\DataSource\Database\EloquentUserDataSource;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentUserDataSourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function findsUserByEmail()
    {
        User::factory(User::class)->create();
        $eloquentUserDataSource = new EloquentUserDataSource();

        $user = $eloquentUserDataSource->findByEmail('email@email.com');

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function noUserIsFoundForTheGivenEmailI()
    {
        $eloquentUserDataSource = new EloquentUserDataSource();

        $this->expectException(Exception::class);

        $eloquentUserDataSource->findByEmail('email@email.com');
    }

    /**
     * @test
     */
    public function noUserIsFoundForTheGivenEmailII()
    {
        User::factory(User::class)->create();
        $eloquentUserDataSource = new EloquentUserDataSource();

        try {
            $eloquentUserDataSource->findByEmail('not_known@email.com');
        } catch (Exception $exception) {
            $this->assertEquals('User not found', $exception->getMessage());
        }

    }
}
