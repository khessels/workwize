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
        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary()->unique()->unsigned();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('english')->nullable(false);
            $table->string('spanish')->nullable(true);
            $table->string('tag')->nullable(false);
            $table->enum('active', ['YES', 'NO'])->nullable(false);

            $table->timestamps();
        });
        Schema::table('categories', function (Blueprint $table)
        {
            $table->foreign('parent_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
