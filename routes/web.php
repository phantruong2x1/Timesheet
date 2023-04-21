<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;
use App\Console\Commands;
use App\Http\Controllers\Admin\StaffsController;
use App\Http\Controllers\Admin\TimesheetController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\RequestDetailController;
use App\Http\Controllers\Admin\StatisticalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\PayrollCostController;
use App\Models\Staffs;
use App\Models\Timesheet;
use Illuminate\Console\Command;
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
            Route::get('/history',[Admin\HistoryController::class, 'index'])->name('historis.index');
            
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

            //User
            Route::prefix('user')->name('users.')->group(function(){
                Route::get('/',[UserController::class,'index'])->name('index');

                Route::get('/add',[UserController::class,'getAdd'])->name('add');

                Route::post('/add',[UserController::class,'postAdd'])->name('post-add');

                Route::get('/edit/{id}',[UserController::class,'getEdit'])->name('edit');

                Route::post('/update',[UserController::class,'postEdit'])->name('post-edit');
                
                Route::get('/delete/{id}',[UserController::class,'delete'])->name('delete');
            });

            //Get and post data timesheet
            Route::get('/get-time',[Admin\TimesheetController::class, 'getDataTimesheet'])->name('timesheets.get-time');
            // Route::post('/get-timesheet',[Commands\GetCurl::class, 'handle'])->name('timesheets.post-time');
            Route::post('/get-time',[Admin\TimesheetController::class, 'postDataTimesheet'])->name('timesheets.post-time');

            //Statistical
            Route::prefix('statistical')->name('statisticals.')->group(function(){
                Route::get('/',[StatisticalController::class,'index'])->name('index');

                Route::get('/add',[StatisticalController::class,'getAdd'])->name('add');

                Route::post('/add',[StatisticalController::class,'postAdd'])->name('post-add');

                Route::get('/edit/{id}',[StatisticalController::class,'getEdit'])->name('edit');

                Route::post('/update',[StatisticalController::class,'postEdit'])->name('post-edit');
                
                Route::get('/delete/{id}',[StatisticalController::class,'delete'])->name('delete');
            });

            //Timesheet
            Route::prefix('timesheet')->name('timesheets.')->group(function(){
                Route::get('/show',[TimesheetController::class,'show'])->name('show');

                Route::get('/create',[TimesheetController::class,'create'])->name('create');

                Route::post('/store',[TimesheetController::class,'store'])->name('store');

                Route::get('/edit/{id}',[TimesheetController::class,'edit'])->name('edit');

                Route::post('/update/{id}',[TimesheetController::class,'update'])->name('update');
                
                Route::get('/destroy/{id}',[TimesheetController::class,'destroy'])->name('destroy');
            });

            //Request detail
            Route::prefix('request')->name('requests.')->group(function(){
                Route::get('/',[RequestDetailController::class,'index'])->name('index');

                Route::get('/update-accept/{id}',[RequestDetailController::class,'updateAccept'])->name('accept');
                
                Route::get('/update-denied/{id}',[RequestDetailController::class,'updateDenied'])->name('denied');

            });
            //Feedback
            Route::prefix('feedback')->name('feedbacks.')->group(function(){
                Route::get('/',[FeedbackController::class,'index'])->name('index');

            });

            //Payroll Cost
            Route::prefix('payroll-cost')->name('payroll-costs.')->group(function(){
                Route::get('/',[PayrollCostController::class,'index'])->name('index');

                Route::get('/show',[PayrollCostController::class,'show'])->name('show');

                Route::get('/create',[PayrollCostController::class,'create'])->name('create');

                Route::post('/store',[PayrollCostController::class,'store'])->name('store');

                Route::get('/edit/{id}',[PayrollCostController::class,'edit'])->name('edit');

                Route::post('/update',[PayrollCostController::class,'update'])->name('update');
                
                Route::get('/destroy/{id}',[PayrollCostController::class,'destroy'])->name('destroy');
            });

        });

        Route::middleware('client')->prefix('client')->group(function(){

            //Dashboard client
            Route::get('/dashboard',[Client\ClientController::class, 'index'])->name('client-dashboard');

            //statistical
            Route::get('/statistical',[Client\StatisticalController::class, 'index'])->name('client.statisticals');

            //option
            Route::get('/forget/{id}', [Client\RequestDetailController::class, 'forget'])->name('option-forget');
            Route::post('/postForget', [Client\RequestDetailController::class, 'postForget'])->name('option-post-forget');

            Route::get('/please-be-late/{date}', [Client\RequestDetailController::class, 'beLate'])->name('option-please-be-late');
            Route::post('/please-be-late', [Client\RequestDetailController::class, 'postBeLate'])->name('option-post-please-be-late');

            Route::get('/please-come-back-soon/{date}', [Client\RequestDetailController::class, 'comeBackSoon'])->name('option-please-come-back-soon');
            Route::post('/please-come-back-soon', [Client\RequestDetailController::class, 'postComeBackSoon'])->name('option-post-please-come-back-soon');

            Route::get('/take-a-break/{date}', [Client\RequestDetailController::class, 'takeABreak'])->name('option-take-a-break');
            Route::post('/take-a-break', [Client\RequestDetailController::class, 'postTakeABreak'])->name('option-post-take-a-break');
            
            Route::get('/make-order/{id}/{date}', [Client\RequestDetailController::class, 'makeOrder'])->name('option-make-order');

            //request 
            Route::get('/request-index', [Client\RequestDetailController::class, 'index'])->name('client.requests.index');
            Route::get('/request-destroy/{id}',[Client\RequestDetailController::class,'destroy'])->name('client.requests.destroy');

            //settings
            Route::get('/setting-user', [Client\SettingController::class, 'getStaff'])->name('client.settings.getStaff');
            Route::post('/setting-user-update', [Client\SettingController::class, 'updateStaff'])->name('client.settings.updateStaff');

            Route::get('/setting-change-password', [Client\SettingController::class, 'changePassword'])->name('client.settings.change-password');
            Route::post('/setting-update-passoword', [Client\SettingController::class, 'updatePassword'])->name('client.settings.update-password');
            
            Route::post('/setting-feekback', [Client\SettingController::class, 'createFeedback'])->name('client.settings.post-feekback');

             // check on and out
             Route::get('/check-in-out', [Client\ClientController::class, 'checkInAndOut'])->name('client.check-in-out');

            //other
            Route::get('/get-statistical-detail/{time}',[Client\StatisticalController::class, 'getStatisticalDetail'])->name('client.get-statistical-detail');

        });
    });
});

Auth::routes();

Route::get('/',[HomeController::class, 'index']);

Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::view('permission-denied', 'errors.permission-denied')->name('denied');
Route::view('account-disabled', 'errors.account-disabled')->name('disabled');
Route::view('account-not-found', 'errors.account-not-found')->name('notfound');

//current time
Route::get('/current-time', function () {
    return response()->json(['time' => Carbon\Carbon::now()]);
});










