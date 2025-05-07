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
        Schema::create('tbl_rak', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_area');
            $table->string('nama_rak');


            // Foreign key constraint
            $table->foreign('id_area')->references('id')->on('tbl_area')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rak');
    }
};
