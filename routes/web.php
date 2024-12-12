<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return redirect('/equipments');
});

Route::get('/{page}', function () {
    return view('index');
});
