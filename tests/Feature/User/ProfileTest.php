<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('profile.show'));

        $response->assertOk();
        $response->assertViewIs('pages.user.profile');
        $response->assertViewHas('profile');
    }

    public function test_guest_cannot_view_profile(): void
    {
        $response = $this->get(route('profile.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_edit_profile_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('profile.edit'));

        $response->assertOk();
        $response->assertViewIs('pages.user.edit-profile');
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
                'phone' => '+1234567890',
                'bio' => 'Software developer',
                'date_of_birth' => '1990-01-01',
                'gender' => 'female',
            ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertEquals('Jane Doe', $this->user->name);
        $this->assertEquals('jane@example.com', $this->user->email);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $this->user->id,
            'phone' => '+1234567890',
            'bio' => 'Software developer',
        ]);
    }

    public function test_profile_update_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'name' => '',
                'email' => '',
            ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_profile_update_validates_email_format(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'name' => 'John Doe',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_profile_update_validates_unique_email(): void
    {
        $otherUser = User::factory()->create(['email' => 'other@example.com']);

        $response = $this->actingAs($this->user)
            ->put(route('profile.update'), [
                'name' => 'John Doe',
                'email' => 'other@example.com',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_view_change_password_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('profile.password.edit'));

        $response->assertOk();
        $response->assertViewIs('pages.user.change-password');
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.password.update'), [
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
    }

    public function test_change_password_requires_correct_current_password(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.password.update'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_change_password_requires_confirmation(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.password.update'), [
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'differentpassword',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_change_password_validates_minimum_length(): void
    {
        $response = $this->actingAs($this->user)
            ->put(route('profile.password.update'), [
                'current_password' => 'password',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        $response->assertSessionHasErrors('password');
    }
}
