<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar la vista de registro
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Manejar el registro
     */
    public function store(Request $request): RedirectResponse
    {
        // Validación simple, sin role
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Crear usuario (el cast hashed se encarga de hash)
        $user = User::create($validated);

        // Asignar rol por defecto 'client' usando Spatie
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('client');
        }

        // Disparar evento de registro
        event(new Registered($user));

        // Loguear usuario automáticamente
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
