<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('user-register', [\App\Http\Controllers\Auth\RegisterController::class, 'customUserRegister'])->name('custom-register');
Route::group([
    'prefix' => 'users',
    'as' => 'users.',
    'middleware' => ['auth']
], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::put('{id}/update', [UserController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'managers',
    'as' => 'managers.',
    'middleware' => ['auth']
], function () {

});

