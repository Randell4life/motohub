<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MobileApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->merge([
            'email' => mb_strtolower(trim((string) $request->input('email', ''))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (User::query()->whereRaw('LOWER(email) = ?', [$data['email']])->exists()) {
            return response()->json([
                'message' => 'ამ ელფოსტით ანგარიში უკვე არსებობს.',
                'errors' => ['email' => ['ამ ელფოსტით ანგარიში უკვე არსებობს.']],
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
        ]);
        return response()->json($this->issue($user), 201);
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => mb_strtolower(trim((string) $request->input('email', ''))),
        ]);

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'ელფოსტა ან პაროლი არასწორია.'], 422);
        }
        return response()->json($this->issue($user));
    }

    public function logout(Request $request)
    {
        if ($plain = $request->bearerToken()) {
            MobileApiToken::where('token_hash', hash('sha256', $plain))->delete();
        }
        return response()->json(['message' => 'ანგარიშიდან წარმატებით გამოხვედი.']);
    }

    private function issue(User $user): array
    {
        $plain = Str::random(64);
        $expires = now()->addDays(30);
        MobileApiToken::create([
            'user_id' => $user->id,
            'name' => 'MotoHub Mobile',
            'token_hash' => hash('sha256', $plain),
            'expires_at' => $expires,
        ]);
        return [
            'token' => $plain,
            'token_type' => 'Bearer',
            'expires_at' => $expires->toIso8601String(),
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'is_admin' => (bool) $user->is_admin],
        ];
    }
}
