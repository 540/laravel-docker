<?php

namespace Tests\Integration\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class IsEarlyAdopterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function noUserFoundForGivenEmail()
    {
        User::factory(User::class)->create();

        $response = $this->get('/api/user/another@email.com');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'User not found']);
    }

    /**
     * @test
     */
    public function userIsEarlyAdopter()
    {
        User::factory(User::class)->create();

        $response = $this->get('/api/user/email@email.com');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => true]);
    }
}
