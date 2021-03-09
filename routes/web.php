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
Route::post('/domains/{domain}', 'App\Http\Controllers\DomainController@intelxSearch');
Route::get('/domain-search/{domain}', 'App\Http\Controllers\DomainController@hunterDomainSearch');
Route::get('/person-search/{domain}', 'App\Http\Controllers\DomainController@hunterPersonSearch');
Route::post('/person-save/{domain}', 'App\Http\Controllers\DomainController@hunterSavePerson');

Route::delete('/delete-hunter-data/{hunterData}', 'App\utilities\Hunter@destroy');
Route::post('/asociate-hunter-data/{hunterData}', 'App\utilities\Hunter@asociateEmail');


Route::resource('/emails', 'App\Http\Controllers\EmailController');
Route::post('/emails/{email}', 'App\Http\Controllers\EmailController@intelxSearch');

Route::get('/getFile/{file}', 'App\utilities\Intelx@getFile');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
