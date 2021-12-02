<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffGuarantorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_guarantor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_personal_info_id')->nullable($value = true)->index();
            $table->string('first_name_kh')->nullable($value = true);
            $table->string('last_name_kh')->nullable($value = true);
            $table->string('first_name_en')->nullable($value = true);
            $table->string('last_name_en')->nullable($value = true);
            $table->tinyInteger('gender')->nullable($value = true);
            $table->date('dob')->nullable($value = true);
            $table->string('pob')->nullable($value = true);
            $table->integer('id_type')->nullable($value = true)->index();
            $table->string('id_code')->nullable($value = true);
            $table->integer('career_id')->nullable($value = true)->index();
            $table->integer('marital_status')->nullable($value = true)->index();
            $table->tinyInteger('related_id')->nullable($value = true)->index();
            $table->tinyInteger('children_no')->nullable($value = true);
            $table->integer('province_id')->nullable($value = true)->index();
            $table->integer('district_id')->nullable($value = true)->index();
            $table->integer('commune_id')->nullable($value = true)->index();
            $table->integer('village_id')->nullable($value = true)->index();
            $table->string('house_no')->nullable($value = true);
            $table->string('street_no')->nullable($value = true);
            $table->string('other_location')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
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
        Schema::dropIfExists('staff_guarantor');
    }
}
