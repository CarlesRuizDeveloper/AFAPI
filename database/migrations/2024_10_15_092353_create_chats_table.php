<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_1_id');
            $table->unsignedBigInteger('user_2_id');
            $table->unsignedBigInteger('llibre_id');
            $table->timestamps();
        
            $table->foreign('user_1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_2_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('llibre_id')->references('id')->on('llibre_de_texts')->onDelete('cascade');


        });
        
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
