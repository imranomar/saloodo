<?php

use Illuminate\Http\Request;
use \App\Product;
use App\User;
use App\Cts;

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

//all products routes
Route::resource('product','ProductController');

//all order routes
Route::resource('order','OrderController');

//user details
Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});

//view all orders of a particular customer - cab be factorized to a seperate controller with more customer functions
Route::post('/customer/{id}/orders', 'CustomerController@getOrdersOfCustomer');
Route::post('/customer/{id}', 'CustomerController@getCustomerDetails');

//signup
Route::post('/signup', "Auth\RegisterController@register");
