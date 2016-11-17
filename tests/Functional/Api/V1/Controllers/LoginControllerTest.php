<?php

namespace App\Functional\Api\V1\Controllers;

use Hash;
use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSuccessfulLogin()
    {
        $this->post('api/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ])->seeJson([
            'status' => 'ok'
        ])->seeJsonStructure([
            'status',
            'token'
        ])->assertResponseOk();
    }

    public function testLoginWithWrongCredentials()
    {
        $this->post('api/login', [
            'email' => 'unknown@email.com',
            'password' => '123456'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(403);
    }

    public function testLoginWithBadRequest()
    {
        $this->post('api/login', [
            'email' => 'test@email.com'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(422);
    }

    public function setUp()
    {
        parent::setUp();

        $user = new User([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => Hash::make('123456')
        ]);

        $user->save();
    }
}
