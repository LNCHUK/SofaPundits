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
        Schema::create('command_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('command');
            $table->string('trigger');
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->boolean('was_successful')->nullable();
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
        Schema::dropIfExists('command_logs');
    }
};
