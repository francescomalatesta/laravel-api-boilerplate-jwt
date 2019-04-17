<?php

namespace App\Functional\Api\V1\Controllers;

use Hash;
use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RefreshControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $user = new User([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $user->save();
    }

    public function testRefresh()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token = $responseJSON['token'];

        $this->post('api/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $token
        ])->assertJsonStructure([
            'status',
            'token',
            'expires_in'
        ])->isOk();
    }

    public function testRefreshWithError()
    {
        $response = $this->post('api/auth/refresh', [], [
            'Authorization' => 'Bearer Wrong'
        ]);

        $response->assertStatus(500);
    }
}
