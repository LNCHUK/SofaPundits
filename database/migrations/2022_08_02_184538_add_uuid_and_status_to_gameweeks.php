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
        Schema::table('gameweeks', function (Blueprint $table) {
            $table->uuid()->after('id');
            $table->string('status')->default('pending')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gameweeks', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'status']);
        });
    }
};
