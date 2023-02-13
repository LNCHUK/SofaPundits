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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('api_name');
            $table->string('endpoint');
            $table->longText('request_params')->nullable();
            $table->longText('response_body')->nullable();
            $table->dateTime('started_at');
            $table->dateTime('completed_at');
            $table->boolean('was_successful');
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
        Schema::dropIfExists('api_logs');
    }
};
