<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = User::when($request->name, function ($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%')
                    ->orWhere('last_name', 'ilike', '%' . $request->name . '%');
            })
                ->when($request->document_number, function ($query) use ($request) {
                    $query->where('document_number', $request->document_number);
                })
                ->when($request->position_id, function ($query) use ($request) {
                    $query->where('position_id', $request->position_id);
                })
                ->when($request->area_id, function ($query) use ($request) {
                    $query->where('area_id', $request->area_id);
                })
                ->with('type_document','position','area','rol')
                ->get();

            if ($user->isEmpty()) {
                return response()->json(['message' => 'No hay usuarios'], 404);
            }

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'type_document_id' => 'required|numeric',
                'document_number' => 'required|string|unique:users',
                'position_id' => 'required|numeric',
                'area_id' => 'required|numeric',
                'rol_id' => 'required|numeric',
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'type_document_id' => $request->input('type_document_id'),
                'document_number' => $request->input('document_number'),
                'position_id' => $request->input('position_id'),
                'area_id' => $request->input('area_id'),
                'rol_id' => $request->input('rol_id'),
            ]);

            return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el usuario: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Usuario no encontrado: ' . $e->getMessage()], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $rol = $request->rol_id??null;
            $contrasena = Hash::make($request->password)??null;

            if ($rol !== null) {
                $user->update([
                    'rol_id' => $rol,
                ]);
            }

            if ($contrasena !== null) {
                $user->update([
                    'password' => $contrasena
                ]);
            }


            return response()->json(['message' => 'Usuario actualizado con éxito', 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el usuario: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json(['message' => 'Usuario eliminado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario: ' . $e->getMessage()], 500);
        }
    }
}
