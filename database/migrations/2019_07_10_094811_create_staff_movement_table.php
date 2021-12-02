<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffMovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_movement', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('staff_personal_info_id')->rereferences('id')->on('staff_personal_info')->index();
            $table->unsignedInteger('company_id')->rereferences('id')->on('companies')->nullable()->index();
            $table->unsignedInteger('branch_id')->rereferences('id')->on('branches')->nullable()->index();
            $table->unsignedInteger('department_id')->rereferences('id')->on('departments')->nullable()->index();
            $table->unsignedInteger('position_id')->rereferences('id')->on('positions')->nullable()->index();
            $table->unsignedInteger('to_company_id')->rereferences('id')->on('companies')->nullable()->index();
            $table->unsignedInteger('to_branch_id')->rereferences('id')->on('branches')->nullable()->index();
            $table->unsignedInteger('to_department_id')->rereferences('id')->on('departments')->nullable()->index();
            $table->unsignedInteger('to_position_id')->rereferences('id')->on('positions')->nullable()->index();
            $table->double('old_salary', 12, 2)->nullable();
            $table->double('new_salary', 12, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->date('reject_date')->nullable();
            $table->string('file_reference', 350)->nullable();
            $table->string('transfer_to_id')->nullable();
            $table->string('transfer_to_name')->nullable();
            $table->string('get_work_form_id')->nullable();
            $table->string('get_work_form_name')->nullable();
            $table->tinyInteger('flag')->rereferences('id')->on('flags');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_movement');
    }
}
