<?php

namespace App\Livewire\Settings;

use App\Enum\Gender;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $date_of_birth = '';

    public int $gender;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->date_of_birth = Auth::user()->date_of_birth->format('Y-m-d');
        $this->gender = Auth::user()->gender->value;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],

            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d'],
            'gender' => ['required', 'integer', Rule::in(Gender::values())],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date_of_birth' => Carbon::createFromFormat("Y-m-d", $validated['date_of_birth']),
            'gender' => Gender::from($validated['gender']),
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}
