<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;


Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Company Users
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'company.context'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/orders', [OrderController::class, 'index']);

    Route::post('/orders', [OrderController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| Editor / Platform Management
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'editor'])->group(function () {

    Route::get('/companies', [CompanyController::class, 'index']);

    Route::post('/companies', [CompanyController::class, 'store']);

    Route::get('/companies/{company}', [CompanyController::class, 'show']);

    Route::put('/companies/{company}', [CompanyController::class, 'update']);

    Route::patch(
        '/companies/{company}/disable',
        [CompanyController::class, 'disable']
    );


    /*
    |--------------------------------------------------------------------------
    | Invitations
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/invitations',
        [InvitationController::class, 'store']
    );
});


/*
|--------------------------------------------------------------------------
| Public Invitation Acceptance
|--------------------------------------------------------------------------
*/

Route::post(
    '/invitations/accept',
    [InvitationController::class, 'accept']
);