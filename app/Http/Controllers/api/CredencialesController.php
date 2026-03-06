<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Credenciales;
use Illuminate\Http\Request;

class CredencialesController extends Controller
{
    //
    public function index()
    {
        $response = Credenciales::all();

        if ($response->isEmpty()) {
            $response = [
                'message' => 'No se encontraron credenciales.'
            ];
        }
        
        return response()->json($response);
    }

    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|unique:credenciales,clave',
            'des_dns' => 'required|string',
            'des_usuario' => 'required|string',
            'des_db' => 'required|string',
            'des_password' => 'required|string',
        ]);

        //Guardar la credencial en la base de datos
        $credencial = Credenciales::create($request->all());

        return response()->json([
            'message' => 'Credencial creada exitosamente.',
            'data' => $credencial
        ], 201);
    }

    public function show(Request $request)
    {
        $clave = $request->input('clave');
        
        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }

        $credencial = Credenciales::find($clave);

        if (!$credencial) {
            return response()->json([
                'message' => 'Credencial no encontrada.'
            ], 404);
        }

        return response()->json([
            'message' => 'Credencial encontrada.',
            'data' => $credencial
        ]);
    }

    public function update(Request $request)
    {
        $clave = $request->input('clave');

        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }

        $credencial = Credenciales::find($clave);

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

        $credencial->update($request->all());

        return response()->json([
            'message' => 'Credencial actualizada exitosamente.',
            'data' => $credencial
        ]);
    }

    public function destroy(Request $request)
    {
        $clave = $request->input('clave');
        
        if (!$clave) {
            return response()->json([
                'message' => 'La clave es requerida.'
            ], 400);
        }
        
        $credencial = Credenciales::find($clave);

        if (!$credencial) {
            return response()->json([
                'message' => 'Credencial no encontrada.'
            ], 404);
        }

        $credencial->delete();

        return response()->json([
            'message' => 'Credencial eliminada exitosamente.'
        ]);
    }
}
