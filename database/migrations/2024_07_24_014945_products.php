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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->enum('active', ['YES', 'NO'])->nullable(false);
            $table->unsignedSmallInteger('stock')->nullable(false)->default(0);
            $table->double('price', 6, 2)->nullable(false);

            $table->timestamps();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete(' cascade');

            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete(' cascade');

            $table->unsignedBigInteger('category_parent_id')->index();
            $table->foreign('category_parent_id')
                ->references('parent_id')
                ->on('categories')
                ->onDelete(' cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
