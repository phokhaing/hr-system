<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffResignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_resigns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('staff_personal_info_id')->rereferences('id')->on('staff_personal_info')->index();
            $table->boolean('is_fraud')->nullable();
            $table->date('resign_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->date('reject_date')->nullable();
            $table->date('last_day')->nullable();
            $table->unsignedInteger('staff_id_replaced_1')->nullable()->index();
            $table->string('staff_name_replaced_1')->nullable();
            $table->unsignedInteger('staff_id_replaced_2')->nullable()->index();
            $table->string('staff_name_replaced_2')->nullable();
            $table->unsignedInteger('reason_company_id')->nullable()->index();
            $table->string('file_reference', 350)->nullable();
            $table->text('reason')->nullable();
            $table->tinyInteger('flag')->rereferences('id')->on('flags');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_resigns');
    }
}
