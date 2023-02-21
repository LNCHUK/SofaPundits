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
        Schema::create('fixture_lineup_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_lineup_id');
            $table->string('type')->comment('Starting XI or Substitute');
            $table->unsignedBigInteger('player_id');
            $table->string('name');
            $table->string('number');
            $table->string('position');
            $table->string('grid_position')->nullable();
            $table->longText('player')->nullable();
            $table->timestamps();

            $table->foreign('fixture_lineup_id')->references('id')->on('fixture_lineups')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture_lineup_players');
    }
};
