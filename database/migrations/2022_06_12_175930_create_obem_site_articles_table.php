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
        Schema::create('obem_site_articles', function (Blueprint $table) {
            $table->id();
            $table->string('capture')->unique();
            $table->string('locale');
            $table->string('guid');
            $table->longText('body');
            $table->dateTime('date');
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
        Schema::dropIfExists('obem_site_articles');
    }
};
