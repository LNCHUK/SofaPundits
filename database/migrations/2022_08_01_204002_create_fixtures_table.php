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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_season_id');
            $table->string('referee')->nullable();
            $table->string('timezone');
            $table->datetime('date');
            $table->unsignedBigInteger('timestamp');
            $table->datetime('kick_off');
            $table->json('periods')->nullable();
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->string('venue')->nullable();
            $table->string('venue_city')->nullable();
            $table->string('status')->nullable();
            $table->string('status_code')->nullable();
            $table->string('time_elapsed')->nullable();
            $table->string('round')->nullable();
            $table->json('goals');
            $table->json('score');
            $table->timestamps();

            $table->foreign('league_season_id')->references('id')->on('league_seasons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('home_team_id')->references('id')->on('teams');

            $table->foreign('away_team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
};
