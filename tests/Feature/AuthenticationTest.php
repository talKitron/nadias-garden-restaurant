<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    /**
     * This test ensures that all required fields for the registration process are filled accordingly.
     */
    public function testRequiredFielsForRegistration() {
        $this->json('POST', '/api/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);

    }

    /**
     * Test mandatory repeating passwords. 
     * The repeated password must match the first one for this test to pass.
     */
    public function testRepeatPassword() {
        $userData = [
            "name" => "John Doe",
            "email" => "jdoe@testdomain.com",
            "password" => "test12345",
        ];

        $this->json('POST', '/api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }

    /**
     * Test App\User successful registration
     */
    public function testSuccessfulRegistration() {
        $userData = [
            'name' => 'John Doe',
            'email' => 'jdoe@testdomain.com',
            'password' => 'test12345',
            'password_confirmation' => 'test12345'
        ];

        $this->json('POST', '/api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'access_token',
                'message'
            ]);
    }

    public function testMustEnterEmailAndPassword() {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessfulLogin() {
        $user = factory(User::class)->create([
           'email' => 'sample@test.com',
           'password' => bcrypt('sample123'),
        ]);


        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
               "user" => [
                   'id',
                   'name',
                   'email',
                   'email_verified_at',
                   'created_at',
                   'updated_at',
               ],
                "access_token",
                "message"
            ]);

        $this->assertAuthenticated();
    }

}
