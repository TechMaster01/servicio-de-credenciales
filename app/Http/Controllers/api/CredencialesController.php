<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Credenciales;
use App\Services\CredencialesEncryptionService;
use Illuminate\Http\Request;

class CredencialesController extends Controller
{
    public function index()
    {
        try {
            $credenciales = Credenciales::all();

            if ($credenciales->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron credenciales.'
                ]);
            }

            // Descifrar todos los campos para cada credencial
            $credencialesDescifradas = $credenciales->map(function ($credencial) {
                return $credencial->getDecryptedAttributes();
            });

            return response()->json($credencialesDescifradas);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las credenciales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string',
            'des_dns' => 'required|string',
            'des_usuario' => 'required|string',
            'des_db' => 'required|string',
            'des_password' => 'required|string',
        ]);

        try {
            // Verificar si la clave ya existe (comparando descifrada)
            $claveExistente = Credenciales::findByDecryptedKey($request->clave);
            if ($claveExistente) {
                return response()->json([
                    'message' => 'Ya existe una credencial con esa clave.',
                ], 422);
            }

            // El cifrado se maneja automáticamente en el modelo
            $credencial = Credenciales::create($request->all());

            return response()->json([
                'message' => 'Credencial creada exitosamente.',
                'data' => $credencial->getDecryptedAttributes()
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la credencial.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $clave = $request->input('clave');
        
        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }

        try {
            // Buscar por clave descifrada
            $credencial = Credenciales::findByDecryptedKey($clave);

            if (!$credencial) {
                return response()->json([
                    'message' => 'Credencial no encontrada.'
                ], 404);
            }

            return response()->json([
                'meta' => [
                    'status' => 'success',
                    'message' => 'Credencial encontrada.'
                ],
                'data' => $credencial->getDecryptedAttributes()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener la credencial.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $clave = $request->input('clave');

        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }

        try {
            // Buscar por clave descifrada
            $credencial = Credenciales::findByDecryptedKey($clave);

            if (!$credencial) {
                return response()->json([
                    'message' => 'Credencial no encontrada.'
                ], 404);
            }

            $request->validate([
                'des_dns' => 'sometimes|required|string',
                'des_usuario' => 'sometimes|required|string',
                'des_db' => 'sometimes|required|string',
                'des_password' => 'sometimes|required|string',
            ]);

            // Solo actualizar los campos que no sean la clave
            $updateData = $request->only(['des_dns', 'des_usuario', 'des_db', 'des_password']);
            $credencial->update($updateData);

            return response()->json([
                'message' => 'Credencial actualizada exitosamente.',
                'data' => $credencial->getDecryptedAttributes()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la credencial.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $clave = $request->input('clave');
        
        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }
        
        try {
            // Buscar por clave descifrada
            $credencial = Credenciales::findByDecryptedKey($clave);

            if (!$credencial) {
                return response()->json([
                    'message' => 'Credencial no encontrada.'
                ], 404);
            }

            $credencial->delete();

            return response()->json([
                'message' => 'Credencial eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la credencial.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método adicional para obtener credenciales con datos cifrados (para debug)
     */
    public function showEncrypted(Request $request)
    {
        $clave = $request->input('clave');
        
        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }

        // Buscar por clave descifrada primero
        $credencial = Credenciales::findByDecryptedKey($clave);

        if (!$credencial) {
            return response()->json([
                'message' => 'Credencial no encontrada.'
            ], 404);
        }

        // Mostrar datos tal como están en la BD (cifrados)
        return response()->json([
            'message' => 'Credencial encontrada (datos cifrados).',
            'data' => $credencial->getAttributes()
        ]);
    }

    /**
     * Método para listar todas las credenciales con datos cifrados (para debug)
     */
    public function indexEncrypted()
    {
        try {
            $credenciales = Credenciales::all();

            if ($credenciales->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron credenciales.'
                ]);
            }

            // Mostrar datos tal como están en la BD (cifrados)
            $credencialesCifradas = $credenciales->map(function ($credencial) {
                return $credencial->getAttributes();
            });

            return response()->json([
                'message' => 'Credenciales encontradas (datos cifrados).',
                'data' => $credencialesCifradas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las credenciales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
