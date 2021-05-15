<?php

namespace Tests\Integration\Controller;

use App\Models\User540;
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
        User540::factory(User540::class)->create();

        $response = $this->get('/api/user/another@email.com');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'User not found']);
    }

    /**
     * @test
     */
    public function userIsEarlyAdopter()
    {
        User540::factory(User540::class)->create();

        $response = $this->get('/api/user/email@email.com');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['earlyAdopter' => true]);
    }
}
