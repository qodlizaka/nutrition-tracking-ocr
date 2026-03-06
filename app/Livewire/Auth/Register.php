<?php

namespace App\Livewire\Auth;

use App\Enum\Gender;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth', ['title' => 'Register'])]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $date_of_birth = '';

    public int $gender = Gender::Male->value;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'date_of_birth' => ['required', 'date_format:Y-m-d', 'before:today'],
            'gender' => ['required', 'integer', Rule::in(Gender::values())],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'date_of_birth' => Carbon::createFromFormat("Y-m-d", $validated['date_of_birth']),
            'gender' => Gender::from($validated['gender']),
        ]))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: false);
    }
}
