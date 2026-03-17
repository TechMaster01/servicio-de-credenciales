<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class FuncionesController extends Controller
{
    /**
     * Encriptar una credencial específica
     */
    public function encrypt(Request $request)
    {
        $request->validate([
            'data' => 'required|string'
        ]);

        try {
            $data = $request->input('data');
            $encrypted = base64_encode($data);
            
            return response()->json([
                'message' => 'Datos encriptados exitosamente.',
                'encrypted_data' => $encrypted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al encriptar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desencriptar datos
     */
    public function decrypt(Request $request)
    {
        $request->validate([
            'data' => 'required|string'
        ]);

        try {
            $data = trim($request->input('data'));

            $result = null;

            try {
                // 1. Obtener la llave específica de credenciales y decodificarla
                $key = env('CREDENTIALS_ENCRYPTION_KEY');
                if (strpos($key, 'base64:') === 0) {
                    $key = base64_decode(substr($key, 7));
                }

                // 2. Crear un encriptador con esta llave específica
                $encrypter = new \Illuminate\Encryption\Encrypter($key, config('app.cipher', 'AES-256-CBC'));
                
                // 3. Desencriptar
                $result = $encrypter->decryptString($data);
                
            } catch (\Exception $e) {
                // Si falla, intentarlo por si es un base64 simple que no está encriptado con AES
                $decoded = base64_decode($data, true);
                if ($decoded !== false && base64_encode($decoded) === $data) {
                    $result = $decoded;
                } else {
                    return response()->json([
                        'message' => 'No se pudo desencriptar: datos incorrectos o llave inválida.',
                        'error' => $e->getMessage()
                    ], 400);
                }
            }

            return response()->json([
                'message' => 'Datos desencriptados exitosamente.',
                'decrypted_data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor al desencriptar: ' . $e->getMessage()
            ], 500);
        }
    }
}
