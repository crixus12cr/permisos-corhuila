<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'type_document_id' => 'required|numeric|exists:type_documents,id',
            'document_number' => 'required',
            'position_id' => 'required|numeric|exists:positions,id',
            'area_id' => 'required|numeric|exists:areas,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_document_id' => $request->type_document_id,
                'document_number' => $request->document_number,
                'position_id' => $request->position_id,
                'area_id' => $request->area_id,
                'rol_id' => 3, /* usuario */
            ]);
            $user->load('type_document');

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'type_document' => $user->type_document->name,
                        'document_number' => $user->documen_number,
                        'position_id' => $user->position_id,
                        'areaId' => $user->area_id,
                        'rol_id' => $user->rol_id
                    ],
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocurrió un error mientras se registraba el usuario: '.$e->getMessage()], 500);
        }
    }



    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Credenciales incorrectas'], 401);
            }

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'type_document' => $user->type_document->name,
                    'document_number' => $user->document_number,
                    'position_id' => $user->position_id,
                    'area_id' => $user->area_id,
                    'rol_id' => $user->rol_id
                ],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocurrio un error mientras intentaba iniciar sesion.'], 500);
        }
    }
}
