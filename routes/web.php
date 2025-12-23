<?php

use App\Http\Controllers\GoogleController;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('sukses');

// Route::get('/test/google/{employee}', function (Employee $employee) {
//     return redirect()->route('google.connect', $employee->id);
// });

// Route::get('/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
// Route::get('/google/callback', [GoogleController::class, 'callback']);

Route::get('/google/connect/{employee}', [GoogleController::class, 'redirect'])
    ->name('google.connect');

Route::get('/google/callback/', [GoogleController::class, 'callback'])
    ->name('google.callback');
