<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'encrypted_data' => 'required|string'
        ]);

        try {
            $encryptedData = $request->input('encrypted_data');
            $decrypted = base64_decode($encryptedData);
            
            return response()->json([
                'message' => 'Datos desencriptados exitosamente.',
                'decrypted_data' => $decrypted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al desencriptar: ' . $e->getMessage()
            ], 500);
        }
    }
}
