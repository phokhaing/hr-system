<?php

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

//Controller for migrate pension fund info
Route::group(['middleware' => ['auth'], 'prefix' => 'pensionfund-migration'], function () {
    Route::get('/upload', 'PensionFundMigrationDataController@upload')->name('pensionfund::migrate.upload');
    Route::post('/import', 'PensionFundMigrationDataController@import')->name('pensionfund::migrate.import');
    Route::post('/import-check', 'PensionFundMigrationDataController@importCheck')->name('pensionfund::migrate.import_check');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'pensionfund'], function () {
    Route::get('/', 'PensionFundController@index');

    // Upload pensionfund
    Route::get('/upload', 'PensionFundController@upload')->name('pensionfund::upload.pf');
    Route::post('/import', 'PensionFundController@import')->name('pensionfund::pf.import');
    Route::post('/verify', 'PensionFundController@verify')->name('pensionfund::pf.verify');

    // Claim pensionfund
    Route::get('/claim', 'ClaimPensionFundController@claim')->name('pensionfund::claim');
    Route::post('/claim-store', 'ClaimPensionFundController@storeClaim')->name('pensionfund::claim.store');
    Route::get('/get-info', 'ClaimPensionFundController@getInfo')->name('pensionfund::get.info');

    Route::get('/create', 'PensionFundController@create')->name('pensionfund::create');
    Route::post('/store', 'PensionFundController@store')->name('pensionfund::pf.store');
    Route::get('/detail/{id}', 'PensionFundController@detail')->name('pensionfund::pf.detail');
    Route::post('/delete/{id}', 'PensionFundController@delete')->name('pensionfund::pf.delete');
    Route::get('/edit/{id}', 'PensionFundController@edit')->name('pensionfund::pf.edit');
    Route::post('/update', 'PensionFundController@update')->name('pensionfund::pf.update');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'report'], function () {
    //Report
    Route::get('pension-fund/claim-report/search-api', 'PensionFundReportController@claimReportApi')->name('pensionfund::report.claim_search_api');
    Route::get('pension-fund/claim-report/search-api/download', 'PensionFundReportController@downloadClaimReportApi')->name('pensionfund::report.claim_search_api');
    Route::get('pension-fund/staff-detail', 'PensionFundReportController@getStaffDetailApi')->name('pensionfund::report.staff_detail_api');
    Route::get('pension-fund/summary', 'PensionFundReportController@summary')->name('pensionfund::report.summary');
    Route::get('pension-fund/current-staff', 'PensionFundReportController@currentStaff')->name('pensionfund::report.currentStaff');

    Route::get('pension-fund/staff-list', 'PensionFundReportController@getStaffListApi')->name('pensionfund::report.staff_list_api');
});

//Do unit test
Route::get('pension-fund/unit-test-summary', 'PensionFundReportUnitTestController@summaryReport')->name('pensionfund::report.unit_test_summary');
Route::get('pension-fund/unit-test-current-staff', 'PensionFundReportUnitTestController@currentStaff')->name('pensionfund::report.unit_test_current_staff');
Route::get('pension-fund/calculate-pf', 'UnitTestController@calculatePF')->name('pensionfund::calculate.pf');

//Do Migrate pf info
Route::get('pension-fund/unit-test-migration', 'PensionFundReportUnitTestController@getAllStaffPersonal')->name('pensionfund::report.unit_test_current_staff');
