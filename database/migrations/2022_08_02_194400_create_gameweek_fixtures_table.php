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
        Schema::create('gameweek_fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gameweek_id');
            $table->unsignedBigInteger('fixture_id');

            $table->foreign('gameweek_id')->references('id')->on('gameweeks')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('fixture_id')->references('id')->on('fixtures')
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
        Schema::dropIfExists('gameweek_fixtures');
    }
};
