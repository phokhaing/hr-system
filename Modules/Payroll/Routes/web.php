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

Route::prefix('payroll')->group(function () {
    Route::get('/', 'PostHalfMonthPayrollController@index')->name('payroll.index');

    // Temp Half Payroll
    Route::post('/checking-half-month', 'PostHalfMonthPayrollController@checkingHalfMonth')->name('payroll.checkingHalfMonth');
    Route::get('/get-checking-half-month', 'PostHalfMonthPayrollController@getCheckingHalfMonth')->name('payroll.getCheckingHalfMonth');

    // Half Payroll
    Route::post('/post-half-payroll', 'PostHalfMonthPayrollController@postHalfPayroll')->name('payroll.setHalfPayroll');
    Route::get('/get-half-payroll', 'PostHalfMonthPayrollController@getHalfPayroll')->name('payroll.getHalfPayroll');
    Route::get('/get-half-payroll/export', 'PostHalfMonthPayrollController@exportReportHalfPayroll')->name('payroll.exportReportHalfPayroll');
    Route::post('/set-temp-full-month-payroll', 'PostHalfMonthPayrollController@setTempFullMonthPayroll')->name('payroll.setTempFullMonthPayroll');
    Route::get('/export-deduction-template', 'PostHalfMonthPayrollController@exportDeductionTemplate')->name('payroll.exportDeductionTemplate');
    Route::get('/view-posted-list-half-month', 'UnPostHalfMonthController@viewPostedList')->name('payroll.view_posted_list_half_month');
    ROute::get('/view-posted-half-payroll-api', 'UnPostHalfMonthController@viewPostedListApi')->name('payroll.view_posted_list_half_month_api');
    Route::post('/block-un-block-transaction-half-month-api', 'UnPostHalfMonthController@blockOrUnBlockTempTransactionApi')->name('payroll.block_or_unblock_tem_transaction_half_month');
    Route::post('/un-post-payroll-half-month-api', 'UnPostHalfMonthController@unPostedPayroll')->name('payroll.unpost_payroll_half_month');
    Route::post('/check-available-date', 'UnPostHalfMonthController@unPostedPayroll')->name('payroll.check_available_date_half_month');
    /** Export payroll half month by staff */
    Route::get('/get-half-payroll/export-by-staff', 'PostHalfMonthPayrollController@exportReportHalfPayrollByStaff')->name('payroll.exportReportHalfPayrollByStaff');
    /** Export payroll half month by staff */
    Route::get('/get-half-payroll/export-by-branch', 'PostHalfMonthPayrollController@exportReportHalfPayrollByBranch')->name('payroll.exportReportHalfPayrollByBranch');

    // Full PayrollgetHalfPayroll
    Route::post('/import-deduction', 'PostFullMonthlyPayrollController@importIncentiveAndDeduction')->name('payroll.importIncentiveAndDeduction');
    Route::post('/preview-import-deduction', 'PostFullMonthlyPayrollController@previewDeductionFile')->name('payroll.previewDeductionFile');
    Route::get('/get-full-month-payroll', 'PostFullMonthlyPayrollController@getFullMonthPayrollReport')->name('payroll.getFullMonthPayroll');
    Route::get('/get-full-month-payroll/export', 'PostFullMonthlyPayrollController@exportFullMonthPayrollReport')->name('payroll.exportFullMonthPayroll');
    Route::get('/get-temp-full-month-payroll', 'PostFullMonthlyPayrollController@getTempFullMonthPayroll')->name('payroll.getTempFullMonthPayroll');
    Route::post('/checking-payroll-full-month', 'PostFullMonthlyPayrollController@checkingContractPayroll')->name('payroll.set_full_payroll');
    Route::post('/set-full-payroll', 'PostFullMonthlyPayrollController@post')->name('payroll.set_full_payroll');
    Route::get('/full-month', 'PostFullMonthlyPayrollController@index');

//    Export Payroll to Bank
    Route::get('/get-half-payroll/export-to-bank', 'PostHalfMonthPayrollController@exportReportToBank')->name('payroll.exportReportToBank');
    Route::get('/get-full-payroll/export-to-bank', 'PostFullMonthlyPayrollController@exportReportToBank')->name('payroll.exportFullMonthReportToBank');

    /** Export as excel */
    Route::get('/full-month/export-by-staff', 'PostFullMonthlyPayrollController@exportFullMonthPayrollReportByStaff');
    Route::get('/full-month/export-by-branch', 'PostFullMonthlyPayrollController@exportFullMonthPayrollReportByBranch');

    //Up Post Payroll
    Route::get('/view-posted-list', 'UnPostController@viewPostedList')->name('payroll.view_posted_list');
    Route::post('/block-un-block-transaction-api', 'UnPostController@blockOrUnBlockTempTransactionApi')->name('payroll.block_or_unblock_tem_transaction');
    Route::get('/view-posted-payroll-api', 'UnPostController@viewPostedPayrollApi')->name('payroll.view_posted');
    Route::post('/un-post-payroll-full-mont-api', 'UnPostController@unPostedPayroll')->name('payroll.unpost_payroll_full_month');

    // Unit Test
    Route::get('/unit-test-post-full-payroll', 'UnitTestController@post')->name('payroll.post_full_payroll');
    Route::get('/unit-test-mockup-tem-transaction', 'UnitTestController@saveTempTransHalfMonth')->name('payroll.save_temp_half');
    //    Route::get('/unit-test-mockup-tem-transaction-end-contract', 'UnitTestController@testSaveStaffResign')->name('payroll.mockup_transaction_end_contract');
    //    Route::get('/unit-test-mockup-tem-transaction-new-staff', 'UnitTestController@testSaveStaffNewStaff')->name('payroll.mockup_transaction_new_staff');
    //    Route::get('/unit-test-mockup-tem-transaction-company-paid-tax', 'UnitTestController@saveTempTransactionWithCompanyPaidTax')->name('payroll.mockup_transaction_new_staff');
    Route::get('/unit-checking-payroll-full-month', 'UnitTestController@checkingContractPayroll')->name('payroll.checking_payroll_full_month');
    Route::get('/unit-testFullReport', 'UnitTestController@testFullReport')->name('payroll.testFullReport');

    //Payroll Setting
    Route::get('/setting', 'PayrollSettingController@index')->name('payroll.setting.index');
    Route::post('/update/exchange-rate', 'PayrollSettingController@updateExchangeRate')->name('payroll.setting.updateExchangeRate');
    Route::post('/update/pension-found/{id}', 'PayrollSettingController@updatePensionFund')->name('payroll.setting.updatePensionFund');
    Route::post('/update/half-month/{id}', 'PayrollSettingController@updateHalfMonth')->name('payroll.setting.updateHalfMonth');
    Route::post('/update/payroll-period/{id}', 'PayrollSettingController@updatePayrollPeriod')->name('payroll.setting.updatePayrollPeriod');
    Route::post('/update/fring-allowance/{id}', 'PayrollSettingController@updateFringAllowance')->name('payroll.setting.updateFringAllowance');
    Route::post('/update/seniority/{id}', 'PayrollSettingController@updateSeniority')->name('payroll.setting.updateSeniority');

    //Tax On Salary Setting
    Route::post('/update/tax-on-salary/{id}', 'PayrollTaxOnSalarySettingController@update')->name('payroll.setting.updateTaxOnSalary');
    Route::post('/add-tax-on-salary', 'PayrollTaxOnSalarySettingController@store')->name('payroll.setting.storeTaxOnSalary');
    Route::post('/delete-tax-on-salary', 'PayrollTaxOnSalarySettingController@delete')->name('payroll.setting.deleteTaxOnSalary');

    //Pension Fund Rate from company Setting
    Route::post('/update/pension-fund-rate/{id}', 'PayrollPensionFundSettingController@update')->name('payroll.setting.updatePensionFundRate');
    Route::post('/add-pension-fund-rate', 'PayrollPensionFundSettingController@store')->name('payroll.setting.storePensionFundRate');
    Route::post('/delete-pension-fund-rate', 'PayrollPensionFundSettingController@delete')->name('payroll.setting.deletePensionFundRate');

    //Clear Payroll Checking list
    Route::post('/clear-checking-list-half-month', 'ClearPayrollController@clearCheckingListHalf')->name('payroll.clear_checking_list_half_month');
    Route::post('/clear-checking-list-full-month', 'ClearPayrollController@clearCheckingListFull')->name('payroll.clear_checking_list_full_month');
});
