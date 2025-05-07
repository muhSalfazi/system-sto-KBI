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
        Schema::create('tbl_part', function (Blueprint $table) {
            $table->id();
            $table->string('Inv_id');
            $table->string('Part_name')->nullable();
            $table->string('Part_number')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('id_plan')->nullable();

            // index
            $table->index('Inv_id');
            $table->index('customer_id');
            $table->index('id_plan');
            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('id_plan')->references('id')->on('tbl_plan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_part');
    }
};
