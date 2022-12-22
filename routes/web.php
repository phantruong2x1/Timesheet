<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\StaffsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\PositionController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;


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

//Change language
Route::get('setLocale/{locale}', function ($locale) {
    if (in_array($locale, Config::get('app.locales'))) {
      Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('app.setLocale');


Route::middleware('auth')->group(function(){

    Route::middleware('checkstatus')->group(function(){

        Route::middleware('admin')->prefix('admin')->group(function(){

            //Dashboard admin
            Route::get('/dashboard',[Admin\AdminController::class, 'index'])->name('admin-dashboard');

            //History
            Route::get('/history',[Admin\HistoryController::class, 'index'])->name('history.index');
            
            //Staff
            Route::prefix('/staff')->name('staff.')->group(function(){
                Route::get('/',[StaffsController::class,'index'])->name('index');

                Route::get('/add',[StaffsController::class,'getAdd'])->name('add');

                Route::post('/add',[StaffsController::class,'postAdd'])->name('post-add');

                Route::get('/edit/{id}',[StaffsController::class,'getEdit'])->name('edit');

                Route::post('/update',[StaffsController::class,'postEdit'])->name('post-edit');
                
                Route::get('/delete/{id}',[StaffsController::class,'delete'])->name('delete');
            });

            //Department
            Route::prefix('department')->name('departments.')->group(function(){
                Route::get('/',[DepartmentController::class,'index'])->name('index');

                Route::get('/add',[DepartmentController::class,'getAdd'])->name('add');

                Route::post('/add',[DepartmentController::class,'postAdd'])->name('post-add');

                Route::get('/edit/{id}',[DepartmentController::class,'getEdit'])->name('edit');

                Route::post('/update',[DepartmentController::class,'postEdit'])->name('post-edit');
                
                Route::get('/delete/{id}',[DepartmentController::class,'delete'])->name('delete');
            });

            //Position
            Route::prefix('position')->name('positions.')->group(function(){
                Route::get('/',[PositionController::class,'index'])->name('index');

                Route::get('/add',[PositionController::class,'getAdd'])->name('add');

                Route::post('/add',[PositionController::class,'postAdd'])->name('post-add');

                Route::get('/edit/{id}',[PositionController::class,'getEdit'])->name('edit');

                Route::post('/update',[PositionController::class,'postEdit'])->name('post-edit');
                
                Route::get('/delete/{id}',[PositionController::class,'delete'])->name('delete');
            });

        });

        Route::middleware('client')->prefix('client')->group(function(){

            //Dashboard client
            Route::get('/dashboard',[Client\ClientController::class, 'index'])->name('client-dashboard');
            
        });
    });
});

Auth::routes();

//Route::get('/test',[HomeController::class, 'index']);

Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::view('permission-denied', 'errors.permission-denied')->name('denied');
Route::view('account-disabled', 'errors.account-disabled')->name('disabled');
Route::view('account-not-found', 'errors.account-not-found')->name('notfound');





