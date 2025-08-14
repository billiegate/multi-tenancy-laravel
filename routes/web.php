<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\ConfigController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\CacheController;
use App\Http\Controllers\Landlord\TenantController;
use App\Http\Middleware\SetTenantMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::withoutMiddleware([SetTenantMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/health', function () {
        return response()->json(['status' => 'ok']);
    })->name('health');

    Route::get('/tenants', [TenantController::class, 'index'])
        ->name('tenants.index');

    Route::post('/tenants', [TenantController::class, 'store'])
        ->name('tenants.store');
});


Route::middleware(['use-tenant'])->group(function () {

    Route::apiResources([
        'config'    => ConfigController::class,
        'user'      => UserController::class,
        'cache'     => CacheController::class,
    ]);

    Route::get('/tenant', [TenantController::class, 'show'])
        ->name('tenants.show');
});
