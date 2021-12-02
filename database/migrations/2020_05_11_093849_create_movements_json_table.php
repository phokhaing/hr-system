<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementsJsonTable extends Migration
{
    /**
     * Run the migrations.
     * - effective_date
     * - reject_date
     * - file_reference
     * - hand_over_to {id, fullName, position, ...}
     * - hand_over_from {id, fullName, position, ...}
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements_json', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('staff_personal_info_id');
            $table->bigInteger('contract_id');
            $table->json('movement_object')->nullable();
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
        Schema::dropIfExists('movements_json');
    }
}
