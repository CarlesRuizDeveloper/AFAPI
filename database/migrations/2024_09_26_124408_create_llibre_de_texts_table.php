<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('llibres_de_text', function (Blueprint $table) {
            $table->id();
            $table->string('titol');
            $table->string('curs');
            $table->string('editorial');
            $table->text('observacions')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); 
            $table->timestamps();
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llibre_de_texts');
    }
};
