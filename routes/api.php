<?php
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::group(['namespace'=>'EquipmentType', 'prefix'=>'equipment-types'], function(){
//   Route::get('/', 'StoreController');
//});
//Route::get('/equipment-types', [App\Http\Controllers\EquipmentTypeController::class, 'index']);
Route::apiResource('equipment-types', App\Http\Controllers\EquipmentTypeController::class);
//Route::apiResource('equipments', App\Http\Controllers\EquipmentController::class);
