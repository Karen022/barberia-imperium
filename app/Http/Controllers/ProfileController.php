<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function index(Request $request)
    {
        $user = $request->user();

        return view('profile.index', compact('user'));
    }
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Actualizar los campos validados
        $user->fill($request->validated());

        // Si se cambió el email, marcarlo como no verificado
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        try {
            // Manejo de la imagen de perfil
            if ($request->hasFile('profile_image')) {
                // Eliminar la imagen anterior si existe
                if ($user->profile_image && Storage::disk('public')->exists('profiles/' . $user->profile_image)) {
                    Storage::disk('public')->delete('profiles/' . $user->profile_image);
                }

                // Subir la nueva imagen
                $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
                $request->file('profile_image')->storeAs('profiles', $fileName, 'public');

                // Guardar el nombre del archivo en la base de datos
                $user->profile_image = $fileName;
            }

            // Guardar los cambios
            $user->save();
        } catch (\Throwable $e) {
            Log::error('Error al actualizar el perfil.', [
                'user_id' => $user->id,
                'exception' => $e,
            ]);

            return Redirect::route('profile.edit')
                ->withInput()
                ->with('error', 'No se pudo actualizar tu perfil. Inténtalo nuevamente.');
        }

        // Redirigir con éxito
        return Redirect::route('profile.index')->with('success', 'Perfil actualizado correctamente.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
