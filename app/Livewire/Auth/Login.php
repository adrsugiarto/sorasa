<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Masuk — SORASA')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    /**
     * Validation rules for login.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Fill quick credentials for demo / testing.
     */
    public function fillCredentials(string $role): void
    {
        if ($role === 'owner') {
            $this->email = 'owner@sorasa.test';
            $this->password = 'password';
        } elseif ($role === 'admin') {
            $this->email = 'admin@sorasa.test';
            $this->password = 'password';
        }
    }

    /**
     * Authenticate user and redirect to dashboard.
     */
    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('Email atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        session()->regenerate();

        $this->redirectIntended(route('admin.dashboard'));
    }

    /**
     * Render the login view.
     */
    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
