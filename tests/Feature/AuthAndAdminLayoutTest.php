<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthAndAdminLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test guest redirection to login.
     */
    public function test_guests_are_redirected_to_login_page(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));

        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertRedirect(route('login'));
    }

    /**
     * Test login page renders successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('SORASA');
        $response->assertSee('owner@sorasa.test');
    }

    /**
     * Test successful login via Livewire component.
     */
    public function test_user_can_authenticate_via_livewire_login(): void
    {
        Livewire::test(Login::class)
            ->set('email', 'owner@sorasa.test')
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();
    }

    /**
     * Test failed login with invalid credentials.
     */
    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        Livewire::test(Login::class)
            ->set('email', 'owner@sorasa.test')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    /**
     * Test authenticated user can access admin dashboard and see active Open PO.
     */
    public function test_authenticated_user_can_access_dashboard_and_see_layout_elements(): void
    {
        $owner = User::where('email', 'owner@sorasa.test')->first();

        $response = $this->actingAs($owner)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Operasional');
        $response->assertSee('PO Batch #14 - Mid November Special');
        $response->assertSee('Total Omzet');
        $response->assertSee('Surplus Usaha');
    }

    /**
     * Test all admin navigation routes render with HTTP 200 for authenticated user.
     */
    public function test_all_admin_module_routes_are_accessible(): void
    {
        $owner = User::where('email', 'owner@sorasa.test')->first();

        $routes = [
            'admin.dashboard',
            'admin.open-po',
            'admin.sales.pos',
            'admin.sales.index',
            'admin.deliveries',
            'admin.payments',
            'admin.purchases',
            'admin.expenses',
            'admin.products',
            'admin.recipes',
            'admin.customers',
            'admin.reports',
            'admin.settings',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($owner)->get(route($route));
            $response->assertStatus(200, "Route {$route} failed with status {$response->status()}");
        }
    }

    /**
     * Test user can logout successfully.
     */
    public function test_user_can_logout(): void
    {
        $owner = User::where('email', 'owner@sorasa.test')->first();

        $response = $this->actingAs($owner)->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
