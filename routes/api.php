<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/token', function (Request $request) {
    // Require email and password
    $request->validate([
        'email' => ['required'],
        'password' => ['required'],
    ]);

    // Find user, or not...
    $user = \App\Models\User::query()
        ->where('email', $request->email)
        ->firstOrFail();

    // Validate the password
    if (\Illuminate\Support\Facades\Hash::check($request->password , $user->password) === false) {
        return json_encode(['error' => 'Unauthenticated']);
    }

    // Create and return the token
    $token = $user->createToken('mobile_api');
    // TODO: Potentially we delete any previous tokens for the mobile app? Or would this invalidate other devices...
    // We might be able to add device information to the tokens so we can identify which device is using the token
    return json_encode(['token' => $token->plainTextToken]);
});