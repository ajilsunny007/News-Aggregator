<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
});

Auth::routes();

Route::get('login', function () {
    $response = [
        'message' => 'Unauthenticated. Please login.',
        'error' => true,
        "code" => 200,
    ];

    return response()->json($response, 401);
})->name('login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
