<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_personal_info_id')->nullable($value = true)->index();
            $table->string('emp_id_card')->nullable($value = true)->comment("get from excel HR (ID Card)");
            $table->integer('branch_id')->nullable($value = true)->index();
            $table->integer('company_id')->nullable($value = true)->index();
            $table->integer('dpt_id')->nullable($value = true)->comment("department ID")->index();
            $table->integer('position_id')->nullable($value = true)->index();
            $table->double('base_salary', 12, 2)->nullable($value = true);
            $table->string('currency')->default("KHR");
            $table->date('employment_date')->nullable($value = true);
            $table->date('probation_end_date')->nullable($value = true);
            $table->tinyInteger('probation_duration')->nullable($value = true);
            $table->date('contract_end_date')->nullable($value = true);
            $table->tinyInteger('contract_duration')->nullable($value = true);
            $table->string('manager')->nullable($value = true);
            $table->tinyInteger('home_visit')->nullable($value = true)->comment("1.Visited, 0.Not yet");
            $table->string('email')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
            $table->string('mobile')->nullable($value = true);
            $table->tinyInteger('flag')->nullable($value = true);
            $table->integer('created_by')->nullable($value = true)->index();
            $table->integer('updated_by')->nullable($value = true)->index();
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
        Schema::dropIfExists('staff_info');
    }
}
