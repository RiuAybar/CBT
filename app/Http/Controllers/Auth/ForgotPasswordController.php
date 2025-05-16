<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\BienvenidaCrearPassword;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        // Generar token y guardarlo en la tabla `password_resets`
        $token = Password::broker()->createToken($user);

        $mensaje = [
            'Hemos recibido una solicitud para restablecer la contraseña.',
            'Este vínculo caduca en 60 minutos.'
        ];

        // Enviar el correo personalizado
        Mail::to($user->email)->send(new BienvenidaCrearPassword($user, $token, 'Restablezca su contraseña.',$mensaje));

        return response()->json(['message' => 'Correo de recuperación enviado con éxito.']);
    }
}
