<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SubscriptionController;

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Company Users
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'company.context',
    'subscription',
])->group(function () {

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


/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'permission:users.view'
])->group(function () {

    Route::get(
        '/users',
        [UserController::class, 'index']
    );

    Route::get(
        '/users/{user}',
        [UserController::class, 'show']
    );
});


Route::middleware([
    'auth:sanctum',
    'permission:users.update'
])->group(function () {

    Route::put(
        '/users/{user}',
        [UserController::class, 'update']
    );
});


Route::middleware([
    'auth:sanctum',
    'permission:users.disable'
])->group(function () {

    Route::patch(
        '/users/{user}/disable',
        [UserController::class, 'disable']
    );
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/audit-logs', [AuditLogController::class, 'index']);
    Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show']);
});


Route::middleware(['auth:sanctum', 'editor'])->group(function () {

    Route::patch(
        '/companies/{company}/subscription',
        [SubscriptionController::class, 'update']
    );

});