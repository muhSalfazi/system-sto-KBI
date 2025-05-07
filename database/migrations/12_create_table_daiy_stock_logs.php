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
        Schema::create('tbl_daiy_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventory');
            $table->unsignedBigInteger('prepared_by');
            $table->string('status')->nullable();
            $table->integer('qty');
            $table->string('type');
            $table->string('inventory_type');

            // index
            $table->index('id_inventory');
            $table->index('prepared_by');
            // fk
            $table->foreign('id_inventory')->references('id')->on('tbl_inventory')->onDelete('cascade');
            $table->foreign('prepared_by')->references('id')->on('tbl_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_daiy_stock_logs');
    }
};
