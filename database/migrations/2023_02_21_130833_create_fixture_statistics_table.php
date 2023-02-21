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
        Schema::create('fixture_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id');
            $table->unsignedBigInteger('team_id');
            $table->integer('shots_on_goal');
            $table->integer('shots_off_goal');
            $table->integer('total_shots');
            $table->integer('blocked_shots');
            $table->integer('shots_inside_box');
            $table->integer('shots_outside_box');
            $table->integer('fouls');
            $table->integer('corners');
            $table->integer('offsides');
            $table->decimal('possession', 5, 2);
            $table->integer('yellow_cards');
            $table->integer('red_cards');
            $table->integer('saves');
            $table->integer('total_passes');
            $table->integer('passes_completed');
            $table->decimal('pass_accuracy', 5, 2);
            $table->decimal('expected_goals', 4, 2);
            $table->timestamps();

            $table->foreign('fixture_id')->references('id')->on('fixtures')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('team_id')->references('id')->on('teams')
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
        Schema::dropIfExists('fixture_statistics');
    }
};
