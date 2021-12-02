<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPersonalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_personal_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name_en')->nullable($value = true);
            $table->string('last_name_en')->nullable($value = true);
            $table->string('first_name_kh')->nullable($value = true);
            $table->string('last_name_kh')->nullable($value = true);
            $table->tinyInteger('marital_status')->nullable($value = true);
            $table->tinyInteger('gender')->nullable($value = true)->comment('0.Male, 1.Female');
            $table->unsignedTinyInteger('id_type')->index()->nullable($value = true)->index();
            $table->string('id_code')->nullable($value = true);
            $table->date('dob')->nullable($value = true)->comment('Date of birth');
            $table->string('pob', 500)->nullable($value = true)->comment('Place of birth');
            $table->unsignedTinyInteger('bank_name')->nullable($value = true)->index();
            $table->string('bank_acc_no')->nullable($value = true);
            $table->float('height')->nullable($value = true);
            $table->string('driver_license')->nullable($value = true);
            $table->integer('province_id')->nullable($value = true)->index();
            $table->integer('district_id')->nullable($value = true)->index();
            $table->integer('commune_id')->nullable($value = true)->index();
            $table->integer('village_id')->nullable($value = true)->index();
            $table->string('house_no')->nullable($value = true);
            $table->string('street_no')->nullable($value = true);
            $table->string('other_location', 500)->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
            $table->string('emergency_contact', 500)->nullable($value = true);
            $table->string('photo', 250)->nullable($value = true);
            $table->string('noted', 1000)->nullable($value = true);
            $table->tinyInteger('flag')->nullable($value = true);
            $table->unsignedInteger('created_by')->nullable($value = true)->index();
            $table->unsignedInteger('updated_by')->nullable($value = true)->index();
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
        Schema::dropIfExists('staff_personal_info');
    }
}
