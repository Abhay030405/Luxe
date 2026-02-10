<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_view_addresses_list(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('addresses.index'));

        $response->assertOk();
        $response->assertViewIs('pages.user.addresses.index');
    }

    public function test_guest_cannot_view_addresses(): void
    {
        $response = $this->get(route('addresses.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_create_address_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('addresses.create'));

        $response->assertOk();
        $response->assertViewIs('pages.user.addresses.create');
    }

    public function test_authenticated_user_can_create_address(): void
    {
        $addressData = [
            'full_name' => 'John Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'address_line_2' => 'Apt 4B',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'address_type' => 'home',
            'is_default' => false,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('addresses.store'), $addressData);

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'full_name' => 'John Doe',
            'phone' => '+1234567890',
            'city' => 'New York',
        ]);
    }

    public function test_first_address_is_automatically_set_as_default(): void
    {
        $addressData = [
            'full_name' => 'John Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'address_type' => 'home',
            'is_default' => false,
        ];

        $this->actingAs($this->user)
            ->post(route('addresses.store'), $addressData);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);
    }

    public function test_address_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('addresses.store'), []);

        $response->assertSessionHasErrors([
            'full_name',
            'phone',
            'address_line_1',
            'city',
            'state',
            'postal_code',
            'country',
            'address_type',
        ]);
    }

    public function test_authenticated_user_can_view_edit_address_form(): void
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get(route('addresses.edit', $address->id));

        $response->assertOk();
        $response->assertViewIs('pages.user.addresses.edit');
    }

    public function test_authenticated_user_can_update_their_address(): void
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->put(route('addresses.update', $address->id), [
                'full_name' => 'Updated Name',
                'phone' => '+9876543210',
                'address_line_1' => '456 Oak Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'USA',
                'address_type' => 'work',
                'is_default' => false,
            ]);

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'full_name' => 'Updated Name',
            'city' => 'Los Angeles',
        ]);
    }

    public function test_user_cannot_update_another_users_address(): void
    {
        $otherUser = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($this->user)
            ->put(route('addresses.update', $address->id), [
                'full_name' => 'Hacker',
                'phone' => '+1234567890',
                'address_line_1' => '123 Hack St',
                'city' => 'Hackville',
                'state' => 'HK',
                'postal_code' => '12345',
                'country' => 'USA',
                'address_type' => 'home',
            ]);

        // Should fail with validation error or not found
        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
            'full_name' => 'Hacker',
        ]);
    }

    public function test_authenticated_user_can_delete_their_address(): void
    {
        Address::factory()->create(['user_id' => $this->user->id, 'is_default' => true]);
        $address = Address::factory()->create(['user_id' => $this->user->id, 'is_default' => false]);

        $response = $this->actingAs($this->user)
            ->delete(route('addresses.destroy', $address->id));

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_cannot_delete_only_address(): void
    {
        $address = Address::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('addresses.destroy', $address->id));

        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
    }

    public function test_can_set_address_as_default(): void
    {
        $address1 = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);

        $address2 = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('addresses.set-default', $address2->id));

        $response->assertRedirect(route('addresses.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'id' => $address2->id,
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $address1->id,
            'is_default' => false,
        ]);
    }

    public function test_only_one_address_can_be_default_at_a_time(): void
    {
        Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);

        $addressData = [
            'full_name' => 'John Doe',
            'phone' => '+1234567890',
            'address_line_1' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'USA',
            'address_type' => 'home',
            'is_default' => true,
        ];

        $this->actingAs($this->user)
            ->post(route('addresses.store'), $addressData);

        $defaultCount = Address::where('user_id', $this->user->id)
            ->where('is_default', true)
            ->count();

        $this->assertEquals(1, $defaultCount);
    }
}
