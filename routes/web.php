<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['use-tenant'])->group(function () {
    Route::get('/', function () {

    });
    Route::apiResources([
        'tenant' => PhotoController::class,
        'user' => PostController::class,
    ]);

    Route::singleton('user.tenant', ThumbnailController::class)->creatable();
});
