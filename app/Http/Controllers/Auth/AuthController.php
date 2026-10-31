<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        if (Auth::attempt($credentials + ['status' => 'active'], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectByRole($request->user());
        }

        return back()->withErrors([
            'email' => 'The supplied credentials are incorrect or the account is inactive.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['nullable','string','max:30'],
            'password' => ['required','confirmed',Password::min(8)],
            'account_type' => ['required','in:customer,vendor'],
            'business_name' => ['required_if:account_type,vendor','nullable','string','max:255'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'role' => $data['account_type'],
                'status' => 'active',
            ]);

            if ($data['account_type'] === 'vendor') {
                $base = Str::slug($data['business_name']);
                $slug = $base;
                $suffix = 1;

                while (Vendor::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $suffix++;
                }

                Vendor::create([
                    'user_id' => $user->id,
                    'business_name' => $data['business_name'],
                    'slug' => $slug,
                    'business_email' => $user->email,
                    'phone' => $user->phone,
                    'listing_type' => 'manual',
                    'country' => 'India',
                    'verification_status' => 'pending',
                    'status' => 'active',
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'vendor' => redirect()->route('vendor.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
