<?php

use App\Http\Controllers\Gameweeks\FixturesController;
use App\Http\Controllers\Gameweeks\GameweekController;
use App\Http\Controllers\Gameweeks\PredictionsController;
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

    Route::get('test', function () {
        $notification = new \App\Notifications\GameweekPublished(
            gameweek: \App\Models\Gameweek::first(),
            recipientName: auth()->user()->first_name,
        );
        auth()->user()->notify($notification);
    });

    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::get('load-fixtures', function () {
        \Illuminate\Support\Facades\Artisan::call('import:fixtures');
        return redirect()->back();
    })->name('fixtures.load');

    Route::resource('groups', GroupController::class)->except(['index']);
    Route::post('groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');

    // Gameweeks CRUD
    Route::resource('groups/{group}/gameweeks', GameweekController::class);

    // Publish Gameweek
    Route::post('groups/{group}/gameweeks/{gameweek}/publish', [
        GameweekController::class, 'publish'
    ])->name('gameweeks.publish');

    // Edit Gameweek Fixtures
    Route::get('groups/{group}/gameweeks/{gameweek}/fixtures', [
        FixturesController::class, 'edit'
    ])->name('gameweeks.edit-fixtures');

    // Update Gameweek Fixtures
    Route::patch('groups/{group}/gameweeks/{gameweek}/fixtures', [
        FixturesController::class, 'update'
    ])->name('gameweeks.update-fixtures');

    // Edit Gameweek Predictions
    Route::get('groups/{group}/gameweeks/{gameweek}/predictions', [
        PredictionsController::class, 'edit'
    ])->name('gameweeks.edit-predictions');

    // Edit Gameweek Predictions
    Route::get('groups/{group}/user/{user}/gameweeks/{gameweek}/predictions', [
        PredictionsController::class, 'editGroupUserPredictions'
    ])->name('gameweeks.edit-user-predictions');

    // Update Gameweek Fixtures
    Route::post('groups/{group}/gameweeks/{gameweek}/predictions', [
        PredictionsController::class, 'update'
    ])->name('gameweeks.update-predictions');

    // Update Gameweek Fixtures
    Route::post('groups/{group}/user/{user}/gameweeks/{gameweek}/predictions', [
        PredictionsController::class, 'updateUserPredictions'
    ])->name('gameweeks.update-user-predictions');

    // View Gameweek Leaderboard
    Route::get('groups/{group}/gameweeks/{gameweek}/leaderboard', [
        GameweekController::class, 'viewLeaderboard'
    ])->name('gameweeks.view-leaderboard');

});

require __DIR__.'/auth.php';
