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

Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
Route::get('/products/category/{id}', [App\Http\Controllers\ProductController::class, 'findCategory']);
Route::get('/products/details/{id}', [App\Http\Controllers\ProductController::class, 'details']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/',function(){  
    return redirect('/products');    //logout แล้วให้วิ่งไปหน้าแรก
});
Auth::routes();

Route::middleware(['auth','verifyIsAdmin'])->group(function(){    //มีการ login account และเป็น admin
    //category
    Route::get('admin/createCategory', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
    Route::post('admin/createCategory', [App\Http\Controllers\Admin\CategoryController::class, 'store']);
    Route::get('admin/editCategory/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'edit']);
    Route::post('admin/updateCategory/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update']);
    Route::get('admin/deleteCategory/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'delete']);
    //Product
    Route::get('admin/createProduct', [App\Http\Controllers\Admin\ProductController::class, 'create']);
    Route::post('admin/createProduct', [App\Http\Controllers\Admin\ProductController::class, 'store']);
    Route::get('admin/dashboard', [App\Http\Controllers\Admin\ProductController::class, 'index']);
    Route::get('admin/editProduct/{id}', [App\Http\Controllers\Admin\ProductController::class, 'edit']);
    Route::get('admin/editProductImage/{id}', [App\Http\Controllers\Admin\ProductController::class, 'editImage']);
    Route::post('admin/updateProduct/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update']);
    Route::post('admin/updateProductImage/{id}', [App\Http\Controllers\Admin\ProductController::class, 'updateImage']);
    Route::get('admin/deleteProduct/{id}', [App\Http\Controllers\Admin\ProductController::class, 'delete']);
    
    //Order 
    Route::get('/admin/orders', [App\Http\Controllers\Admin\OrderController::class, 'orderPanel']);
    Route::get('/admin/orders/detail/{id}', [App\Http\Controllers\Admin\OrderController::class, 'showOrderDetail']);

    //users
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'showUsers']);
});      

//FrontEnd
Route::middleware(['auth'])->group(function(){    //มีการ login account
    //Add to Cart
    Route::get('/products/addToCart/{id}', [App\Http\Controllers\ProductController::class, 'addProductToCart']);
    Route::get('/products/cart', [App\Http\Controllers\ProductController::class, 'showCart']);
    Route::get('/products/cart/deleteFromCart/{id}', [App\Http\Controllers\ProductController::class, 'deleteFromCart']);
    Route::get('/products/cart/incrementCart/{id}', [App\Http\Controllers\ProductController::class, 'incrementCart']);
    Route::get('/products/cart/decrementCart/{id}', [App\Http\Controllers\ProductController::class, 'decrementCart']);
    Route::post('/products/addQuantityToCart', [App\Http\Controllers\ProductController::class, 'addQuantityToCart']);

    //Create Orders
    Route::get('/products/checkout', [App\Http\Controllers\ProductController::class, 'checkout']);
    Route::post('/products/createOrder', [App\Http\Controllers\ProductController::class, 'createOrder']);
    Route::get('/products/showPayment', [App\Http\Controllers\ProductController::class, 'showPayment']);
    Route::get('/products/showThankyou', [App\Http\Controllers\ProductController::class, 'showThankyou']);

});

Route::get('/products/search', [App\Http\Controllers\ProductController::class, 'searchProduct']);
Route::get('/products/priceRange', [App\Http\Controllers\ProductController::class, 'searchProductPrice']);