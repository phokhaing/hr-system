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

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth'], 'prefix' => 'hrtraining'], function () {

    Route::get('/', 'HRTrainingController@index')->name('hrtraining.index');

    //Course Setting
    Route::get('/course-setting', 'CourseSettingController@index')->name('hrtraining::course.setting');
    Route::get('/course-create', 'CourseSettingController@create')->name('hrtraining::course.setting.create');
    Route::post('/course-store', 'CourseSettingController@store')->name('hrtraining::course.setting.store');
    Route::get('/course-detail/{id}', 'CourseSettingController@detail')->name('hrtraining::course.setting.detail');
    Route::post('/course-delete/{id}', 'CourseSettingController@delete')->name('hrtraining::course.setting.delete');
    Route::get('/course-edit/{id}', 'CourseSettingController@edit')->name('hrtraining::course.setting.edit');
    Route::post('/course-update', 'CourseSettingController@update')->name('hrtraining::course.setting.update');
    Route::get('/course-lists', 'CourseSettingController@lists')->name('hrtraining::course.setting.lists');
    Route::get('/course-detail-api/{id}', 'CourseSettingController@detailApi')->name('hrtraining::course.setting.detailApi');

    // Category Setting
    Route::get('/category-setting', 'CategorySettingController@index')->name('hrtraining::category.setting');
    Route::get('/category-setting/create', 'CategorySettingController@create')->name('hrtraining::category.create');
    Route::post('/category-setting/store', 'CategorySettingController@store')->name('hrtraining::category.store');
    Route::post('/category-setting/{id}/destroy', 'CategorySettingController@destroy')->name('hrtraining::category.destroy');
    Route::get('/category-setting/{id}/edit', 'CategorySettingController@edit')->name('hrtraining::category.edit');
    Route::post('/category-setting/{id}/update', 'CategorySettingController@update')->name('hrtraining::category.update');
    Route::get('/category-setting/{id}/detail', 'CategorySettingController@detail')->name('hrtraining::category.detail');

    // Course Content (E-Lib) Setting
    Route::get('/course-content-setting', 'CourseContentSettingController@index')->name('hrtraining::course_content.setting');
    Route::get('/course-content-edit/{id}', 'CourseContentSettingController@edit')->name('hrtraining::course_content.setting.edit');

    Route::post('/course-content-store', 'CourseContentSettingController@store')->name('hrtraining::course_content.setting.store');
    Route::post('/course-content-update', 'CourseContentSettingController@update')->name('hrtraining::course_content.setting.update');
    Route::post('/course-content-delete/{content_id}', 'CourseContentSettingController@delete')->name('hrtraining::course_content.setting.delete');


    // Examination Setting
    Route::get('/examination-setting', 'ExaminationController@index')->name('hrtraining::examination.setting.index');
    Route::post('/examination-setting/store', 'ExaminationController@store')->name('hrtraining::examination.setting.store');
    Route::get('/examination-setting/list-api', 'ExaminationController@list')->name('hrtraining::examination.setting.list');
    Route::delete('/examination-setting/{id}', 'ExaminationController@destroy')->name('hrtraining::examination.setting.destroy');
    Route::post('/examination-setting/update', 'ExaminationController@update')->name('hrtraining::examination.setting.update');

    // Show results of examination's staff
    Route::get('/view-my-results/', 'ExaminationController@results')->name('hrtraining::examination.results');
    Route::get('/view-my-results/{id}', 'ExaminationController@detailResult')->name('hrtraining::examination.detailResult');

    // Question
    Route::post('/examination-setting/question/store', 'QuestionController@store')->name('hrtraining::examination.setting.question.store');
    Route::get('/examination-setting/question/{id}', 'QuestionController@listByExamId')->name('hrtraining::examination.setting.question.listByExamId');
    Route::post('/examination-setting/question/destroy', 'QuestionController@destroy')->name('hrtraining::examination.setting.question.destroy');


    //HR Enrollment
    Route::group(['prefix' => 'enrollment'], function () {
        Route::get('/{id}/training', 'EnrollmentController@index')->name('hrtraining::enrollment.index');
        Route::get('/create', 'EnrollmentController@create')->name('hrtraining::enrollment.create');
        Route::post('/store', 'EnrollmentController@store')->name('hrtraining::enrollment.store');
        Route::post('/delete/{id}', 'EnrollmentController@delete')->name('hrtraining::enrollment.delete');
        Route::get('/edit/{id}/{category_id}', 'EnrollmentController@edit')->name('hrtraining::enrollment.edit');
        Route::post('/update', 'EnrollmentController@update')->name('hrtraining::enrollment.update');
        Route::get('/detail/{id}', 'EnrollmentController@detail')->name('hrtraining::enrollment.detail');
        Route::post('/approve', 'EnrollmentController@approve')->name('hrtraining::enrollment.approve');
        Route::post('/update-progress', 'EnrollmentController@updateProgress')->name('hrtraining::enrollment.update_progress');
        Route::get('/review-trainee-exam', 'EnrollmentController@reviewTraineeExam')->name('hrtraining::enrollment.review_trainee_exam');
        Route::get('/review-exam', 'EnrollmentController@reviewExam')->name('hrtraining::enrollment.review_exam');
        Route::post('/calculate-exam-result', 'EnrollmentController@calculateExamResult')->name('hrtraining::enrollment.calculate_exam_result');

        //Rest Client Request
        Route::get('staff-current-contract-by-department-branch/{companyCode}/{departmentCode}/all', 'EnrollmentController@getTraineeWithCurrentContract');
    });

    //HR Review Staff Training and Exam
    Route::group(['prefix' => 'review-training'], function () {
        Route::get('/', 'HRReviewTrainingController@index')->name('hrtraining::review_training.index');
        Route::get('/list-trainees-by-enrollment', 'HRReviewTrainingController@listTraineeInCurrentEnrollment')->name('hrtraining::review_training.list_trainee_by_enrollment');
        Route::get('/review-exam', 'HRReviewTrainingController@reviewTraineeExam')->name('hrtraining::review_training.review_exam');
        Route::post('/calculate-exam-result', 'HRReviewTrainingController@calculateExamResult')->name('hrtraining::review_training.calculate_exam_result');
        Route::post('/update-trainee-progress', 'HRReviewTrainingController@updateTraineeProgress')->name('hrtraining::review_training.update_trainee_progress');
    });

    //Staff Enrollment
    Route::group(['prefix' => 'staff-enrollment'], function () {
        Route::get('/view-all-training-event', 'StaffEnrollmentController@viewAllTrainingEvent')->name('hrtraining::staff_enrollment.view_all_training_event');
        Route::get('/view-my-training-schedule', 'StaffEnrollmentController@viewMyTrainingSchedule')->name('hrtraining::staff_enrollment.view_my_training_schedule');

        Route::get('/detail/{id}', 'StaffEnrollmentController@detail')->name('hrtraining::staff_enrollment.detail');
        Route::get('/my-exam-result', 'StaffEnrollmentController@myExamResult')->name('hrtraining::staff_enrollment.my_exam_result');
        Route::post('/request-join-training', 'StaffEnrollmentController@requestJoinTraining')->name('hrtraining::staff_enrollment.request_join_training');
        Route::post('/request-cancel-training', 'StaffEnrollmentController@requestCancelTraining')->name('hrtraining::staff_enrollment.request_cancel_training');

        Route::get('/{id}/staff-training', 'StaffTrainingController@getAllOrientationTrainingCourse')->name('hrtraining::staff_training.orientation');
    });

    //Staff On Training
    Route::group(['prefix' => 'staff-on-training'], function () {
        Route::get('/start-training', 'StaffOnTrainingController@startTraining')->name('hrtraining::staff_on_training.start_training');
        Route::post('/complete-my-training', 'StaffOnTrainingController@completeMyTraining')->name('hrtraining::staff_on_training.complete_my_training');

        //Staff Take Exam From Training
        Route::get('/take-exam', 'StaffOnTrainingController@takeExam')->name('hrtraining::staff_on_training.take_exam');
        Route::post('/take-exam/save-and-continue', 'StaffOnTrainingController@takeExamSaveContinue')->name('hrtraining::staff_on_training.take_exam.save_continue');
    });
});


//Training Report
Route::group(['prefix' => 'report'], function () {
    Route::get('/staff-training', 'TrainingReportController@staffTraining')->name('hrtraining::report.staff_training');
    Route::get('/staff-training-result', 'TrainingReportController@staffTrainingResult')->name('hrtraining::report.staff_training_result');
    Route::get('/staff-training-course-by-category', 'TrainingReportController@trainingCourseByCategory')->name('hrtraining::report.training_course_by_category');
});

//Unit test for developer
Route::group(['prefix' => 'unit-test'], function () {
    Route::get('/staff-training-report', 'UnitTestController@staffTrainingReport')->name('hrtraining::unit_test.staff_training_report');
});
