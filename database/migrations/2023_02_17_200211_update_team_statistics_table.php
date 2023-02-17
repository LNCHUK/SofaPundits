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
        Schema::table('team_statistics', function (Blueprint $table) {
            $table->renameColumn('league_season_id', 'league_season');
            $table->unique(['team_id', 'league_season', 'league_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('team_statistics', function (Blueprint $table) {
            $table->dropUnique(['team_id', 'league_season', 'league_id']);
            $table->renameColumn('league_season', 'league_season_id');
        });
    }
};
