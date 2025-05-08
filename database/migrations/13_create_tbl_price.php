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
        Schema::create('tbl_price', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventory');
            $table->integer('price')->default(0);
            $table ->date('date_start');
            $table ->date('date_end');


            // index
            $table->index('id_inventory');

            // fk
            $table->foreign('id_inventory')->references('id')->on('tbl_inventory')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_price');
    }
};
