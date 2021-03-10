<?php

use App\Mail\ContactMe;
use App\Notifications\Notificacion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;


Route::get('/', 'App\Http\Controllers\UserController@dash');

Route::resource('/users', 'App\Http\Controllers\UserController');
Route::resource('/domains', 'App\Http\Controllers\DomainController');

Route::post('/domains/{domain}', 'App\Http\Controllers\DomainController@intelxSearch');
Route::post('/domain-search/{domain}', 'App\Http\Controllers\DomainController@hunterDomainSearch');
Route::post('/person-search/{domain}', 'App\Http\Controllers\DomainController@hunterPersonSearch');
Route::post('/person-save/{domain}', 'App\Http\Controllers\DomainController@hunterSavePerson');

Route::delete('/delete-hunter-data/{hunterData}', 'App\Http\Controllers\HunterController@destroy');
Route::post('/asociate-hunter-data/{hunterData}', 'App\Http\Controllers\HunterController@asociateEmail');

Route::resource('/emails', 'App\Http\Controllers\EmailController');
Route::post('/emails/{email}', 'App\Http\Controllers\EmailController@intelxSearch');

Route::get('/getFile/{file}', 'App\Http\Controllers\IntelxController@getFile');

// Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
//     return view('dash');
// })->name('dashboard');
