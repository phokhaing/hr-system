<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesAndDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches_and_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_code')->index()->nullable();
            $table->string('code')->index()->nullable();
            $table->string('short_name',10)->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_km')->nullable();
            $table->text('detail')->nullable()->comment('Something that describe about branch or department');
            $table->integer('parent_id')->nullable()->comment('Belong to parent record.');
            $table->integer('order_by')->nullable();
            $table->integer('rank')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('branches_and_departments');
    }
}
