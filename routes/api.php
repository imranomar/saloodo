<?php

use Illuminate\Http\Request;
use \App\Product;
use App\User;

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

Route::resource('product','ProductController');
Route::resource('order','OrderController');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/customer/{id}/orders', function ($id) {
    //return User::with('orders','orders.products')->paginate(10)->findOrFail(1);
    return User::where('id',$id)->findOrFail($id)->orders()->with('products')->paginate(2);
});

Route::post('/signup', "Auth\RegisterController@register");
