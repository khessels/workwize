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
            $table->string('tags')->nullable(true);
            $table->enum('active', ['YES', 'NO'])->nullable(false);
            $table->unsignedSmallInteger('stock')->nullable(false)->default(0);
            $table->double('price', 6, 2)->nullable(false);

            $table->timestamps();
        });
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete(' cascade');
            $table->unsignedBigInteger('tag_id')->nullable(false);
            $table->foreign('tag_id')
                ->references('id')
                ->on('tag')
                ->onDelete(' cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_category');
    }
};
