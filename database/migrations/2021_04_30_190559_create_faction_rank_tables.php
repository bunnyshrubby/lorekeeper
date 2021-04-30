<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactionRankTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faction_ranks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('faction_id')->unsigned()->index();

            // Basics
            $table->string('name');
            $table->text('descripion');

            // Order within the faction
            $table->integer('sort')->default(0);
            // Whether or not the rank is open vs must be set by staff
            $table->boolean('is_open')->default(1);
            // Number of positions available within this rank -- has no impact if open
            $table->integer('amount')->nullable()->default(null);
            // Amount of faction standing required to attain the rank -- has no impact if closed
            $table->integer('breakpoint')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faction_ranks');
    }
}
