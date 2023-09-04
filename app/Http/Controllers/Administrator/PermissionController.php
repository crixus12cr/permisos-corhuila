<?php

namespace App\Http\Controllers\Administrator;

use App\Exports\PermisosExport;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $data = false)
    {
        $payload = JWTAuth::parseToken()->getPayload();

        $userId = $payload->get('sub');

        $userAccess = User::where('id', $userId)->with('rol')->first();

        $rol = $userAccess->rol->id;

        switch ($rol) {
            case 1: //super usuario
                $permiso = Permission::when($request->created_at, function ($query) use ($request) {
                    if ($request->created_at === 'last_day') {
                        $query->whereDate('created_at', Carbon::yesterday());
                    } elseif ($request->created_at === 'last_week') {
                        $query->whereDate('created_at', '>', Carbon::now()->subWeek());
                    } elseif ($request->created_at === 'last_month') {
                        $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                        $query->whereYear('created_at', Carbon::now()->subMonth()->year);
                    } elseif ($request->created_at === 'last_year') {
                        $query->whereYear('created_at', Carbon::now()->subYear()->year);
                    } else {
                        $query->whereDate('created_at', $request->created_at);
                    }
                })
                    ->when($request->document_number, function ($query) use ($request) {
                        $query->whereHas('user', function ($_query) use ($request) {
                            $_query->where('document_number', $request->document_number);
                        });
                    })
                    ->when($request->area, function ($query) use ($request) {
                        $query->whereHas('user.area', function ($_query) use ($request) {
                            $_query->where('name', 'ilike', '%' . $request->area . '%');
                        });
                    })
                    ->when($request->position, function ($query) use ($request) {
                        $query->whereHas('user.position', function ($_query) use ($request) {
                            $_query->where('name', 'ilike', '%' . $request->position . '%');
                        });
                    })
                    ->with('user')
                    ->orderBy('created_at', 'desc');

                break;
            case 2: //administrador
                $permiso = Permission::when($request->created_at, function ($query) use ($request) {
                    if ($request->created_at === 'last_day') {
                        $query->whereDate('created_at', Carbon::yesterday());
                    } elseif ($request->created_at === 'last_week') {
                        $query->whereDate('created_at', '>', Carbon::now()->subWeek());
                    } elseif ($request->created_at === 'last_month') {
                        $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                        $query->whereYear('created_at', Carbon::now()->subMonth()->year);
                    } elseif ($request->created_at === 'last_year') {
                        $query->whereYear('created_at', Carbon::now()->subYear()->year);
                    } else {
                        $query->whereDate('created_at', $request->created_at);
                    }
                })
                    ->when($request->document_number, function ($query) use ($request) {
                        $query->whereHas('user', function ($_query) use ($request) {
                            $_query->where('document_number', $request->document_number);
                        });
                    })
                    ->when($request->area, function ($query) use ($request) {
                        $query->whereHas('user.area', function ($_query) use ($request) {
                            $_query->where('name', 'ilike', '%' . $request->area . '%');
                        });
                    })
                    ->when($request->area, function ($query) use ($request) {
                        $query->whereHas('user.position', function ($_query) use ($request) {
                            $_query->where('name', 'ilike', '%' . $request->area . '%');
                        });
                    })
                    ->orderBy('created_at', 'desc');

                break;
            case 3: //usuario
                $permiso =  Permission::where('user_id', $userId)
                    ->when($request->created_at, function ($query) use ($request) {
                        if ($request->created_at === 'last_day') {
                            $query->whereDate('created_at', Carbon::yesterday());
                        } elseif ($request->created_at === 'last_week') {
                            $query->whereDate('created_at', '>', Carbon::now()->subWeek());
                        } elseif ($request->created_at === 'last_month') {
                            $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
                            $query->whereYear('created_at', Carbon::now()->subMonth()->year);
                        } elseif ($request->created_at === 'last_year') {
                            $query->whereYear('created_at', Carbon::now()->subYear()->year);
                        } else {
                            $query->whereDate('created_at', $request->created_at);
                        }
                    })
                    ->orderBy('created_at', 'desc');

                break;

            default:
                # code...
                break;
        }

        if ($data) {
            return $permissions = $permiso->get();
        } else {
            $permissions = $permiso->paginate(10);
            return response()->json($permissions);
        }
    }

    public function downloadExcel(Request $request)
    {
        $datos = $this->index($request, $data = true);

        return Excel::download(new PermisosExport($datos), 'permissions.xlsx');
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

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = Storage::putFile('public/reports', $file);

                $data['file'] = $path;
            }

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

        $permission = Permission::find($id);

        if ($permission) {
            $permission->update([
                'autorization_boss' => $request->boss,
                'autorization_hr' => $request->hr,
            ]);

            $permission->save();
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
        //
    }
}
