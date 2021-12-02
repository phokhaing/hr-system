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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('fake-data', 'FakeDataController@fakeData');

// To allow php.ini on Server
ini_set('memory_limit', '512M');

/* =============================================
                Staff Personal Info
    ============================================= */
Route::group(['middleware' => ['auth'], 'prefix' => 'staff-personnel-info'], function () {
    Route::get('/', ['as' => 'staff-personal-info.index', 'uses' => 'StaffInfo\StaffPersonalInfoController@index']);
    Route::get('/create', ['as' => 'staff-personal-info.create', 'uses' => 'StaffInfo\StaffPersonalInfoController@create']);
    Route::post('/store', ['as' => 'staff-personal-info.store', 'uses' => 'StaffInfo\StaffPersonalInfoController@store']);
    Route::get('/{staff}/edit', ['as' => 'staff-personal-info.edit', 'uses' => 'StaffInfo\StaffPersonalInfoController@edit']);
    Route::post('/update', ['as' => 'staff-personal-info.update', 'uses' => 'StaffInfo\StaffPersonalInfoController@update']);
    Route::post('/destroy', ['as' => 'staff-personal-info.destroy', 'uses' => 'StaffInfo\StaffPersonalInfoController@destroy']);
    Route::get('/{staff}/show', ['as' => 'staff-personal-info.show', 'uses' => 'StaffInfo\StaffPersonalInfoController@show']);
    Route::get('/filter', ['as' => 'staff.filter', 'uses' => 'StaffInfo\StaffPersonalInfoController@filter']);
    Route::get('/import', ['as' => 'staff.import', 'uses' => 'StaffInfo\StaffPersonalInfoController@import']);
    Route::post('/import-file', ['as' => 'staff.import-file', 'uses' => 'StaffInfo\StaffPersonalInfoController@importFile']);
    Route::get('/{id}/detail-json', 'StaffInfo\StaffPersonalInfoController@staffDetailJson')->name('staff.staffDetailJson');

});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::get('view-profile', 'UserController@viewProfile')->name('user.viewProfile');
    Route::patch('update-profile/{id}', 'UserController@updateProfile')->name('user.updateProfile');
    Route::resource('products', 'ProductController');
    Route::resource('permissions', 'PermissionController');

    /* =============================================
                    Staff Resign
    ============================================= */
    Route::get('staff-resign', ['as' => 'resign.index', 'uses' => 'StaffInfo\StaffResignController@index']);
    Route::get('staff-resign/resign', ['as' => 'resign.create', 'uses' => 'StaffInfo\StaffResignController@create']);
    Route::post('staff-resign/store', ['as' => 'resign.store', 'uses' => 'StaffInfo\StaffResignController@store']);
    Route::post('staff-resign/edit', ['as' => 'resign.edit', 'uses' => 'StaffInfo\StaffResignController@edit']);
    Route::get('staff-resign/{id}/show', ['as' => 'resign.show', 'uses' => 'StaffInfo\StaffResignController@show']);
    Route::post('staff-resign/{id}/update', 'StaffInfo\StaffResignController@update');
    Route::post('staff-resign/{id}/delete', 'StaffInfo\StaffResignController@destroy');
    Route::post('staff-resign/find', ['as' => 'resign.find_id', 'uses' => 'StaffInfo\StaffResignController@findId']);
    Route::get('staff-resign/{id}/reject', ['as' => 'resign.reject', 'uses' => 'StaffInfo\StaffResignController@reject']);
    Route::get('staff-resign/filter', ['as' => 'resign.filter', 'uses' => 'StaffInfo\StaffResignController@filter']);


    /* =============================================
                    Staff Movement
    ============================================= */
    Route::get('/staff-movement', ['as' => 'movement.index', 'uses' => 'StaffInfo\StaffMovementController@index']);
    Route::get('/staff-movement/create', ['as' => 'movement.create', 'uses' => 'StaffInfo\StaffMovementController@create']);
    Route::post('/staff-movement/store', ['as' => 'movement.store', 'uses' => 'StaffInfo\StaffMovementController@store']);
    Route::get('/staff-movement/{id}/destroy', ['as' => 'movement.destroy', 'uses' => 'StaffInfo\StaffMovementController@destroy']);
    Route::get('/staff-movement/{id}/edit', ['as' => 'movement.edit', 'uses' => 'StaffInfo\StaffMovementController@edit']);
    Route::post('/staff-movement/update', ['as' => 'movement.update', 'uses' => 'StaffInfo\StaffMovementController@update']);
    Route::post('/staff-movement/find', ['as' => 'movement.find', 'uses' => 'StaffInfo\StaffMovementController@find']);
    Route::get('/staff-movement/filter', ['as' => 'movement.filter', 'uses' => 'StaffInfo\StaffMovementController@filter']);
    Route::get('/staff-movement/{id}/show', ['as' => 'movement.show', 'uses' => 'StaffInfo\StaffMovementController@show']);

    /* =============================================
                        Unity
    ============================================= */
    Route::get('unity/district/{pro_id}', ['as' => 'unity.district', 'uses' => 'UnityController@getDistrict']);
    Route::get('unity/commune/{dis_id}', ['as' => 'unity.commune', 'uses' => 'UnityController@getCommune']);
    Route::get('unity/village/{com_id}', ['as' => 'unity.village', 'uses' => 'UnityController@getVillage']);

    /* =============================================
                Company, branch and Department
    ============================================= */
    Route::get('company', 'CompanyController@index');
    Route::get('company/create', 'CompanyController@create');
    Route::post('company/store', 'CompanyController@store');
    Route::get('company/{id}/edit', 'CompanyController@edit');
    Route::get('company/{id}/show', 'CompanyController@show');
    Route::post('company/{id}/update', 'CompanyController@update');
    Route::post('company/{id}/delete', 'CompanyController@destroy');
    Route::get('company/{id}/find-branch-department', 'CompanyController@getBranchDepartmentByCompany');
    Route::get('company/{id}/find-position', 'CompanyController@getPositionByCompany');
    Route::get('company/current', 'CompanyController@current');
    Route::get('company/all', 'CompanyController@all');
    Route::get('company/company-for-training', 'CompanyController@getCompanyForTraining');

    Route::get('branch-and-department', 'BranchesAndDepartmentsController@index')->name('branch_department.index');
    Route::get('branch-and-department/create', 'BranchesAndDepartmentsController@create');
    Route::post('branch-and-department/store', 'BranchesAndDepartmentsController@store');
    Route::get('branch-and-department/{id}/edit', 'BranchesAndDepartmentsController@edit');
    Route::get('branch-and-department/{id}/show', 'BranchesAndDepartmentsController@show');
    Route::post('branch-and-department/{id}/update', 'BranchesAndDepartmentsController@update');
    Route::post('branch-and-department/{id}/delete', 'BranchesAndDepartmentsController@destroy');
    Route::get('branch-and-department/{code}/all', 'BranchesAndDepartmentsController@getByCompany');
    Route::get('branch-and-department/{companyCode}/{branchDepartmentCode}/staffs', 'BranchesAndDepartmentsController@getStaffInBranchDepartment');


    Route::get('position', 'PositionController@index')->name('position.index');
    Route::get('position/create', 'PositionController@create');
    Route::post('position/store', 'PositionController@store');
    Route::get('position/{id}/edit', 'PositionController@edit');
    Route::get('position/{id}/show', 'PositionController@show');
    Route::post('position/{id}/update', 'PositionController@update');
    Route::post('position/{id}/delete', 'PositionController@destroy');
    Route::get('position/{code}/all', 'PositionController@getByCompany');

    /* =============================================
                Form Downloads
    ============================================= */
    Route::get('form_downloads', 'FormDownloadController@index');
    Route::get('form_download/create', 'FormDownloadController@create');
    Route::post('form_download/store', 'FormDownloadController@store');
    Route::post('form_download/{id}/delete', 'FormDownloadController@destroy');
    Route::get('public/form_download/{name}', 'FormDownloadController@showFile');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'staff'], function () {

    /* =============================================
                      Staff Education
    ============================================= */
    Route::get('education/{id}/edit', ['as' => 'education.edit', 'uses' => 'StaffInfo\StaffEducationController@edit']);
    Route::post('education/store', ['as' => 'education.store', 'uses' => 'StaffInfo\StaffEducationController@store']);
    Route::post('education/update', ['as' => 'education.update', 'uses' => 'StaffInfo\StaffEducationController@update']);

    /* =============================================
                      Staff Spouse
    ============================================= */
    Route::get('spouse/{id}/edit', ['as' => 'spouse.edit', 'uses' => 'StaffInfo\SpouseController@edit']);
    Route::post('spouse/store', ['as' => 'spouse.store', 'uses' => 'StaffInfo\SpouseController@store']);
    Route::post('spouse/update', ['as' => 'spouse.update', 'uses' => 'StaffInfo\SpouseController@update']);

    /* =============================================
                      Staff Training
    ============================================= */
    Route::get('training/{id}/edit', ['as' => 'training.edit', 'uses' => 'StaffInfo\StaffTrainingController@edit']);
    Route::post('training/store', ['as' => 'training.store', 'uses' => 'StaffInfo\StaffTrainingController@store']);
    Route::post('training/update', ['as' => 'training.update', 'uses' => 'StaffInfo\StaffTrainingController@update']);

    /* =============================================
                      Staff Experience
    ============================================= */
    Route::get('experience/{id}/edit', ['as' => 'experience.edit', 'uses' => 'StaffInfo\StaffExperienceController@edit']);
    Route::post('experience/store', ['as' => 'experience.store', 'uses' => 'StaffInfo\StaffExperienceController@store']);
    Route::post('experience/update', ['as' => 'experience.update', 'uses' => 'StaffInfo\StaffExperienceController@update']);

    /* =============================================
                      Staff Guarantor
    ============================================= */
    Route::get('guarantor/{id}/edit', ['as' => 'guarantor.edit', 'uses' => 'StaffInfo\StaffGuarantorController@edit']);
    Route::post('guarantor/store', ['as' => 'guarantor.store', 'uses' => 'StaffInfo\StaffGuarantorController@store']);
    Route::post('guarantor/update', ['as' => 'guarantor.update', 'uses' => 'StaffInfo\StaffGuarantorController@update']);

    /* =============================================
                      Staff Profile
    ============================================= */
    Route::get('profile/{id}/edit', ['as' => 'profile.edit', 'uses' => 'StaffInfo\StaffProfileController@edit']);
    Route::post('profile/store', ['as' => 'profile.store', 'uses' => 'StaffInfo\StaffProfileController@store']);
    Route::post('profile/update', ['as' => 'profile.update', 'uses' => 'StaffInfo\StaffProfileController@update']);

    /* =============================================
                      Staff Document
    ============================================= */
    Route::get('document/{id}/edit', ['as' => 'document.edit', 'uses' => 'StaffInfo\StaffDocumentController@edit']);
    Route::post('document/store', ['as' => 'document.store', 'uses' => 'StaffInfo\StaffDocumentController@store']);
    Route::post('document/update', ['as' => 'document.update', 'uses' => 'StaffInfo\StaffDocumentController@update']);
    Route::get('document/{id}/destroy', 'StaffInfo\StaffDocumentController@destroy');

    /* =============================================
                      Staff Request Resign
    ============================================= */
    Route::get('request-resign/{id}/edit', 'StaffInfo\RequestResignController@edit')->name('request_resign.edit');
    Route::post('request-resign/store', 'StaffInfo\RequestResignController@store')->name('request_resign.store');
    Route::get('request-resign/index', 'StaffInfo\RequestResignController@index')->name('request_resign.index');
    Route::get('request-resign/list', 'StaffInfo\RequestResignController@list')->name('request_resign.list');
    Route::post('request-resign/update', 'StaffInfo\RequestResignController@update')->name('request_resign.update');

    /* =============================================
                      Pension Fund
    ============================================= */
    Route::get('pension-fund/{id}/edit', 'StaffInfo\StaffPersonalInfoController@viewPensionFund')->name('pension_fund.view');
    Route::get('training/{id}/edit', 'StaffInfo\StaffPersonalInfoController@viewTraining')->name('training.view');

});

/* =============================================
                     Report
   ============================================= */
Route::group(['middleware' => ['auth'], 'prefix' => 'report'], function () {
    Route::get('/', ['as' => 'report.index', 'uses' => 'Report\ReportController@index']);
    Route::get('/index', ['as' => 'report.index', 'uses' => 'Report\ReportController@index']);

    Route::get('/staff-active/search-api', ['as' => 'report.getActiveStaffApi', 'uses' => 'Report\ReportController@getActiveStaffApi']);
    Route::get('/staff-active/search-api/download', 'Report\ReportController@downloadStaffActive')->name('report.downloadStaffActive');
    Route::get('/staff-movement/search-api', 'Report\ReportController@getMovementStaffApi')->name('report.getMovementStaffApi');
    Route::get('/staff-movement/search-api/download', 'Report\ReportController@downloadMovementStaff')->name('report.downloadMovementStaff');
    Route::get('/staff-end-contract/search-api', 'Report\ReportController@getStaffEndContractApi')->name('report.getStaffEndContractApi');
    Route::get('/staff-end-contract/search-api/download', 'Report\ReportController@downloadStaffEndContract')->name('report.downloadStaffEndContract');
    Route::get('/staff-request-resign/search-api', 'Report\ReportController@getStaffRequestResignApi')->name('report.getStaffRequestResignApi');
    Route::get('/staff-request-resign/search-api/download', 'Report\ReportController@downloadStaffRequestResign')->name('report.downloadStaffRequestResign');

    Route::get('/staff-profile', ['as' => 'report.profile', 'uses' => 'Report\ReportController@profile']);
    Route::get('/staff-profile/action', ['as' => 'report.action', 'uses' => 'Report\ReportController@action']);
    // Route::get('/staff-resign/action' ,['as' => 'report.action_resign', 'uses' => 'Report\ReportController@action_resign']);

    // Lack of information of staff
    Route::get('/lack-of-information', ['as' => 'report.lack_info', 'uses' => 'Report\ReportController@lack_info']);
    Route::get('/lack-of-information/get-data', ['as' => 'report.get_lack_info', 'uses' => 'Report\ReportController@get_lack_info']);

    Route::get('/summary/age-range-and-gender', 'Report\AgeRangAndGenderController@index')->name('report.summaryAgeRangeAndGender');
    Route::get('/summary/age-range-and-gender/export', 'Report\AgeRangAndGenderController@export')->name('report.summaryAgeRangeAndGenderExport');

    Route::get('/summary/constract-status', 'Report\ContractStatusController@index')->name('report.summaryContractStatus');
    Route::get('/summary/constract-status/export', 'Report\ContractStatusController@export')->name('report.summaryContractStatusExport');

    Route::get('/summary/seniority', 'Report\SeniorityController@index')->name('report.summarySeniority');
    Route::get('/summary/seniority/export', 'Report\SeniorityController@export')->name('report.summarySeniorityExport');

    Route::get('/summary/staff-turn-over-by-month', 'Report\StaffTurnOverEachMonthController@index')->name('report.summaryStaffTurnOverEachMonth');
    Route::get('/summary/staff-turn-over-by-month/export', 'Report\StaffTurnOverEachMonthController@export')->name('report.summaryStaffTurnOverEachMonthExport');

    Route::get('/summary/staff-turn-over', 'Report\StaffTurnOverController@index')->name('report.summaryStaffTurnOver');
    Route::get('/summary/staff-turn-over/export', 'Report\StaffTurnOverController@export')->name('report.summaryStaffTurnOverExport');

    //Final Pay
    Route::get('/final-pay', ['as' => 'report.reportFinalPay', 'uses' => 'FinalPayController@report']);
    //Last Day/Block Salary
    Route::get('/last-day-or-block-salary', ['as' => 'report.reportLastDayOrBlockSalary', 'uses' => 'BlockSalaryController@report']);
});

Route::get('public/staff_document/{name}', 'StaffInfo\StaffDocumentController@showFile');
Route::post('probation-end-date', ['as' => 'end_probation.index', 'uses' => 'UnityController@end_probation']);

// Staff Contract
Route::group(['middleware' => ['auth'], 'prefix' => 'contract'], function () {
    Route::get('/', ['as' => 'contract.index', 'uses' => 'ContractController@index']);
    Route::get('/index', ['as' => 'contract.index', 'uses' => 'ContractController@index']);
    Route::post('/store', ['as' => 'contract.store', 'uses' => 'ContractController@store']);
    Route::post('/store-contract-by-type', ['as' => 'contract.storeContractByType', 'uses' => 'ContractController@storeContractByType']);
    Route::post('/store-regular', ['as' => 'contract.storeRegular', 'uses' => 'ContractController@storeRegular']);
    Route::post('/store-death', ['as' => 'contract.storeDeath', 'uses' => 'ContractController@storeDeath']);
    Route::post('/store-terminate', ['as' => 'contract.storeTerminate', 'uses' => 'ContractController@storeTerminate']);
    Route::post('/store-resign', ['as' => 'contract.storeResign', 'uses' => 'ContractController@storeResign']);
    Route::post('/store-demote', ['as' => 'contract.storeDemote', 'uses' => 'ContractController@storeDemote']);
    Route::post('/store-promote', ['as' => 'contract.storePromote', 'uses' => 'ContractController@storePromote']);
    Route::post('/store-lay-off', ['as' => 'contract.storeLayOff', 'uses' => 'ContractController@storeLayOff']);
    Route::post('/store-change-location', ['as' => 'contract.storeChangeLocation', 'uses' => 'ContractController@storeChangeLocation']);
    Route::get('/actions', ['as' => 'contract.actions', 'uses' => 'ContractController@actions']);
    Route::post('/find', ['as' => 'contract.find', 'uses' => 'ContractController@find']);
    Route::post('/delete', ['as' => 'contract.delete', 'uses' => 'ContractController@delete']);
    Route::get('/{id}/edit', ['as' => 'contract.edit', 'uses' => 'ContractController@edit']);
    Route::post('/update', ['as' => 'contract.update', 'uses' => 'ContractController@update']);

    //Modify new salary
    Route::get('/new-salary/{id}', ['as' => 'contract.new_salary', 'uses' => 'NewSalaryController@index']);
    Route::post('/new-salary/store', ['as' => 'contract.new_salary_store', 'uses' => 'NewSalaryController@store']);
    Route::post('/new-salary/update', ['as' => 'contract.new_salary_update', 'uses' => 'NewSalaryController@update']);
    Route::post('/new-salary/delete', ['as' => 'contract.new_salary_delete', 'uses' => 'NewSalaryController@delete']);
});

// Staff Contract Actions
Route::group(['middleware' => ['auth'], 'prefix' => 'contract-actions'], function () {
    Route::post('/store', ['as' => 'contract-actions.store', 'uses' => 'ContractActionsController@store']);
});

// Block salary/Last Day for final pay
Route::group(['middleware' => ['auth'], 'prefix' => 'block-salary'], function () {
    Route::get('/{id}/block-last-day', ['as' => 'block_salary.block_last_day', 'uses' => 'BlockSalaryController@blockLastDay']);
    Route::get('/', ['as' => 'block_salary.index', 'uses' => 'BlockSalaryController@index']);
    Route::post('/block-or-unblock', ['as' => 'block_salary.block_or_unBlock', 'uses' => 'BlockSalaryController@blockOrUnBlock']);
    Route::get('/testing-date', ['as' => 'block_salary.testing_date', 'uses' => 'BlockSalaryController@calculateBlockSalaryDateRange']);
});

// Final Pay
Route::group(['middleware' => ['auth'], 'prefix' => 'final-pay'], function () {
    Route::get('/', ['as' => 'final_pay.index', 'uses' => 'FinalPayController@index']);
    Route::get('/index', ['as' => 'final_pay.index', 'uses' => 'FinalPayController@index']);
    Route::get('/{id}/edit', ['as' => 'final_pay.edit', 'uses' => 'FinalPayController@edit']);
    Route::get('/{id}/show', ['as' => 'final_pay.show', 'uses' => 'FinalPayController@show']);
    Route::post('/update', ['as' => 'final_pay.update', 'uses' => 'FinalPayController@update']);
    Route::get('/create', ['as' => 'final_pay.create', 'uses' => 'FinalPayController@create']);
    Route::post('/store', ['as' => 'final_pay.store', 'uses' => 'FinalPayController@store']);
    Route::post('/delete', ['as' => 'final_pay.delete', 'uses' => 'FinalPayController@delete']);
    Route::get('/get-staff-info', ['as' => 'final_pay.get_staff_info', 'uses' => 'FinalPayController@getStaffInfo']);
    Route::post('/checking-tax', ['as' => 'final_pay.checkingTax', 'uses' => 'FinalPayController@checkingTax']);
    Route::post('/post', ['as' => 'final_pay.post', 'uses' => 'FinalPayController@post']);
    Route::post('/checking', ['as' => 'final_pay.post', 'uses' => 'FinalPayController@post']);
    Route::get('/{id}/export-excel', ['as' => 'final_pay.export_excel', 'uses' => 'FinalPayController@exportExcel']);
});