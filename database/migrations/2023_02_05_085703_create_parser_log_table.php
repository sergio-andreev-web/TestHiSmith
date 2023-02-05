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
        Schema::create('parser_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_time');
            $table->string('request_method');
            $table->string('request_url');
            $table->integer('response_code');
            $table->text('response_body');
            $table->integer('execution_time');
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
        Schema::dropIfExists('parser_log');
    }
};
