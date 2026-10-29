<?php

namespace App\Http\Middleware;

use App\Models\MobileApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMobileToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if (!$plainToken) {
            return response()->json(['message' => 'ავტორიზაცია საჭიროა.'], 401);
        }

        $token = MobileApiToken::query()
            ->where('token_hash', hash('sha256', $plainToken))
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->with('user')->first();

        if (!$token || !$token->user) {
            return response()->json(['message' => 'სესია ვადაგასულია. ხელახლა შედი ანგარიშში.'], 401);
        }

        $token->forceFill(['last_used_at' => now()])->save();
        $request->setUserResolver(fn () => $token->user);

        return $next($request);
    }
}
