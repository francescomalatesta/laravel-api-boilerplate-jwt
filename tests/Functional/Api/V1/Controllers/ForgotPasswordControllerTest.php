<?php

namespace App\Functional\Api\V1\Controllers;

use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ForgotPasswordControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSuccessfulForgotPassword()
    {
        $this->post('api/recovery', [
            'email' => 'test@email.com'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseOk();
    }

    public function testForgotPasswordWithNotFoundUser()
    {
        $this->post('api/recovery', [
            'email' => 'unknown@email.com'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(404);
    }

    public function testForgotPasswordWithValidationErrors()
    {
        $this->post('api/recovery', [
            'email' => 'i am not an email'
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
            'password' => '123456'
        ]);

        $user->save();
    }
}
