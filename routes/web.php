<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AppoinmentsController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group( function () {

Route::get('/api/users',[UserController::class,"index"]);
Route::post('/api/users',[UserController::class,"store"]);
Route::post('/api/users/update',[UserController::class,"UpdateUser"]);
Route::patch('/api/users/{user}/role-change',[UserController::class,"roleChange"]);


// Route::get('/api/users/search',[UserController::class,"searchQuery"]);


Route::get('/api/appointments/',[AppoinmentsController::class,"index"]);
Route::post('/api/appointment/create',[AppoinmentsController::class,"store"]);
Route::get('/api/appointment/{appointment}/edit',[AppoinmentsController::class,"edit"]);
Route::put('/api/appointment/{appointment}/edit',[AppoinmentsController::class,"update"]);
Route::delete('/api/appointment/{appointment}',[AppoinmentsController::class,"delete"]);
Route::get('/api/clients/',[ClientController::class,"getClients"]);
Route::get('/api/status/schedule',[ClientController::class,"getStatusCount"]);

Route::post('/api/logout',[UserController::class,"logout"]);
});

Route::get('test-route', [AppoinmentsController::class,'test'])->name('test.route');
Route::get('{view}', ApplicationController::class)->where('view','(.*)')->middleware('auth');
