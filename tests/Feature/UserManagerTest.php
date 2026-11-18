<?php

namespace Tests\Feature;

use App\Livewire\Admin\UserManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'name' => 'Owner Utama',
            'email' => 'owner@sorasa.test',
            'role' => 'owner',
            'password' => Hash::make('password123'),
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin Dapur',
            'email' => 'admin@sorasa.test',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_owner_can_access_user_manager(): void
    {
        $this->actingAs($this->owner)
            ->get(route('admin.users'))
            ->assertStatus(200)
            ->assertSee('Kelola Akun Admin')
            ->assertSee('owner@sorasa.test')
            ->assertSee('admin@sorasa.test');
    }

    public function test_non_owner_is_forbidden_from_user_manager(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.users'))
            ->assertStatus(403);
    }

    public function test_owner_can_create_new_admin_user(): void
    {
        Livewire::actingAs($this->owner)
            ->test(UserManager::class)
            ->call('openCreateModal')
            ->set('name', 'Admin Kasir Baru')
            ->set('email', 'kasir@sorasa.test')
            ->set('role', 'admin')
            ->set('password', 'secret123')
            ->set('password_confirmation', 'secret123')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false);

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Kasir Baru',
            'email' => 'kasir@sorasa.test',
            'role' => 'admin',
        ]);

        $newUser = User::where('email', 'kasir@sorasa.test')->first();
        $this->assertTrue(Hash::check('secret123', $newUser->password));
    }

    public function test_owner_can_update_admin_and_change_password(): void
    {
        Livewire::actingAs($this->owner)
            ->test(UserManager::class)
            ->call('openEditModal', $this->admin->id)
            ->set('name', 'Admin Dapur Updated')
            ->set('password', 'newsecret456')
            ->set('password_confirmation', 'newsecret456')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Admin Dapur Updated', $this->admin->fresh()->name);
        $this->assertTrue(Hash::check('newsecret456', $this->admin->fresh()->password));
    }

    public function test_owner_cannot_delete_themselves(): void
    {
        Livewire::actingAs($this->owner)
            ->test(UserManager::class)
            ->call('delete', $this->owner->id);

        $this->assertDatabaseHas('users', [
            'id' => $this->owner->id,
        ]);
    }

    public function test_owner_can_delete_admin_account(): void
    {
        Livewire::actingAs($this->owner)
            ->test(UserManager::class)
            ->call('delete', $this->admin->id);

        $this->assertDatabaseMissing('users', [
            'id' => $this->admin->id,
        ]);
    }
}
