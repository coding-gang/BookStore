<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthorController;

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




Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');



Auth::routes();

Route::group(['middleware' => ['auth','role:Superuser|Administrator|Manager|Accounting|Salesman|Staff|Inventory officer|HR']],function () {

    Route::resource('admin',Controllers\AdminController::class);
});

Route::group(['middleware' => ['auth','role:Administrator|Manager|Salesman|Staff']],function () {

    Route::resource('category',Controllers\CategoryController::class);
    Route::resource('author',AuthorController::class);
    Route::resource('publisher',Controllers\PublisherController::class);
    Route::resource('book',BookController::class);

    Route::post('/book/site/{id}/file-delete',[BookController::class, 'deleteImage'])->name('book.deleteImage');
    Route::get('/book/{id}/discount',[BookController::class, 'discountBook'])->name('book.discount');
    Route::post('/book/{id}/discount/update',[BookController::class, 'updateDiscountBook'])->name('book.UpdateDiscount');


});
Route::group(['middleware' => ['auth','role:Administrator|Manager']],function () {
    Route::resource('user',UserController::class);

});

Route::group(['middleware' => ['auth','role:Administrator']],function () {
    Route::resource('role',RoleController::class);
    Route::resource('permission',PermissionController::class);
    Route::get('/user/{user}/role',[UserController::class, 'editRole'])->name('user.role');
    Route::post('/user/{user}/addRole',[UserController::class, 'addRole'])->name('user.addRole');

});
Route::group(['middleware' => ['auth','role:Superuser|Administrator|Manager|Accounting|Salesman|Inventory officer']],function () {
    Route::resource('order',OrderController::class);
    Route::delete('/order/{id}/customer/{idCustomer}/delete',[OrderController::class, 'orderDelete'])->name('order.orderDelete');
    Route::get('/order/{id}/customer/{idCustomer}/show',[OrderController::class, 'orderShow'])->name('order.orderShow');
});

