<?php
//C:\laragon\www\PichangaYa\pichangaya\app\Http\Controllers\VerificationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * 1. ENVIAR CÓDIGO
     */
    public function sendCode(Request $request)
    {
        $user = Auth::user();
        
        // Generamos el código para cualquiera de los dos casos
        $code = rand(100000, 999999);
        $user->verification_code = $code;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);

        // --- CASO A: CORREO ---
        if ($request->channel === 'email') {
            // Guardamos el código en la BD antes de enviar
            $user->save(); 

            try {
                Mail::to($user->email)->send(new VerificationCodeMail($code));
                return response()->json(['message' => 'Código enviado a tu correo.']);
            } catch (\Exception $e) {
                // Si falla el envío, limpiamos el código
                $user->verification_code = null;
                $user->save();
                return response()->json(['message' => 'Error SMTP: ' . $e->getMessage()], 500);
            }
        }

        // --- CASO B: CELULAR (SMS) ---
        else {
            // Solo validamos el teléfono si estamos en el canal SMS
            $request->validate([
                'phone' => 'required|string|min:9|max:15'
            ]);

            // Actualizamos el número si lo cambiaron
            $user->phone = $request->phone;
            $user->save(); // Guardamos teléfono y código

            // Simulación en Log
            Log::info("📱 [SIMULACIÓN SMS] Para el número {$user->phone}: Tu código es {$code}");

            return response()->json([
                'message' => 'Código enviado (Simulado en Log).',
            ]);
        }
    }

    /**
     * 2. VERIFICAR CÓDIGO
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
            'channel' => 'nullable|string' // Necesitamos saber qué estamos verificando
        ]);

        $user = Auth::user();

        // 1. Validar que el código sea correcto
        if ($user->verification_code != $request->code) {
            return response()->json(['error' => 'El código es incorrecto.'], 422);
        }

        // 2. Validar que no haya expirado
        if (Carbon::now()->greaterThan($user->verification_code_expires_at)) {
            return response()->json(['error' => 'El código ha expirado. Pide uno nuevo.'], 422);
        }

        // 3. VERIFICAR SEGÚN EL CANAL
        if ($request->channel === 'email') {
            // Verificamos Correo
            $user->email_verified_at = now();
            // Aseguramos que ForceFill guarde el cambio de fecha
            $user->forceFill(['email_verified_at' => now()])->save();
        } else {
            // Verificamos Celular
            $user->phone_verified_at = now();
            $user->save();
        }
        
        // 4. Limpiar código usado
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return response()->json(['message' => '¡Verificación exitosa!']);
    }
}