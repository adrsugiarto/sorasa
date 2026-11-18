<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Profil & Pengaturan Usaha — SORASA')]
class Settings extends Component
{
    // Business profile form fields
    public string $store_name = '';

    public string $store_tagline = '';

    public string $store_address = '';

    public string $store_phone = '';

    public string $store_email = '';

    public string $kitchen_notes = '';

    // Bank accounts list
    /**
     * @var array<int, array{id: string, bank_name: string, account_number: string, account_name: string, is_primary: bool}>
     */
    public array $bankAccounts = [];

    // Bank Modal state
    public bool $showBankModal = false;

    public bool $isEditingBank = false;

    public ?string $editingBankId = null;

    public string $bank_name = '';

    public string $account_number = '';

    public string $account_name = '';

    public bool $is_primary = false;

    // Delete Bank Modal state
    public bool $showDeleteBankModal = false;

    public ?string $deletingBankId = null;

    public string $deletingBankName = '';

    /**
     * Mount component and populate settings.
     */
    public function mount(): void
    {
        $this->loadSettings();
    }

    /**
     * Load settings from database.
     */
    public function loadSettings(): void
    {
        $this->store_name = Setting::get('store_name', 'SORASA Kitchen & Pastry') ?? '';
        $this->store_tagline = Setting::get('store_tagline', 'Spesialis Pre-Order Masakan Nusantara & Dessert Homemade') ?? '';
        $this->store_address = Setting::get('store_address', 'Jl. Cendrawasih No. 24, Sukajadi, Kota Bandung, Jawa Barat 40162') ?? '';
        $this->store_phone = Setting::get('store_phone', '081223456789') ?? '';
        $this->store_email = Setting::get('store_email', 'halo@sorasa.test') ?? '';
        $this->kitchen_notes = Setting::get('kitchen_notes', 'Harap konfirmasi jadwal kirim H-1 sebelum tanggal pengiriman PO.') ?? '';

        $this->bankAccounts = Setting::getBankAccounts();
    }

    /**
     * Update business identity and profile.
     */
    public function updateProfile(): void
    {
        $validated = $this->validate([
            'store_name' => ['required', 'string', 'max:100'],
            'store_tagline' => ['nullable', 'string', 'max:255'],
            'store_address' => ['required', 'string', 'max:500'],
            'store_phone' => ['required', 'string', 'max:30'],
            'store_email' => ['nullable', 'email', 'max:100'],
            'kitchen_notes' => ['nullable', 'string', 'max:500'],
        ], [
            'store_name.required' => 'Nama usaha wajib diisi.',
            'store_address.required' => 'Alamat dapur wajib diisi.',
            'store_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'store_email.email' => 'Format email usaha tidak valid.',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) $value);
        }

        session()->flash('profile_success', 'Identitas bisnis & dapur berhasil disimpan!');
    }

    /**
     * Open modal to create a new bank account.
     */
    public function openCreateBankModal(): void
    {
        $this->resetValidation();
        $this->isEditingBank = false;
        $this->editingBankId = null;
        $this->bank_name = '';
        $this->account_number = '';
        $this->account_name = $this->store_name ?: 'SORASA KULINER NUSANTARA';
        $this->is_primary = count($this->bankAccounts) === 0;
        $this->showBankModal = true;
    }

    /**
     * Quick preset for popular banks in Indonesia.
     */
    public function selectBankPreset(string $bankName): void
    {
        $this->bank_name = $bankName;
    }

    /**
     * Open modal to edit existing bank account.
     */
    public function openEditBankModal(string $id): void
    {
        $this->resetValidation();
        $account = collect($this->bankAccounts)->firstWhere('id', $id);

        if (! $account) {
            return;
        }

        $this->isEditingBank = true;
        $this->editingBankId = $id;
        $this->bank_name = $account['bank_name'] ?? '';
        $this->account_number = $account['account_number'] ?? '';
        $this->account_name = $account['account_name'] ?? '';
        $this->is_primary = (bool) ($account['is_primary'] ?? false);
        $this->showBankModal = true;
    }

    /**
     * Save new or edited bank account.
     */
    public function saveBankAccount(): void
    {
        $this->validate([
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:150'],
        ], [
            'bank_name.required' => 'Nama bank atau penyedia e-wallet wajib diisi.',
            'account_number.required' => 'Nomor rekening atau nomor akun wajib diisi.',
            'account_name.required' => 'Atas nama rekening wajib diisi.',
        ]);

        $accountData = [
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'is_primary' => $this->is_primary,
        ];

        if ($this->isEditingBank && $this->editingBankId) {
            Setting::updateBankAccount($this->editingBankId, $accountData);
            session()->flash('bank_success', "Rekening {$this->bank_name} berhasil diperbarui!");
        } else {
            Setting::addBankAccount($accountData);
            session()->flash('bank_success', "Rekening baru ({$this->bank_name}) berhasil ditambahkan!");
        }

        $this->bankAccounts = Setting::getBankAccounts();
        $this->showBankModal = false;
    }

    /**
     * Set a bank account as the primary account.
     */
    public function setPrimaryBank(string $id): void
    {
        Setting::setPrimaryBankAccount($id);
        $this->bankAccounts = Setting::getBankAccounts();
        session()->flash('bank_success', 'Rekening utama berhasil diubah!');
    }

    /**
     * Confirm bank account deletion.
     */
    public function confirmDeleteBank(string $id): void
    {
        $account = collect($this->bankAccounts)->firstWhere('id', $id);

        if (! $account) {
            return;
        }

        $this->deletingBankId = $id;
        $this->deletingBankName = "{$account['bank_name']} ({$account['account_number']})";
        $this->showDeleteBankModal = true;
    }

    /**
     * Delete the confirmed bank account.
     */
    public function deleteBank(): void
    {
        if ($this->deletingBankId) {
            Setting::deleteBankAccount($this->deletingBankId);
            $this->bankAccounts = Setting::getBankAccounts();
            session()->flash('bank_success', 'Rekening berhasil dihapus.');
        }

        $this->showDeleteBankModal = false;
        $this->deletingBankId = null;
    }

    public function render(): View
    {
        return view('livewire.admin.settings');
    }
}
