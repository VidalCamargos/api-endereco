<?php

use App\Http\Controllers\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* @var \Illuminate\Routing\Router $router */

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

$router->get('/address', [AddressController::class, 'getAddress'])
    ->name('address');
