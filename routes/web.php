<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\OtpController;

//Route::get('/login', [OtpController::class, 'showLoginForm'])->middleware('throttle:2,1')->name('login');
Route::get('/login', [OtpController::class, 'showLoginForm'])->name('login');
Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/verify-otp', [OtpController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.check');

Route::get('/home', function () {
    return 'You are logged in!';
})->middleware('auth')->name('home');
