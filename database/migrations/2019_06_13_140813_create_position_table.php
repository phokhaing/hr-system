<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code')->nullable()->index();
            $table->integer('company_id')->nullable()->index();
            $table->integer('company_code')->nullable()->index();
            $table->integer('branch_department_code')->nullable()->index();
            $table->string('short_name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_kh')->nullable();
            $table->longText('desc_en')->nullable();
            $table->longText('desc_kh')->nullable();
            $table->string('range')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('positions');
    }
}
