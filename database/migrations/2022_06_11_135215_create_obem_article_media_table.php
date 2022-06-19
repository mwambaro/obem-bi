<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obem_article_media', function (Blueprint $table) {
            $table->id();
            $table->string('mime_type');
            $table->string('media_file_name');
            $table->string('media_file_path')->unique();
            $table->integer('article_id');
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
        Schema::dropIfExists('obem_article_media');
    }
};
