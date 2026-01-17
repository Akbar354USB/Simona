<?php

use App\Http\Controllers\AdditionalLeaveController;
use App\Http\Controllers\AdditionalLeaveRequestController;
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
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AttendaceController;
use App\Http\Controllers\WorkShiftController;
use App\Http\Controllers\WorkUnitController;
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
Route::get('/tamu', [HomeController::class, 'tamu'])
    ->name('redirect.tamu');

Route::middleware(['auth'])->group(function () {
    Route::get('/tamu', [HomeController::class, 'tamu'])
        ->name('redirect.tamu');
});

Route::middleware(['auth', 'ChekRole:superadmin', 'google.connected'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])
        ->name('home');
});

Route::middleware('auth', 'ChekRole:superadmin,ppnpn')->group(function () {
    Route::get('/attendance', [AttendaceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/store', [AttendaceController::class, 'store'])->name('attendance.store');
});

Route::middleware('auth', 'ChekRole:superadmin')->group(function () {
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
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

    //satker 
    Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/create', [AgencyController::class, 'create'])->name('agencies.create');
    Route::post('/agencies', [AgencyController::class, 'store'])->name('agencies.store');
    Route::get('/agencies/{agency}/edit', [AgencyController::class, 'edit'])->name('agencies.edit');
    Route::put('/agencies/{agency}', [AgencyController::class, 'update'])->name('agencies.update');
    Route::delete('/agencies/{agency}', [AgencyController::class, 'destroy'])->name('agencies.destroy');

    //absensi PPNPN
    Route::get('/attendances-data', [AttendaceController::class, 'dataindex'])->name('attendances.data');
    Route::delete('/attendances/{attendance}', [AttendaceController::class, 'destroy'])->name('attendances.destroy');
    Route::delete('/attendances-delete-all', [AttendaceController::class, 'destroyAll'])->name('attendances.destroyAll');
    Route::get('/attendances-print-pdf', [AttendaceController::class, 'printPdf'])->name('attendances.printPdf');

    Route::get('/work-shifts', [WorkShiftController::class, 'index'])->name('work-shifts.index');
    Route::get('/work-shifts/create', [WorkShiftController::class, 'create'])->name('work-shifts.create');
    Route::post('/work-shifts', [WorkShiftController::class, 'store'])->name('work-shifts.store');
    Route::get('/work-shifts/{workShift}/edit', [WorkShiftController::class, 'edit'])->name('work-shifts.edit');
    Route::put('/work-shifts/{workShift}', [WorkShiftController::class, 'update'])->name('work-shifts.update');
    Route::delete('/work-shifts/{workShift}', [WorkShiftController::class, 'destroy'])->name('work-shifts.destroy');



    /*Additional Leave Request Routes*/

    // 1. Tampilkan semua data
    Route::get(
        '/additional-leave-requests',
        [AdditionalLeaveRequestController::class, 'index']
    )->name('additional-leave-requests.index');
    Route::get(
        '/additional-leave-requests/create',
        [AdditionalLeaveRequestController::class, 'create']
    )->name('additional-leave-requests.create');
    Route::post(
        '/additional-leave-requests',
        [AdditionalLeaveRequestController::class, 'store']
    )->name('additional-leave-requests.store');
    Route::get('/additional-leave-requests/{additionalLeaveRequest}', [AdditionalLeaveRequestController::class, 'show'])->name('additional-leave-requests.show');
    Route::get('/additional-leave-requests/{additionalLeaveRequest}/edit', [AdditionalLeaveRequestController::class, 'edit'])->name('additional-leave-requests.edit');
    Route::put('/additional-leave-requests/{additionalLeaveRequest}', [AdditionalLeaveRequestController::class, 'update'])->name('additional-leave-requests.update');
    Route::delete('/additional-leave-requests/{additionalLeaveRequest}', [AdditionalLeaveRequestController::class, 'destroy'])->name('additional-leave-requests.destroy');

    /*Additional Leaves Routes*/
    Route::get('/additional-leaves', [AdditionalLeaveController::class, 'index'])->name('additional-leaves.index');
    Route::get('/additional-leaves/create', [AdditionalLeaveController::class, 'create'])->name('additional-leaves.create');
    Route::post('/additional-leaves', [AdditionalLeaveController::class, 'store'])->name('additional-leaves.store');
    Route::delete('/additional-leaves/{additionalLeave}', [AdditionalLeaveController::class, 'destroy'])->name('additional-leaves.destroy');
    Route::get('additional-leave-requests/{additionalLeaveRequest}/print', [AdditionalLeaveRequestController::class, 'print'])->name('additional-leave-requests.print');

    //data unit kerja
    Route::get('/work-units', [WorkUnitController::class, 'index'])->name('work-units.index');
    Route::get('/work-units/create', [WorkUnitController::class, 'create'])->name('work-units.create');
    Route::post('/work-units', [WorkUnitController::class, 'store'])->name('work-units.store');
    Route::delete('/work-units/{id}', [WorkUnitController::class, 'destroy'])->name('work-units.destroy');
});


Auth::routes();
