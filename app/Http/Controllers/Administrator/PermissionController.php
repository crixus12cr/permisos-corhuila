<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payload = JWTAuth::parseToken()->getPayload();

        $userId = $payload->get('sub');

        $userAccess = User::where('id', $userId)->with('rol')->first();

        $rol = $userAccess->rol->id;

        switch ($rol) {
            case 1: //super usuario
                $permiso = Permission::when($request->created_at, function ($query) use($request){
                    $query->where('created_at', $request->created_at);
                })
                ->when($request->document_number, function ($query) use($request){
                    $query->whereHas('user', function ($_query) use($request) {
                        $_query->where('document_number', $request->document_number);
                    });
                })
                ->when($request->area, function ($query) use($request) {
                    $query->whereHas('user.area', function ($_query) use($request) {
                        $_query->where('name', 'ilike', '%' . $request->area . '%');
                    });
                })
                ->when($request->area, function ($query) use($request) {
                    $query->whereHas('user.position', function ($_query) use($request) {
                        $_query->where('name', 'ilike', '%' . $request->area . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

                return response()->json($permiso);
                break;
            case 2://administrador
                $permiso = Permission::when($request->created_at, function ($query) use($request){
                    $query->where('created_at', $request->created_at);
                })
                ->when($request->document_number, function ($query) use($request){
                    $query->whereHas('user', function ($_query) use($request) {
                        $_query->where('document_number', $request->document_number);
                    });
                })
                ->when($request->area, function ($query) use($request) {
                    $query->whereHas('user.area', function ($_query) use($request) {
                        $_query->where('name', 'ilike', '%' . $request->area . '%');
                    });
                })
                ->when($request->area, function ($query) use($request) {
                    $query->whereHas('user.position', function ($_query) use($request) {
                        $_query->where('name', 'ilike', '%' . $request->area . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

                return response()->json($permiso);
                break;
            case 3: //usuario
                $permiso =  Permission::where('user_id', $userId)
                ->when($request->created_at, function ($query) use($request){
                    $query->where('created_at', $request->created_at);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

                return response()->json($permiso);
                break;

            default:
                # code...
                break;
        }

        // return response()->json(Permission::get());
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

            $data = $request->only([
                'request_date',
                'date_permission',
                'time_start',
                'time_end',
                'commitment',
                'observations',
                'user_id'
            ]);

            $permiso = Permission::create($data);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Permiso creado exitosamente',
                'data' => $permiso
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
