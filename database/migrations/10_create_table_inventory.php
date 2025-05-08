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
        Schema::create('tbl_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_part');
            $table->unsignedBigInteger('id_category');
            $table->integer('plan_stock')->default(0);
            $table->enum('status', ['OK', 'NG']);

            // index
            $table->index('id_part');

            // fk
            $table->foreign('id_category')->references('id')->on('tbl_category')->onDelete('cascade');
            $table->foreign('id_part')->references('id')->on('tbl_part')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_inventory');
    }
};
