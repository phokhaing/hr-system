<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_experience', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_personal_info_id')->nullable($value = true)->index();
            $table->string('position')->nullable($value = true);
            $table->integer('level_id')->nullable($value = true)->index();
            $table->string('company_name_en')->nullable($value = true);
            $table->string('company_name_kh')->nullable($value = true);
            $table->date('start_date')->nullable($value = true);
            $table->date('end_date')->nullable($value = true);
            $table->integer('province_id')->nullable($value = true)->index();
            $table->integer('district_id')->nullable($value = true)->index();
            $table->integer('commune_id')->nullable($value = true)->index();
            $table->integer('village_id')->nullable($value = true)->index();
            $table->string('house_no')->nullable($value = true);
            $table->string('street_no')->nullable($value = true);
            $table->string('other_location')->nullable($value = true);
            $table->string('noted')->nullable($value = true);
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
        Schema::dropIfExists('staff_experience');
    }
}
