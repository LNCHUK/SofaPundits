<?php

use App\Http\Controllers\Gameweeks\FixturesController;
use App\Http\Controllers\Gameweeks\GameweekController;
use App\Http\Controllers\Gameweeks\PredictionsController;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\Groups\PlayersController;
use App\Http\Controllers\User\PreferencesController;
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
        \App\Jobs\ImportFixtureEvents::dispatch(868176);
    });

    Route::get('mail', function () {
        $gameweek = \App\Models\Gameweek::find(1);
        return new \App\Mail\GameweekWasPublished($gameweek);
    });

    // Dashboard route
    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

    // User based routes
    Route::get('user/preferences', [PreferencesController::class, 'index'])
        ->name('user.preferences');
    Route::post('user/preferences', [PreferencesController::class, 'update'])
        ->name('user.preferences');

    // TODO: Remove this once the scheduler has been sorted (or add a more permanent method for it as a utility)
    Route::get('load-fixtures', function () {
        \Illuminate\Support\Facades\Artisan::call('import:fixtures');
        return redirect()->back();
    })->name('fixtures.load');

    Route::resource('groups', GroupController::class)->except(['index']);
    Route::post('groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');

    // Group Players Routes
    Route::get('groups/{group}/players', [PlayersController::class, 'index'])->name('groups.players');
    Route::patch('groups/{group}/players', [PlayersController::class, 'update'])->name('groups.players.update');

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
