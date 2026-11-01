<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\RewardsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(Request $request): View
    {
        return view('auth.register', ['referralCode' => $request->session()->get('referral_code')]);
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function register(Request $request, RewardsService $rewards)
    {
        $request->merge(['referral_code' => strtoupper(trim((string) ($request->input('referral_code') ?: $request->session()->get('referral_code', ''))))]);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'referral_code' => ['nullable', 'string', 'min:4', 'max:20', 'alpha_num', 'exists:users,referral_code'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
'phone' => [
    'nullable',
    'string',
    'max:30',
    'regex:/^[0-9+\-\s()]+$/',
],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
	    'phone' => $validated['phone'] ?? null,	
            'password' => $validated['password'],
        ]);

        $rewards->onRegistration($user, $validated['referral_code'] ?? $request->session()->get('referral_code'));
        $request->session()->forget('referral_code');
        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('home')
            ->with('success', 'რეგისტრაცია წარმატებულია!');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'ელფოსტა ან პაროლი არასწორია.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'ანგარიშიდან წარმატებით გამოხვედი.');
    }
}