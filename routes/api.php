<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PositionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// // Lấy danh sách sản phẩm
// Route::get('positions', 'Api\ProductController@index')->name('positions.index');

// // Lấy thông tin sản phẩm theo id
// Route::get('positions/{id}', 'Api\ProductController@show')->name('positions.show');

// // Thêm sản phẩm mới
// Route::post('positions', 'Api\ProductController@store')->name('positions.store');

// // Cập nhật thông tin sản phẩm theo id
// # Sử dụng put nếu cập nhật toàn bộ các trường
// Route::put('positions/{id}', 'Api\ProductController@update')->name('positions.update');
// # Sử dụng patch nếu cập nhật 1 vài trường
// Route::patch('positions/{id}', 'Api\ProductController@update')->name('positions.update');

// // Xóa sản phẩm theo id
// Route::delete('positions/{id}', 'Api\ProductController@destroy')->name('positions.destroy');

Route::apiResource('positions', PositionController::class);

