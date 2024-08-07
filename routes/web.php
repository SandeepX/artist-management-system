<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('user-register', [RegisterController::class, 'customUserRegister'])->name('custom-register');
Route::group([
    'prefix' => 'users',
    'as' => 'users.',
    'middleware' => ['auth','super_admin']
], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('create', [UserController::class, 'create'])->name('create');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::put('{id}/update', [UserController::class, 'update'])->name('update');
});

Route::group([
    'prefix' => 'artists',
    'as' => 'artists.',
    'middleware' => ['auth','manager']
], function () {
    Route::get('{id}/edit', [ArtistController::class, 'edit'])->name('edit');
    Route::resource('/', ArtistController::class)->except(['destroy', 'show', 'edit','update']);
    Route::get('delete/{id}', [ArtistController::class, 'delete'])->name('delete');
    Route::put('update/{id}', [ArtistController::class, 'update'])->name('update');
    Route::get('export', [ArtistController::class, 'exportArtists'])->name('export');
    Route::post('artists/import', [ArtistController::class, 'importArtists'])->name('import');
});

