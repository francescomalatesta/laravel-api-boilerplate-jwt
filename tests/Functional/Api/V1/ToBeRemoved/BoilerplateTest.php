<?php

namespace App\Functional\Api\V1\ToBeRemoved;


use App\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BoilerplateTest extends TestCase
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

    public function testCanServePublicContent()
    {
        $this->get('api/hello')->assertExactJson([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    }

    public function testWillDenyUnauthorizedAccess()
    {
        $this->get('api/protected')->assertStatus(401);
    }

    public function testWillShowContentToAuthenticatedUser()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $token = json_decode($response->getContent(), true)['token'];

        $this->get('api/protected?token=' . $token, [])->assertExactJson([
            'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
        ]);
    }

    public function testWillRefreshToken()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $token = json_decode($response->getContent(), true)['token'];

        $this->get('api/protected?token=' . $token, [])->assertExactJson([
            'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
        ]);

        $refreshTokenResponse = $this->get('api/refresh?token=' . $token, []);

        $newToken = explode(' ', $refreshTokenResponse->headers->get('authorization'))[1];

        $this->assertNotEquals($newToken, $token);

        $this->get('api/protected?token=' . $newToken, [])->assertExactJson([
            'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
        ]);
    }
}
