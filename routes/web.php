<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\archiveController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\invoicesarchiveController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesAttachmentController;


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
    return view('auth.login');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified','user_status'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('invoices',InvoicesController::class);
    Route::delete('invoices.force_delete',[InvoicesController::class,'force_delete'])
    ->name('force_delete');
    Route::resource('sections',SectionsController::class);
    Route::resource('products',ProductController::class);
    Route::resource('InvoiceAttachments',InvoicesAttachmentController::class);
    Route::get('/section/{id}',[InvoicesController::class,'getproducts']);
    Route::get('InvoicesDetails/{id}',[InvoicesDetailsController::class,'showDetails'])
    ->name('showDetails');

    Route::get('download/{invoice_number}/{file_name}',
    [InvoicesDetailsController::class,'get_file'])
    ->name('get_file');

    Route::get('view_file/{invoice_number}/{file_name}',
    [InvoicesDetailsController::class,'view_file'])
    ->name('view_file');

    Route::post('delete_file',[InvoicesDetailsController::class,'destroy'])
    ->name('delete_file');

    Route::get('edit_invoice/{id}',[InvoicesController::class,'edit']);

    Route::get('Status_show/{id}',[InvoicesController::class,'show'])
    ->name('Status_show');

    Route::post('Status_update/{id}',[InvoicesController::class,'Status_update'])
    ->name('Status_update');


    Route::get('invoices_paid',[InvoicesController::class,'invoices_paid'])
    ->name('invoices_paid');

    Route::get('invoices_unpaid',[InvoicesController::class,'invoices_unpaid'])
    ->name('invoices_unpaid');

    Route::get('invoices_patial_paid',[InvoicesController::class,'invoices_patial_paid'])
    ->name('invoices_patial_paid');

    Route::get('archive',[invoicesarchiveController::class,'index'])
    ->name('archive');

    Route::delete('archive_invoice',[invoicesarchiveController::class,'archive_invoice'])
    ->name('archive_invoice');

    Route::post('restore_archive_invoice',[invoicesarchiveController::class,'restore_archive_invoice'])
    ->name('restore_archive_invoice');

    Route::delete('destroy_arc',[invoicesarchiveController::class,'destroy'])
    ->name('destroy_arc');

    Route::resource('invoicesarchive',invoicesarchiveController::class);

    Route::get('print_invoice/{id}',[InvoicesController::class,'print_invoice']);


    Route::group(['middleware' => ['auth']], function() {
        Route::resource('roles',RoleController::class);
        Route::resource('users',UserController::class);
        });

});



require __DIR__.'/auth.php';

Route::get('/{page}', [AdminController::class,'index']);
