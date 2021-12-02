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

Route::middleware('auth:api')->get('/hrapi', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api'], 'prefix' => '/hrapi'], function() {
    Route::post('/staff', 'UserController@staffProfile');
    Route::post('/staff/list', 'UserController@ListStaffProfile');
    
    Route::post('company/branch-position-list', 'CompanyController@branchAndPositionList');

});