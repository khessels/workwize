<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // https://blog.ghanshyamdigital.com/building-a-self-referencing-model-in-laravel-a-step-by-step-guide
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label')->nullable(false);
            $table->string('data')->nullable(true);
            $table->string('icon')->nullable(true);
            $table->enum('active', ['YES', 'NO'])->nullable(false);

            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table)
        {
            $table->foreign('parent_id')
                ->references('id')->on('categories')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id']);
        });
    }
};
