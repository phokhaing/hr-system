<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_document', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_personal_info_id')->nullable()->index();
            $table->integer('staff_document_type_id')->nullable()->index();
            $table->text('src')->nullable();
            $table->text('name')->nullable();
            $table->boolean('check')->nullable();
            $table->boolean('not_have')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('staff_document');
    }
}
