<?php

use App\Mail\ContactMe;
use App\Notifications\Notificacion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

Route::get('/', function () {
    return view('index');
});

Route::get('index', function () {
    return view('index');
});

Route::resource('/users', 'App\Http\Controllers\UserController');
Route::resource('/domains', 'App\Http\Controllers\DomainController');
Route::resource('/emails', 'App\Http\Controllers\EmailController');
Route::post('/emails/{email}', 'App\Http\Controllers\EmailController@search');
Route::get('/getFile/{file}', 'App\Http\Controllers\EmailController@getFile');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
