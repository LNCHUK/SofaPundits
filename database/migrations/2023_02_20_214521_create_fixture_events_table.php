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
        Schema::create('fixture_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id');
            $table->string('type');
            $table->string('detail')->nullable();
            $table->text('comments')->nullable();
            $table->integer('minutes_elapsed');
            $table->integer('extra_minutes_elapsed')->nullable();
            $table->text('time');
            $table->unsignedBigInteger('team_id');
            $table->text('team');
            $table->unsignedBigInteger('player_id')->nullable();
            $table->string('player_name');
            $table->string('player');
            $table->unsignedBigInteger('secondary_player_id')->nullable();
            $table->string('secondary_player_name')->nullable();
            $table->string('assist')->nullable();
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
        Schema::dropIfExists('fixture_events');
    }
};
