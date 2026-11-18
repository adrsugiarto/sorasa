<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Retrieve a setting value by key.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::query()->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set or update a setting value by key.
     */
    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Retrieve the list of bank accounts.
     *
     * @return array<int, array{id: string, bank_name: string, account_number: string, account_name: string, is_primary: bool}>
     */
    public static function getBankAccounts(): array
    {
        $json = static::get('bank_accounts');
        if ($json) {
            $decoded = json_decode($json, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return array_values($decoded);
            }
        }

        // Fallback to legacy single-account keys
        $bankName = static::get('bank_name');
        $accountNumber = static::get('bank_account_number');
        $accountName = static::get('bank_account_name');

        if ($bankName || $accountNumber) {
            return [
                [
                    'id' => 'bank-default',
                    'bank_name' => $bankName ?: 'BCA (Bank Central Asia)',
                    'account_number' => $accountNumber ?: '8420198273',
                    'account_name' => $accountName ?: 'SORASA KULINER NUSANTARA',
                    'is_primary' => true,
                ],
            ];
        }

        return [];
    }

    /**
     * Save bank accounts list to setting and keep legacy single bank keys in sync.
     *
     * @param  array<int, array{id?: string, bank_name: string, account_number: string, account_name: string, is_primary?: bool}>  $accounts
     */
    public static function setBankAccounts(array $accounts): void
    {
        $normalized = [];
        $hasPrimary = false;

        foreach (array_values($accounts) as $acc) {
            $isPrimary = (bool) ($acc['is_primary'] ?? false);
            if ($isPrimary && ! $hasPrimary) {
                $hasPrimary = true;
            } elseif ($isPrimary && $hasPrimary) {
                $isPrimary = false;
            }

            $normalized[] = [
                'id' => ! empty($acc['id']) ? (string) $acc['id'] : 'bank-'.uniqid(),
                'bank_name' => trim((string) ($acc['bank_name'] ?? '')),
                'account_number' => trim((string) ($acc['account_number'] ?? '')),
                'account_name' => trim((string) ($acc['account_name'] ?? '')),
                'is_primary' => $isPrimary,
            ];
        }

        if (! $hasPrimary && count($normalized) > 0) {
            $normalized[0]['is_primary'] = true;
        }

        static::set('bank_accounts', json_encode($normalized));

        $primary = collect($normalized)->firstWhere('is_primary', true) ?? ($normalized[0] ?? null);
        if ($primary) {
            static::set('bank_name', $primary['bank_name']);
            static::set('bank_account_number', $primary['account_number']);
            static::set('bank_account_name', $primary['account_name']);
        }
    }

    /**
     * Add a new bank account.
     *
     * @param  array{id?: string, bank_name: string, account_number: string, account_name: string, is_primary?: bool}  $data
     */
    public static function addBankAccount(array $data): void
    {
        $accounts = static::getBankAccounts();
        $accounts[] = $data;
        static::setBankAccounts($accounts);
    }

    /**
     * Update an existing bank account by id.
     *
     * @param  array{bank_name?: string, account_number?: string, account_name?: string, is_primary?: bool}  $data
     */
    public static function updateBankAccount(string $id, array $data): void
    {
        $accounts = static::getBankAccounts();
        foreach ($accounts as &$account) {
            if ($account['id'] === $id) {
                $account = array_merge($account, $data);
                break;
            }
        }
        static::setBankAccounts($accounts);
    }

    /**
     * Delete a bank account by id.
     */
    public static function deleteBankAccount(string $id): void
    {
        $accounts = static::getBankAccounts();
        $accounts = array_filter($accounts, fn ($acc) => $acc['id'] !== $id);
        static::setBankAccounts(array_values($accounts));
    }

    /**
     * Set a bank account as primary.
     */
    public static function setPrimaryBankAccount(string $id): void
    {
        $accounts = static::getBankAccounts();
        foreach ($accounts as &$account) {
            $account['is_primary'] = ($account['id'] === $id);
        }
        static::setBankAccounts($accounts);
    }
}
