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
        Schema::create('team_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('league_season_id');
            $table->unsignedBigInteger('league_id');
            $table->string('form')->nullable();
            $table->longText('fixtures')->nullable();
            $table->longText('goals')->nullable();
            $table->longText('biggest')->nullable();
            $table->longText('clean_sheet')->nullable();
            $table->longText('failed_to_score')->nullable();
            $table->longText('penalty')->nullable();
            $table->longText('lineups')->nullable();
            $table->longText('cards')->nullable();
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
        Schema::dropIfExists('team_statistics');
    }
};
