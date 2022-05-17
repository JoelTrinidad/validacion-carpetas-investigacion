<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_login_form()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/');
    }
    
    public function test_user_can_login()
    {
        $password = 'anyuser1234';
        $user = factory(User::class)->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('user1234'),
        ]);
        
        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function test_user_cannot_view_registration_form()
    {
        $response = $this->get('register');

        $response->assertRedirect('/login');
        
    }

    public function test_user_can_view_registration_form_when_authenticated()
    {
        $user = factory(User::class)->make();

        $response = $this->actingAs($user)->get('register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('user1234'),
        ]);

        $response = $this->actingAs($user)->post('/register', [
            'dependencia' => 'ssc',
            'curp' => 'PERC561125MSPRMT03',
            'email' => 'user@yopmail.com',
            'oficio_alta' => 'oficio de alta',
            'password' => 'user1234',
            'password_confirmation' => 'user1234',
        ]);

        $response->assertRedirect('/');
        $this->assertCount(2, $users = User::all());
        $user = $users->last();
        $this->assertEquals('PERC561125MSPRMT03', $user->curp);
        $this->assertEquals('user@yopmail.com', $user->email);
        $this->assertTrue(Hash::check('user1234', $user->password));
    }
}
