<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can view registration form.
     */
    public function test_user_can_view_signup_form(): void
    {
        $response = $this->get(route('signup'));

        $response->assertOk();
        $response->assertViewIs('auth.signup');
    }

    /**
     * Test happy path: user can successfully register.
     */
    public function test_user_can_register_with_valid_details(): void
    {
        $payload = [
            'fname' => 'Jane',
            'lname' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '+1-555-0123',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ];

        $response = $this->post(route('sign'), $payload);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Account created! Please login.');

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'fname' => 'Jane',
            'lname' => 'Doe',
            'phone' => '+1-555-0123',
        ]);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));
    }

    /**
     * Test registration validation: uniqueness and required fields.
     */
    public function test_registration_validates_required_fields_and_uniqueness(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
            'phone' => '+1-555-0000',
        ]);

        $response = $this->post(route('sign'), [
            'fname' => '',
            'lname' => '',
            'email' => 'existing@example.com',
            'phone' => '+1-555-0000',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors([
            'fname',
            'lname',
            'email',
            'phone',
            'password',
        ]);
    }

    /**
     * Test user can view login form.
     */
    public function test_user_can_view_login_form(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    /**
     * Test happy path: user can log in with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'SecretPassword123!',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'SecretPassword123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'SecretPassword123!',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'john@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test happy path: authenticated user can log out.
     */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'You have been logged out.');
        $this->assertGuest();
    }
}
