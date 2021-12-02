<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResignsJsonTable extends Migration
{
    /**
     * Run the migrations.
     * - is_fraud
     * - requested_date
     * - approved_date
     * - rejected_date
     * - last_day
     * - staff_hand_over {....}
     * - company_reason
     * - staff_reason
     * - file_reference
     * ...
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resigns_json', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('staff_personal_info_id');
            $table->bigInteger('contract_id');
            $table->json('resign_object')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('resigns_json');
    }
}
