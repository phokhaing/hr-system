<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('File title');
            $table->string('pdf_name')->nullable()->comment('Original file name');
            $table->string('doc_name')->nullable()->comment('Original file name');
            $table->string('pdf_src')->comment('pdf path');
            $table->string('doc_src')->comment('Other document path');
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
        Schema::dropIfExists('form_downloads');
    }
}
