<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('register',[App\Http\Controllers\api\AuthController::class,'register']);
Route::post('login',[App\Http\Controllers\api\AuthController::class,'login']);
Route::post('tokenGet',[App\Http\Controllers\api\AuthController::class,'tokenGet']);


Route::post('/forgotPassword', [App\Http\Controllers\api\AuthenticateController::class, 'forgotPassword']);
Route::post('/updatePassword', [App\Http\Controllers\api\AuthenticateController::class, 'updatePassword']);
Route::post('/otp/verify', [App\Http\Controllers\api\AuthenticateController::class, 'otpVerification']);
Route::post('/resendEmail',[App\Http\Controllers\api\AuthenticateController::class,'resendEmail']);
Route::get('/UserGet',[App\Http\Controllers\api\AuthenticateController::class,'index']);
Route::get('/UserGet/{id}',[App\Http\Controllers\api\AuthenticateController::class,'show']);
Route::apiResource('vehicle', App\Http\Controllers\api\VehicleController::class);

//registration
Route::apiResource('registration', App\Http\Controllers\api\RegistrationController::class);
Route::post('/registrationUpdate/{id}',[App\Http\Controllers\api\StudentController::class,'update']);
Route::post('/updateReguest/{id}',[App\Http\Controllers\api\RegistrationController::class,'updateReguest']);
  

/////////////////////////////////////////////

//vehicle
Route::post('/vehicleUpdate/{id}',[App\Http\Controllers\api\VehicleController::class,'update']);
Route::get('/vehicleIndex/{type}',[App\Http\Controllers\api\VehicleController::class,'vehicleIndex']);
Route::get('/notAssign',[App\Http\Controllers\api\VehicleController::class,'notAssign']);


// student
Route::get('/studentGet/{id}',[App\Http\Controllers\api\StudentController::class,'studentGet']);
Route::delete('/destroyStudent/{id}',[App\Http\Controllers\api\StudentController::class,'destroy']);

///Driver
Route::apiResource('drivers', App\Http\Controllers\api\DriverController::class);
Route::post('/driversUpdate/{id}',[App\Http\Controllers\api\DriverController::class,'update']);

Route::post('/driverLogin',[App\Http\Controllers\api\DriverController::class,'driverLogin']);



/// CareTaker
Route::apiResource('careTaker', App\Http\Controllers\api\CareTakerController::class);
Route::post('/careTakerUpdate/{id}',[App\Http\Controllers\api\CareTakerController::class,'update']);

Route::middleware('auth:api')->group( function () {
    // Route::resource('products', ProductController::class);
    Route::post('/update/profile', [App\Http\Controllers\api\AuthenticateController::class, 'updateProfile']);
    Route::post('/PasswordChanged ', [App\Http\Controllers\api\AuthenticateController::class, 'PasswordChanged']);
    Route::Get('/bigwaysData',[App\Http\Controllers\api\RegistrationController::class,'reguestedDataGet']);
    Route::apiResource('student', App\Http\Controllers\api\StudentController::class);
    Route::post('/studentUpdate/{id}',[App\Http\Controllers\api\StudentController::class,'update']);
    Route::Get('/parentGet',[App\Http\Controllers\api\RegistrationController::class,'parentGet']);

    Route::get('/getAlll',[App\Http\Controllers\api\DriverController::class,'index']);


});