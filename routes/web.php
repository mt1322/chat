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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/start', [ChatController::class, 'start'])
    ->name('start');

Route::get('/chat', [ChatController::class, 'index'])
    ->name('index');

Route::post('/post', [ChatController::class, 'store'])
    ->name('store');

Route::get('/{num}/edit',  [ChatController::class, 'edit'])
    ->name('edit');

Route::patch('/{message}/update', [ChatController::class, 'update'])
    ->name('update');

Route::delete('/{message}/delete', [ChatController::class, 'destroy'])
    ->name('destroy');

Route::post('/add', [ChatController::class, 'add'])
    ->name('add');

Route::get('/{id}/change', [ChatController::class, 'change'])
    ->name('change');

Route::delete('/{message}/deleteChannel', [ChatController::class, 'destroyChannel'])
    ->name('destroyChannel');

Route::delete('/{message}/deleteOwnChannel', [ChatController::class, 'destroyOwnChannel'])
    ->name('destroyOwnChannel');
