<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookApiController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

//  API Authentication (Token)
Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    // Check password
    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Invalid credentials.'],
        ]);
    }

    // Create and return the token
    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token, 'user_role' => $user->Role]);
});


// API Book Management Routes

//all CRUD operations for books
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('books', BookApiController::class)->except('index', 'show');

    //Get authenticated user details
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


// routes for public Viewing of books
Route::get('/books', [BookApiController::class, 'index']);
Route::get('/books/{book}', [BookApiController::class, 'show']);
