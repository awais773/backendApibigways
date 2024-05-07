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
Route::get('/realtime-location', [App\Http\Controllers\api\VehicleController::class, 'getRealtimeLocation']);

//registration
Route::apiResource('registration', App\Http\Controllers\api\RegistrationController::class);
Route::post('/registrationUpdate/{id}',[App\Http\Controllers\api\RegistrationController::class,'update']);
Route::post('/updateReguest/{id}',[App\Http\Controllers\api\RegistrationController::class,'updateReguest']);
Route::get('/approved',[App\Http\Controllers\api\RegistrationController::class,'approved']);


/////////////////////////////////////////////


//vehicle
Route::post('/vehicleUpdate/{id}',[App\Http\Controllers\api\VehicleController::class,'update']);
Route::get('/vehicleIndex/{type}',[App\Http\Controllers\api\VehicleController::class,'vehicleIndex']);
Route::get('/notAssign',[App\Http\Controllers\api\VehicleController::class,'notAssign']);


// student
Route::get('/studentGet/{id}',[App\Http\Controllers\api\StudentController::class,'studentGet']);
Route::get('/studentShow/{id}',[App\Http\Controllers\api\StudentController::class,'show']);
Route::delete('/destroyStudent/{id}',[App\Http\Controllers\api\StudentController::class,'destroy']);
Route::post('/studentadd',[App\Http\Controllers\api\StudentController::class,'store']);
Route::post('/studentUpdated/{id}',[App\Http\Controllers\api\StudentController::class,'update']);
////  StudentAttendance
Route::post('/studentAttenStore',[App\Http\Controllers\api\AttendanceController::class,'studentAttenStore']);
Route::get('/studentAttenShow/{id}',[App\Http\Controllers\api\AttendanceController::class,'studentAttenShow']);
Route::get('/studentAttendance',[App\Http\Controllers\api\AttendanceController::class,'studentAttendance']);

///Driver
Route::apiResource('drivers', App\Http\Controllers\api\DriverController::class);
// Route::apiResource('DriverAttendance', App\Http\Controllers\api\AttendanceController::class);
Route::post('driver/login',[App\Http\Controllers\api\DriverController::class, 'driverLogin'])->name('driver.login');
Route::get('/DriverAttendance/{id}',[App\Http\Controllers\api\AttendanceController::class,'Show']);
Route::get('/DriverAttendance',[App\Http\Controllers\api\AttendanceController::class,'index']);
////  DriverAttendance
Route::group( ['middleware' => ['auth:driver-api'] ],function(){
    Route::post('DriverAttendance', App\Http\Controllers\api\AttendanceController::class, 'store');
    Route::post('/driversUpdate/{id}',[App\Http\Controllers\api\DriverController::class,'update']);
});

/// CareTaker
Route::apiResource('careTaker', App\Http\Controllers\api\CareTakerController::class);
Route::post('caretaker/login',[App\Http\Controllers\api\CareTakerController::class, 'caretakerLogin'])->name('caretaker.login');
Route::get('/careTakerAttenShow/{id}',[App\Http\Controllers\api\AttendanceController::class,'careTakerAttenShow']);
Route::get('/careTakerAttendance',[App\Http\Controllers\api\AttendanceController::class,'careTakerAttendance']);
/// CareTakerAttendance
Route::group( ['middleware' => ['auth:caretaker-api'] ],function(){
    Route::post('/careTakerAttenStore',[App\Http\Controllers\api\AttendanceController::class,'careTakerAttenStore']);
    Route::post('/careTakerUpdate/{id}',[App\Http\Controllers\api\CareTakerController::class,'update']);
});




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
