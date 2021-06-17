<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamps();
        });
        Schema::create('video_format', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("video_id")->unsigned();
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->bigInteger("format_id")->unsigned();
            $table->foreign('format_id')->references('id')->on('formats')->onDelete('cascade');
            $table->string("uri");
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
        Schema::dropIfExists('formats');
    }
}
