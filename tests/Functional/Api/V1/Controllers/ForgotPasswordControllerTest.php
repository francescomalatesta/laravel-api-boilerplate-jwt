<?php

namespace App\Functional\Api\V1\Controllers;

use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ForgotPasswordControllerTest extends TestCase
{
    use DatabaseMigrations;

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

    public function testForgotPasswordRecoverySuccessfully()
    {
        $this->post('api/auth/recovery', [
            'email' => 'test@email.com'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseOk();
    }

    public function testForgotPasswordRecoveryReturnsUserNotFoundError()
    {
        $this->post('api/auth/recovery', [
            'email' => 'unknown@email.com'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(404);
    }

    public function testForgotPasswordRecoveryReturnsValidationErrors()
    {
        $this->post('api/auth/recovery', [
            'email' => 'i am not an email'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(422);
    }
}
