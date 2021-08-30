<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/',[\App\Http\Controllers\MainController::class,'index']);
Route::post('/save-personal',[\App\Http\Controllers\MainController::class,'savePersonal']);
Route::get('/eye-test',[\App\Http\Controllers\MainController::class,'eyeTest']);
Route::get('/form-test',[\App\Http\Controllers\MainController::class,'formTest']);
Route::post('/finish-test',[\App\Http\Controllers\MainController::class,'finishTest']);

Route::middleware("api")->group(function() {
    Route::post('api/abort-test', [\App\Http\Controllers\MainController::class,'abort']);
    Route::post('api/eye-test-completed', [\App\Http\Controllers\MainController::class,'eyeTestCompleted']);
});


