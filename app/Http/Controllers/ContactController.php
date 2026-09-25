<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {

            Mail::to('imperiumbarberiia@gmail.com')
                ->send(new ContactMessage(
                    $validated['name'],
                    $validated['email'],
                    $validated['message'],
                ));

                return back()->with(
                    'success',
                    'Tu mensaje fue enviado correctamente. Gracias por contactarnos!'
                );
        } catch (\Throwable $e) {
            Log::error('Error al enviar mensaje de contacto.',  [
                'email' => $validated['email'],
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with(
                'error',
                'No se pudo enviar el mensaje. Inténtalo nuevamente.'
            );
        }
    }
}
