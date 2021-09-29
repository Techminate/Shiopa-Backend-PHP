<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Models
use App\Models\User;

//Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RoleController;

use App\Http\Controllers\Item\CategoryController;
use App\Http\Controllers\Item\BrandController;
use App\Http\Controllers\Item\UnitController;

Route::middleware('auth:sanctum','verified')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/loginAdmin', [AuthController::class, 'loginAdmin']);
Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Auth
    Route::post('/registerUser', [AuthController::class, 'registerUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    // Users
    Route::get('/userList', [UserController::class, 'userList']);
    Route::get('/userGetById/{id}', [UserController::class, 'userGetById']);
    Route::put('/userUpdate/{id}', [UserController::class, 'userUpdate']);
    Route::delete('/userDelete/{id}', [UserController::class, 'userDelete']);
    Route::get('/userProfileView/{id}', [UserController::class, 'userProfileView']);

    // Profile
    Route::post('/userProfileCreate', [ProfileController::class, 'userProfileCreate']);
    Route::get('/userProfileGetById/{id}', [ProfileController::class, 'userProfileGetById']);
    Route::put('/userProfileUpdate/{id}', [ProfileController::class, 'userProfileUpdate']);
    Route::delete('/userProfileDelete/{id}', [ProfileController::class, 'userProfileDelete']);
    //Profile Settings
    Route::post('/profileSettingPhotoUpdate', [ProfileController::class, 'profileSettingPhotoUpdate']);
    Route::post('/profileSettingPasswordUpdate', [ProfileController::class,'profileSettingPasswordUpdate']);
    //Roles
    Route::get('/roleList', [RoleController::class, 'roleList']);
    Route::get('/roleGetById/{id}', [RoleController::class, 'roleGetById']);
    Route::post('/roleCreate', [RoleController::class, 'roleCreate']);
    Route::put('/roleUpdate/{id}', [RoleController::class, 'roleUpdate']);
    Route::delete('/roleDelete/{id}', [RoleController::class, 'roleDelete']);

    //Category
    Route::get('/categoryList', [CategoryController::class, 'categoryList']);
    Route::get('/categoryGetById/{id}', [CategoryController::class, 'categoryGetById']);
    Route::post('/categoryCreate', [CategoryController::class, 'categoryCreate']);
    Route::put('/categoryUpdate/{id}', [CategoryController::class, 'categoryUpdate']);
    Route::delete('/categoryDelete/{id}', [CategoryController::class, 'categoryDelete']);

    //Brand
    Route::get('/brandList', [BrandController::class, 'brandList']);
    Route::get('/brandGetById/{id}', [BrandController::class, 'brandGetById']);
    Route::post('/brandCreate', [BrandController::class, 'brandCreate']);
    Route::put('/brandUpdate/{id}', [BrandController::class, 'brandUpdate']);
    Route::delete('/brandDelete/{id}', [BrandController::class, 'brandDelete']);

    //unit
    Route::get('/unitList', [UnitController::class, 'unitList']);
    Route::get('/unitGetById/{id}', [UnitController::class, 'unitGetById']);
    Route::post('/unitCreate', [UnitController::class, 'unitCreate']);
    Route::put('/unitUpdate/{id}', [UnitController::class, 'unitUpdate']);
    Route::delete('/unitDelete/{id}', [UnitController::class, 'unitDelete']);

    
});