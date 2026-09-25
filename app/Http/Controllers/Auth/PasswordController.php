<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        try {
            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);
        }catch (\Throwable $e) {
            Log::error('Error al actualizar la contraseña del usuario.', [
                'user_id' => $request->user()->id,
                'exception' => $e,
            ]);

            return back()->with(
                'error',
                'No se pudo actualizar la contraseña. Inténtalo nuevamente.'
            );
        }

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}
