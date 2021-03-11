<?php

use App\Mail\ContactMe;
use App\Notifications\Notificacion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


Route::get('/', 'App\Http\Controllers\UserController@dash')->name('dashboard');

//? Controladores de DB
Route::resource('/users', 'App\Http\Controllers\UserController');
Route::resource('/domains', 'App\Http\Controllers\DomainController');
Route::resource('/emails', 'App\Http\Controllers\EmailController');

//? Controladores de API externas
Route::post('/intelxmail/{email}', 'App\Http\Controllers\EmailController@intelxSearch');
Route::post('/intelxdomain/{domain}', 'App\Http\Controllers\DomainController@intelxSearch');
Route::post('/domain-search/{domain}', 'App\Http\Controllers\DomainController@hunterDomainSearch');
Route::post('/person-search/{domain}', 'App\Http\Controllers\DomainController@hunterPersonSearch');
Route::post('/person-save/{domain}', 'App\Http\Controllers\DomainController@hunterSavePerson');
Route::delete('/delete-hunter-data/{hunterData}', 'App\Http\Controllers\HunterController@destroy');
Route::post('/asociate-hunter-data/{hunterData}', 'App\Http\Controllers\HunterController@asociateEmail');
Route::get('/getFile/{file}', 'App\Http\Controllers\IntelxController@getFile');

// Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
//     return view('dash');
// })->name('dashboard');
