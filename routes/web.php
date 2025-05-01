<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/sensor-data', [SensorController::class, 'getAllSensorData'])->name('sensor.data');

// Route::get('/devices', [DeviceController::class, 'devices'])->name('devices');
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
// Route::get('/devices', [DeviceController::class, 'showAllDevices'])->name('devices.devices'); // Tambahkan jika belum ada
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::get('/devices/by-room', [App\Http\Controllers\Api\DeviceController::class, 'getDevicesByRoom']);
Route::post('/devices/generate-token', [DeviceController::class, 'generateToken'])->name('devices.generateToken');


Route::resource('devices', DeviceController::class);

Route::get('/users', fn () => view('users', ['user' => Auth::user()]))->name('users')->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
    Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users_data', [UsersController::class, 'index2'])->name('users_data.index2');
});
Route::put('/users/{id}/update-role', [UsersController::class, 'updateRole'])->name('users.updateRole');

Route::view('/contact', 'contact');

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/login', fn () => view('auth.login'))->name('login');
route::post('/login', [AuthController::class, 'login']);

Route::get('/register', fn () => view('auth.register'))->name('register');
route::post('/register', [AuthController::class, 'register']);

Route::get('/', [DashboardController::class, 'index']);
Route::get('/logout', [AuthController::class, 'logout']);

// Route::get('/', function () {
//     return view('dashboard');
// })->middleware('auth');

// Route::middleware('guest')->group(function () {
//     Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('login', [AuthenticatedSessionController::class, 'store']);
//     Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
//     Route::post('register', [RegisteredUserController::class, 'store']);
//     Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
//     Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
//     Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
//     Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
// });

// Route::middleware('auth')->group(function () {
//     Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
//     Route::get('verify-email', [EmailVerificationNotificationController::class, 'create'])->name('verification.notice');
//     Route::post('verify-email', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
//     Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
//     Route::get('/dashboard/{device}/{type}', [DashboardController::class, 'type'])->name('dashboard.type');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//     Route::resource('devices', DevicesController::class)->withTrashed();
//     Route::resource('receivers', ReceiversController::class)->withTrashed();
// });

// require __DIR__.'/auth.php';
