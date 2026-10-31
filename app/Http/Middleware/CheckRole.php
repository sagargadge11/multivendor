<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->status !== 'active') {
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account is inactive. Please contact the administrator.',
                ]);
        }

        if (!in_array($user->role, $roles, true)) {
            abort(403, 'You are not authorized to access this page.');
        }

        if ($user->role === 'vendor') {
            $vendor = $user->vendor;

            if (!$vendor || $vendor->status !== 'active') {
                abort(403, 'Your vendor account is suspended or unavailable. Please contact the administrator.');
            }
        }

        return $next($request);
    }
}
