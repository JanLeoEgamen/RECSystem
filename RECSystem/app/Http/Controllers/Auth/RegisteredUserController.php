<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u', // Allows letters, spaces, and hyphens
                function ($attribute, $value, $fail) use ($request) {
                    // Check for unique combination of first and last name
                    $exists = User::where('first_name', $value)
                                ->where('last_name', $request->last_name)
                                ->exists();
                    if ($exists) {
                        $fail('This name combination already exists in our system.');
                    }
                }
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u'
            ],
            'birthdate' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'), // Must be 18+ years old
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d') // Reasonable minimum age
            ],
            // 'email' => [
            //     'required',
            //     'string',
            //     'email',
            //     'max:255',
            //     'unique:users',
            //     function ($attribute, $value, $fail) {
            //         // Block disposable emails
            //         $disposableDomains = ['mailinator.com', 'tempmail.com', 'example.com'];
            //         $domain = substr(strrchr($value, "@"), 1);
            //         if (in_array($domain, $disposableDomains)) {
            //             $fail('Disposable email addresses are not allowed.');
            //         }
            //     }
            // ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
                    ->mixedCase() // Requires at least one uppercase and one lowercase letter
                    ->numbers()   // Requires at least one number
                    ->symbols()   // Requires at least one special character
                    ->min(8)      // Minimum 8 characters
            ],
        ], [
            'first_name.regex' => 'First name may only contain letters, spaces and hyphens',
            'last_name.regex' => 'Last name may only contain letters, spaces and hyphens',
            'birthdate.before_or_equal' => 'You must be at least 18 years old to register',
            'birthdate.after_or_equal' => 'Please enter a valid birthdate',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
