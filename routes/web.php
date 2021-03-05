<?php

use App\Mail\ContactMe;
use App\Notifications\Notificacion;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('index', function () {
    return view('index');
});

//////////////


Route::get('/email', function () {
    return view('mail');
})->middleware('auth');

Route::post('/email', function (Request $req) {
    request()->validate(['email' => 'required|email']);

    // Mail::to($req->email)
    //     ->send(new ContactMe('tus remeras'));


    request()->user()->notify(new Notificacion());

    return redirect('/email')
        ->with('message', 'Email enviado');
})->middleware('auth');



//////////////


Route::resource('/users', 'App\Http\Controllers\UserController');
Route::resource('/domains', 'App\Http\Controllers\DomainController');
Route::resource('/emails', 'App\Http\Controllers\EmailController');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
