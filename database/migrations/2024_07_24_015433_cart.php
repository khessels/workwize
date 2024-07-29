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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->enum('paid', ['YES', 'NO', 'PROCESSING'])->default('NO')->nullable(false);
            $table->double('total', 6, 2)->nullable(true);
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id')->index();
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete(' cascade');

            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete(' cascade');
            $table->integer('quantity')->nullable(false);
            $table->double('price', 6, 2)->nullable(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
