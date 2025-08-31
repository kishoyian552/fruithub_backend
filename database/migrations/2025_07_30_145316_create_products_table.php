<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->boolean('in_stock')->default(true); 
            $table->float('rating')->default(0);
            $table->integer('reviews')->default(0);
            $table->timestamps();
        });// products table
    }

    public function down(): void
    {
        Schema::dropIfExists('products');// products table
    }// rollback
};
