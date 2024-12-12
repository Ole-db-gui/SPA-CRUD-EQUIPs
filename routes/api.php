<?php
use Illuminate\Support\Facades\Route;

Route::apiResource('equipment-type', App\Http\Controllers\EquipmentTypeController::class);
Route::apiResource('equipment', App\Http\Controllers\EquipmentController::class);
