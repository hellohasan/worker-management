<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HireController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Settings\BasicSettingController;
use App\Http\Controllers\Settings\ServerInformationController;

Route::get('/', function () {
    return to_route('login');
});

Auth::routes([
    'register' => false,
    'verify'   => false,
]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'settings'], function () {

        Route::get('edit-profile', [ProfileController::class, 'manageProfile'])->name('edit-profile');
        Route::post('edit-profile', [ProfileController::class, 'updateProfile'])->name('update-profile');

        Route::get('change-password', [ProfileController::class, 'managePassword'])->name('change-password');
        Route::post('change-password', [ProfileController::class, 'updatePassword'])->name('update-password');

        Route::get('basic-content', [BasicSettingController::class, 'basicContent'])->name('basic-content');
        Route::post('basic-content', [BasicSettingController::class, 'updateBasicContent'])->name('update-basic-content');

        Route::get('logo-favicon', [BasicSettingController::class, 'logoFavicon'])->name('logo-favicon');
        Route::post('logo-favicon', [BasicSettingController::class, 'updateLogoFavicon'])->name('update-logo-favicon');
    });

    Route::group(['prefix' => 'backend'], function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('workers', WorkerController::class);
        Route::resource('companies', CompanyController::class);
    });

    Route::group(['prefix' => 'company'], function () {
        Route::get('new-hire', [HireController::class, 'newHire'])->name('hire.new');
        Route::get('hire-history', [HireController::class, 'history'])->name('hire.history');
        Route::post('hire-cart', [HireController::class, 'storeHire'])->name('hire-cart');
        Route::get('worker-cart', [HireController::class, 'cart'])->name('worker-cart');
        Route::get('hire-remove/{id}', [HireController::class, 'remove'])->name('hire-remove');
        Route::post('hire-confirm', [HireController::class, 'confirm'])->name('hire-confirm');
        Route::post('hire-destroy', [HireController::class, 'destroy'])->name('hire-destroy');
        Route::delete('hire-worker-destroy/{id}', [HireController::class, 'workerDestroy'])->name('hire-employee-destroy');
        Route::post('hire-updated', [HireController::class, 'update'])->name('hire.updated')->middleware('permission:hire-update');
        Route::delete('order-destroy/{id}', [HireController::class, 'orderDestroy'])->name('order-destroy');

        Route::get('employee-details/{custom}', [HireController::class, 'employeeDetails'])->name('employee-details');

        Route::get('hire-details/{custom}', [HireController::class, 'details'])->name('hire-details');

    });

    Route::resource('users', UserController::class);

    Route::get('test-blade', TestController::class)->name('test-blade');

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('system-information', [ServerInformationController::class, 'getInformation'])->name('system-information');
});
