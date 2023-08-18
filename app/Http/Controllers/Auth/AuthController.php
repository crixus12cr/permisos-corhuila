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
            'lastName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'typeDocumentId' => 'required',
            'documentNumber' => 'required|unique:users',
            'positionId' => 'required',
            'areaId' => 'required',
            'rolId' => 'required',
        ];

        try {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type_document_id' => $request->typeDocumentId,
                'document_number' => $request->documentNumber,
                'position_id' => $request->positionId,
                'area_id' => $request->areaId,
                'rol_id' => 3, /* usuario */
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'typeDocument' => $user->type_document->name,
                    'documentNumber' => $user->document_number,
                    'positionId' => $user->position_id,
                    'areaId' => $user->area_id,
                    'rolId' => $user->rol_id
                ],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocurrio un error mientras se registraba el usuario'], 500);
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
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'typeDocument' => $user->type_document->name,
                    'documentNumber' => $user->document_number,
                    'positionId' => $user->position_id,
                    'areaId' => $user->area_id,
                    'rolId' => $user->rol_id
                ],
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocurrio un error mientras intentaba iniciar sesion.'], 500);
        }
    }
}
