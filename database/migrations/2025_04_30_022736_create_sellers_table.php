<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();

            
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sellers');
    }
};
