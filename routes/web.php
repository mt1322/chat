<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::get('/', [ChatController::class, 'index'])
    ->name('index');

Route::post('/post', [ChatController::class, 'store'])
    ->name('store');

Route::get('/{num}/edit', [ChatController::class, 'edit'])
    ->name('edit');

Route::get('/{num}/update', [ChatController::class, 'update'])
    ->name('update');

Route::delete('/{message}', [ChatController::class, 'destroy'])
    ->name('destroy');


// Route::get('/post', [ChatController::class, 'get'])
    // ->name('show');

// Route::post('/post', [ChatController::class, 'store'])
    // ->name('post');
