<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     * - company-department/branch-position
     * - emp_id_card
     * - salary
     * - currency
     * - employment_date
     * - probation_end_date
     * - contract_end_date
     * - manager {}
     * - home_visit
     * - email
     * - phone
     * - mobile
     * - status (new, promote, demote, resign, ...)
     * - ...
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_code')->index()->comment('Code on employee card.');
            $table->bigInteger('staff_personal_info_id')->unsigned();
            $table->foreign('staff_personal_info_id')->references('id')->on('staff_personal_info')->onDelete('cascade');
            $table->string('company_profile')->nullable()->comment('xxx-xxx-xxx');
            $table->json('contract_object')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->integer('contract_type')->nullable()->comment('Please see on constant');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
