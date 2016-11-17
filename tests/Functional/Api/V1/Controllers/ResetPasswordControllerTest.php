<?php

namespace App\Functional\Api\V1\Controllers;

use DB;
use Config;
use App\User;
use App\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResetPasswordControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = new User([
            'name' => 'Test User',
            'email' => 'test@email.com',
            'password' => '123456'
        ]);
        $user->save();

        DB::table('password_resets')->insert([
            'email' => 'test@email.com',
            'token' => 'my_super_secret_code',
            'created_at' => Carbon::now()
        ]);
    }

    public function testResetSuccessfully()
    {
        $this->post('api/auth/reset', [
            'email' => 'test@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseOk();
    }

    public function testResetSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.reset_password.release_token', true);

        $this->post('api/auth/reset', [
            'email' => 'test@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ])->seeJsonStructure([
            'status',
            'token'
        ])->seeJson([
            'status' => 'ok'
        ])->assertResponseOk();
    }

    public function testResetReturnsProcessError()
    {
        $this->post('api/auth/reset', [
            'email' => 'unknown@email.com',
            'token' => 'this_code_is_invalid',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(500);
    }

    public function testResetReturnsValidationError()
    {
        $this->post('api/auth/reset', [
            'email' => 'test@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass'
        ])->seeJsonStructure([
            'error'
        ])->assertResponseStatus(422);
    }
}
