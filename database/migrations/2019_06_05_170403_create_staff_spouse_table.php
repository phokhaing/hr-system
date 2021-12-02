<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffSpouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_spouse', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_personal_info_id')->nullable($value = true)->index();
            $table->string('full_name')->nullable($value = true);
            $table->tinyInteger('gender')->nullable($value = true);
            $table->tinyInteger('occupation_id')->nullable($value = true);
            $table->date('dob')->nullable($value = true);
            $table->tinyInteger('children_no')->nullable($value = true);
            $table->tinyInteger('children_tax')->nullable($value = true);
            $table->tinyInteger('spouse_tax')->nullable($value = true);
            $table->integer('province_id')->nullable($value = true)->index();
            $table->integer('district_id')->nullable($value = true)->index();
            $table->integer('commune_id')->nullable($value = true)->index();
            $table->integer('village_id')->nullable($value = true)->index();
            $table->string('house_no')->nullable($value = true);
            $table->string('street_no')->nullable($value = true);
            $table->string('phone')->nullable($value = true);
            $table->string('other_location')->nullable($value = true);
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
        Schema::dropIfExists('staff_spouse');
    }
}
