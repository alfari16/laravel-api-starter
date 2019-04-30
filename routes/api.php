<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::get('/', function(){
        return response()->json([
            'message' => 'Welcome to api',
            'status_code' => 200
        ], 200);
    });

    Route::post('login', 'AuthenticateController@authenticate');

    Route::get('transactions.json', 'TransactionController@index');
    Route::get('transactions/{id}.json', 'TransactionController@show');
    Route::post('transactions/create', 'TransactionController@store');
    Route::put('transactions/update/{id}', 'TransactionController@update');
    Route::delete('transactions/delete/{id}', 'TransactionController@destroy');

    Route::get('products.json', 'ProductController@index');
    Route::get('products/{id}.json', 'ProductController@show');
    Route::post('products/create', 'ProductController@store');
    Route::put('products/update/{id}', 'ProductController@update');
    Route::delete('products/delete/{id}', 'ProductController@destroy');
    
    Route::get('customers.json', 'CustomerController@index');
    Route::get('customers/{id}.json', 'CustomerController@show');
    Route::post('customers/create', 'CustomerController@store');
    Route::put('customers/update/{id}', 'CustomerController@update');
    Route::delete('customers/delete/{id}', 'CustomerController@destroy');
});