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
        Schema::create('integration_settings', function (Blueprint $table) {
            $table->id(); 
            $table->string('type'); 
            $table->string('value')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable(); 

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('integration_settings'); // Drop the table if it exists
    }
};
