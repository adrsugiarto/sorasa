<?php

namespace Tests\Feature;

use App\Livewire\Admin\Settings;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->owner = User::where('email', 'owner@sorasa.test')->first();
    }

    /**
     * Test settings page is accessible and displays seeded profile & bank.
     */
    public function test_settings_page_renders_with_profile_and_bank(): void
    {
        $response = $this->actingAs($this->owner)->get(route('admin.settings'));
        $response->assertStatus(200);
        $response->assertSee('Profil & Pengaturan Usaha', false);
        $response->assertSee('Identitas Bisnis & Dapur', false);
        $response->assertSee('Informasi Rekening Pembayaran (Nota)', false);
        $response->assertSee('SORASA Kitchen & Pastry', false);
        $response->assertSee('BCA (Bank Central Asia)', false);
        $response->assertSee('8420198273', false);
    }

    /**
     * Test updating business profile persists to settings table.
     */
    public function test_user_can_update_business_profile(): void
    {
        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->set('store_name', 'SORASA Resto & Catering')
            ->set('store_tagline', 'Katering Harian & PO Nusantara')
            ->set('store_phone', '081999888777')
            ->set('store_address', 'Jl. R.E. Martadinata No. 100, Bandung')
            ->set('store_email', 'catering@sorasa.test')
            ->set('kitchen_notes', 'Order minimal H-2 sebelum kirim')
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertSame('SORASA Resto & Catering', Setting::get('store_name'));
        $this->assertSame('Katering Harian & PO Nusantara', Setting::get('store_tagline'));
        $this->assertSame('081999888777', Setting::get('store_phone'));
        $this->assertSame('Jl. R.E. Martadinata No. 100, Bandung', Setting::get('store_address'));
        $this->assertSame('catering@sorasa.test', Setting::get('store_email'));
        $this->assertSame('Order minimal H-2 sebelum kirim', Setting::get('kitchen_notes'));
    }

    /**
     * Test profile validation requires mandatory fields.
     */
    public function test_profile_update_validates_required_fields(): void
    {
        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->set('store_name', '')
            ->set('store_phone', '')
            ->set('store_address', '')
            ->call('updateProfile')
            ->assertHasErrors(['store_name', 'store_phone', 'store_address']);
    }

    /**
     * Test adding a new bank account.
     */
    public function test_user_can_add_new_bank_account(): void
    {
        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->call('openCreateBankModal')
            ->assertSet('showBankModal', true)
            ->set('bank_name', 'Bank Mandiri')
            ->set('account_number', '1370019283746')
            ->set('account_name', 'SORASA CATERING NUSANTARA')
            ->set('is_primary', false)
            ->call('saveBankAccount')
            ->assertHasNoErrors()
            ->assertSet('showBankModal', false);

        $accounts = Setting::getBankAccounts();
        $this->assertCount(2, $accounts);

        $mandiri = collect($accounts)->firstWhere('bank_name', 'Bank Mandiri');
        $this->assertNotNull($mandiri);
        $this->assertSame('1370019283746', $mandiri['account_number']);
        $this->assertSame('SORASA CATERING NUSANTARA', $mandiri['account_name']);
        $this->assertFalse($mandiri['is_primary']);
    }

    /**
     * Test editing an existing bank account.
     */
    public function test_user_can_edit_existing_bank_account(): void
    {
        $accounts = Setting::getBankAccounts();
        $firstId = $accounts[0]['id'];

        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->call('openEditBankModal', $firstId)
            ->assertSet('showBankModal', true)
            ->assertSet('bank_name', 'BCA (Bank Central Asia)')
            ->set('account_number', '8420999999')
            ->set('account_name', 'SORASA UTAMA')
            ->call('saveBankAccount')
            ->assertHasNoErrors()
            ->assertSet('showBankModal', false);

        $updatedAccounts = Setting::getBankAccounts();
        $updatedFirst = collect($updatedAccounts)->firstWhere('id', $firstId);

        $this->assertSame('8420999999', $updatedFirst['account_number']);
        $this->assertSame('SORASA UTAMA', $updatedFirst['account_name']);
    }

    /**
     * Test changing primary bank account.
     */
    public function test_user_can_set_bank_account_as_primary(): void
    {
        // Add second account
        Setting::addBankAccount([
            'id' => 'bank-mandiri-test',
            'bank_name' => 'Bank Mandiri',
            'account_number' => '137000111222',
            'account_name' => 'SORASA KULINER',
            'is_primary' => false,
        ]);

        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->call('setPrimaryBank', 'bank-mandiri-test')
            ->assertHasNoErrors();

        $accounts = Setting::getBankAccounts();
        $mandiri = collect($accounts)->firstWhere('id', 'bank-mandiri-test');
        $this->assertTrue($mandiri['is_primary']);

        // Check legacy keys are synced with new primary
        $this->assertSame('Bank Mandiri', Setting::get('bank_name'));
        $this->assertSame('137000111222', Setting::get('bank_account_number'));
    }

    /**
     * Test deleting a bank account.
     */
    public function test_user_can_delete_bank_account(): void
    {
        Setting::addBankAccount([
            'id' => 'bank-to-delete',
            'bank_name' => 'Bank BNI',
            'account_number' => '0987654321',
            'account_name' => 'SORASA BNI',
            'is_primary' => false,
        ]);

        $this->assertCount(2, Setting::getBankAccounts());

        Livewire::actingAs($this->owner)
            ->test(Settings::class)
            ->call('confirmDeleteBank', 'bank-to-delete')
            ->assertSet('showDeleteBankModal', true)
            ->call('deleteBank')
            ->assertSet('showDeleteBankModal', false)
            ->assertHasNoErrors();

        $accounts = Setting::getBankAccounts();
        $this->assertCount(1, $accounts);
        $this->assertNull(collect($accounts)->firstWhere('id', 'bank-to-delete'));
    }
}
