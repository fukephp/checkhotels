<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Find page login
     * 
     * @test
     */
    public function test_login_get_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Try to login existing user with correct credentials
     * 
     * @test
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->make([
            'password' => bcrypt($password = 'password123'),
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Try to login with wrong credentials
     * 
     * @test
     */
    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password123'),
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect('/login');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * Find page register
     * 
     * @test
     */
    public function test_register_get_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Register a new user
     * 
     * @test
     */
    public function test_it_register_new_user() 
    {
        $response = $this->post('/register', [
            'email' => 'test@testunit123.com',
            'username' => 'Test',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');
    }

    /**
     * Try register user with wrong password
     * 
     * @test
     */
    public function test_it_register_new_user_with_error_password_confirmation() 
    {
        $response = $this->post('/register', [
            'email' => 'test@testunit123.com',
            'username' => 'Test',
            'password' => 'test12345',
            'password_confirmation' => 'test1234'
        ]);
        $response->assertSessionHasErrors(['password_confirmation']);
        $response->assertRedirect('/');
    }

    /**
     * Try register user with wrong email
     * 
     * @test
     */
    public function test_it_register_new_user_with_error_email() 
    {
        $response = $this->post('/register', [
            'email' => 'testunit123.com',
            'username' => 'Test',
            'password' => 'test12345',
            'password_confirmation' => 'test1234'
        ]);
        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect('/');
    }
}
