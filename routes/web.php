<?php

use App\Http\Controllers\GoogleController;
use App\Models\Employee;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostWbbmController;
use App\Http\Controllers\GetWbbmController;
use App\Models\Categories;
use App\Http\Controllers\GuestBookController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GoogleAccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\ReminderLogController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Auth;

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


Route::get('/google/connect/{employee}', [GoogleController::class, 'redirect'])
    ->name('google.connect');

Route::get('/google/callback/', [GoogleController::class, 'callback'])
    ->name('google.callback');

Route::middleware(['auth', 'google.connected'])->group(function () {
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});




Auth::routes();



Route::middleware('auth', 'ChekRole:test')->group(function () {});


Route::middleware('auth', 'ChekRole:superadmin')->group(function () {
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    //route wbbm
    Route::get('/wbbm/create', [PostWbbmController::class, 'createWbbm'])->name("wbbm-create");
    Route::post('/postcategories', [PostWbbmController::class, 'storeCategories'])->name("categories-store");
    Route::post('/postSubcategories', [PostWbbmController::class, 'storeSubCategories'])->name("subcategories-store");
    Route::post('/postItems', [PostWbbmController::class, 'storeItem'])->name("items-store");
    Route::post('/postDocumentItems', [PostWbbmController::class, 'storeItemDocument'])->name("documentItem-store");

    Route::get('/wbbm/monitor', [GetWbbmController::class, 'monitorWbbm'])->name("wbbm-monitor");
    Route::get('/wbbm/data-pencapaian', [GetWbbmController::class, 'dataCapaian'])->name("wbbm-data");
    Route::post('/upload', [GetWbbmController::class, 'storeDok'])->name('upload.store');
    Route::delete('/category/delete/{id}', [GetWbbmController::class, 'destroyCategory'])->name("category-delete");
    Route::delete('/subcategory/delete/{id}', [GetWbbmController::class, 'destroySubCategory'])->name("subcategory-delete");
    Route::delete('/item/delete/{id}', [GetWbbmController::class, 'destroyItem'])->name("item-delete");
    Route::delete('/document/delete/{id}', [GetWbbmController::class, 'destroyDocument'])->name("document-delete");
    Route::get('/wbbm/cek-progress', [GetWbbmController::class, 'tesProgress'])->name("wbbm-tes-progres");
    Route::get('/kategori/{id}/progress', function ($id) {
        $kategori = Categories::with('sub_categories.items.item_documents.upload')->findOrFail($id);

        return response()->json([
            'progress' => $kategori->progress()
        ]);
    });

    //route buku tamu
    Route::get('/guest_book/index', [GuestBookController::class, 'index'])->name('guest_book_index');
    Route::get('/guest_book/create', [GuestBookController::class, 'create'])->name('guest_book_create');
    Route::post('/guest_book', [GuestBookController::class, 'store'])->name('guest_book_store');
    Route::get('/guest_book/{id}/edit', [GuestBookController::class, 'edit'])->name('guest_book_edit');
    Route::put('/guest_book/{id}', [GuestBookController::class, 'update'])->name('guest_book_update');
    Route::delete('/guest_book/{id}', [GuestBookController::class, 'destroy'])->name('guest_book_destroy');
    Route::get('/guest-book/print/pdf', [GuestBookController::class, 'printPdf'])
        ->name('guest_book_print_pdf');

    //data google Account
    Route::get('/google-accounts', [GoogleAccountController::class, 'index'])->name('google-accounts.index');
    Route::delete('/google-accounts/{googleAccount}', [GoogleAccountController::class, 'destroy'])->name('google-accounts.destroy');

    //data work schedule
    Route::get('/work-schedules', [WorkScheduleController::class, 'index'])->name('work-schedules.index');
    Route::get('/work-schedules/create', [WorkScheduleController::class, 'create'])->name('work-schedules.create');
    Route::post('/work-schedules', [WorkScheduleController::class, 'store'])->name('work-schedules.store');
    Route::get('/work-schedules/{workSchedule}/edit', [WorkScheduleController::class, 'edit'])->name('work-schedules.edit');
    Route::put('/work-schedules/{workSchedule}', [WorkScheduleController::class, 'update'])->name('work-schedules.update');
    Route::delete('/work-schedules/{workSchedule}', [WorkScheduleController::class, 'destroy'])->name('work-schedules.destroy');

    Route::delete('/reminder-logs/truncate', [ReminderLogController::class, 'truncate'])->name('reminder-logs.truncate');
    Route::get('/reminder-logs', [ReminderLogController::class, 'index'])->name('reminder-logs.index');
    Route::delete('/reminder-logs/{reminderLog}', [ReminderLogController::class, 'destroy'])->name('reminder-logs.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
