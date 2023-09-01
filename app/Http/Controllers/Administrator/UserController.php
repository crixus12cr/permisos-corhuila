<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
            $users = User::when($request->name, function ($query) use ($request) {
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
                ->select(
                    'id',
                    'name',
                    'last_name',
                    'email',
                    'type_document_id',
                    'document_number',
                    'position_id',
                    'area_id',
                )
                ->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'No hay usuarios'], 404);
            }

            return response()->json($users);
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
        //
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
