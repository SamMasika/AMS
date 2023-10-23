<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SectionController;

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
Route::prefix('AMS')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::get('/logout', [AuthController::class, 'logout']);

        //Section
                Route::get('/section-list', [SectionController::class, 'index']);
            Route::post('/section-store', [SectionController::class, 'store']);
            Route::get('/section-show/{id}', [SectionController::class, 'show']);
            Route::post('/section-update/{id}', [SectionController::class, 'update']);
            Route::delete('/section-delete/{id}', [SectionController::class, 'destroy']);
           //Office
           Route::get('/office-list', [OfficeController::class, 'index']);
           Route::post('/office-store', [OfficeController::class, 'store']);
           Route::get('/office-show/{id}', [OfficeController::class, 'show']);
           Route::post('/office-update/{id}', [OfficeController::class, 'update']);
           Route::delete('/office-delete/{id}', [OfficeController::class, 'destroy']);
           //Staff
           Route::get('/staff-list', [StaffController::class, 'index']);
           Route::post('/staff-store', [StaffController::class, 'store']);
           Route::get('/staff-show/{id}', [StaffController::class, 'show']);
           Route::post('/staff-update/{id}', [StaffController::class, 'update']);
           Route::delete('/staff-delete/{id}', [StaffController::class, 'destroy']);
             //Asset
             Route::get('/asset-list', [AssetController::class, 'index']);
             Route::post('/asset-store', [AssetController::class, 'store']);
             Route::get('/asset-show/{id}', [AssetController::class, 'show']);
             Route::post('/asset-update/{id}', [AssetController::class, 'update']);
             Route::delete('/asset-delete/{id}', [AssetController::class, 'destroy']);
             Route::get('/electronics', [AssetController::class, 'electronics']);
             Route::get('/laptops', [AssetController::class, 'laptops']);
             Route::get('/disks', [AssetController::class, 'disks']);
             Route::get('/televisions', [AssetController::class, 'televisions']);
             Route::get('/desktops', [AssetController::class, 'desktops']);
             Route::get('/furniture', [AssetController::class, 'furniture']);
             Route::post('/assign-asset/{id}', [AssetController::class, 'assignElectronics']);
             Route::post('/assign-furniture/{id}', [AssetController::class, 'assignFurniture']);
             Route::post('/unassign-electronic/{id}', [AssetController::class, 'electronicUnassign']);
             Route::get('/assets-by-section', [AssetController::class,'assetsBySection']);
             Route::get('/assets-by-section/{sectionName}', [ReportController::class, 'assetsBySection']);
             Route::get('/assets-by-staff/{sectionName}', [ReportController::class, 'assetsByStaff']);
             Route::get('/assets-by-office/{officeName}', [ReportController::class, 'assetsByOffice']);

    });
    
});
