<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;

// Existing route for index
Route::get('/firebase-users', [FirebaseController::class, 'index'])->name('firebase.index');

// New route for user details by asalInstansi
Route::get('/firebase-users/details/{asalInstansi}', [FirebaseController::class, 'showUsersByInstansi'])->name('firebase.userDetails');
Route::get('/users/export/{asalInstansi}', [FirebaseController::class, 'exportUsersByInstansi'])->name('users.export');
Route::post('/users/upload', [FirebaseController::class, 'uploadUsers'])->name('users.upload');


Route::get('/', function () {
    return view('welcome');
});
