<?php

use App\Http\Controllers\GameweekController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::resource('groups', GroupController::class)->except(['index']);
    Route::post('groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');

    Route::resource('groups/{group}/gameweeks', GameweekController::class);

    Route::get('groups/{group}/gameweeks/{gameweek}/predictions', [
        GameweekController::class, 'editPredictions'
    ])->name('gameweeks.edit-predictions');

    Route::post('groups/{group}/gameweeks/{gameweek}/predictions', [
        GameweekController::class, 'updatePredictions'
    ])->name('gameweeks.update-predictions');

});

require __DIR__.'/auth.php';
