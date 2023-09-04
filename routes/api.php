<?php

use App\Http\Controllers\Administrator\AreaController;
use App\Http\Controllers\Administrator\PermissionController;
use App\Http\Controllers\Administrator\PositionController;
use App\Http\Controllers\Administrator\RolController;
use App\Http\Controllers\Administrator\TypeDocumentController;
use App\Http\Controllers\Administrator\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/rol', RolController::class);
Route::apiResource('/area', AreaController::class);
Route::apiResource('/type-document', TypeDocumentController::class);
Route::apiResource('/position', PositionController::class);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

/* rutas */

Route::group(['middleware' => ['jwt.token']], function () {

    Route::apiResource('/permission', PermissionController::class);
    Route::post('/autorization', [PermissionController::class, 'autorization']);
    Route::apiResource('/users', UserController::class);
    Route::post('/user/password', [UserController::class, 'contra']);
    Route::post('/descargar/excel', [PermissionController::class, 'downloadExcel']);
    Route::get('/descargar/archivo/{id}', [PermissionController::class, 'downloadFile']);
});



