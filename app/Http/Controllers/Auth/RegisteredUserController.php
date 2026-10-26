<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_num'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:admin,logistik,pengadaan,unit'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Tetapkan role berdasarkan pilihan di form registrasi
        $selectedRole = $request->input('role');
        if ($selectedRole && in_array($selectedRole, ['admin', 'logistik', 'pengadaan', 'unit'])) {
            $user->assignRole($selectedRole);
        } elseif ($request->name === 'admin' || str_contains(strtolower($request->name), 'admin')) {
            $user->assignRole('admin');
        } elseif ($request->name === 'logistik') {
            $user->assignRole('logistik');
        } elseif ($request->name === 'pengadaan') {
            $user->assignRole('pengadaan');
        } else {
            $user->assignRole('admin'); // Jadikan Super Admin secara default
        }

        event(new Registered($user));

        // Auth::login($user);

        return redirect(RouteServiceProvider::HOME)
            ->with('success', 'Registrasi user berhasil!');
    }
}
