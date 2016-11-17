<?php

namespace App\Functional\Api\V1\Controllers;

use Config;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SignUpControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSignUpSuccessfully()
    {
        $this->post('api/auth/signup', [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '123456'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseStatus(201);
    }

    public function testSignUpSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.sign_up.release_token', true);

        $this->post('api/auth/signup', [
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '123456'
        ])->seeJsonStructure([
            'status', 'token'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseStatus(201);
    }

    public function testSignUpReturnsValidationError()
    {
        $this->post('api/auth/signup', [
            'name' => 'Test User',
            'email' => 'test@email.com'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(422);
    }
}
