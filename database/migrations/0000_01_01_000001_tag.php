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
        Schema::create('topic', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->timestamps();
        });

        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->unsignedBigInteger('topic_id')->nullable(false);
            $table->foreign('topic_id')
                ->references('id')
                ->on('topic')
                ->onDelete(' cascade');
            $table->enum('visible', ['YES','NO'])->nullable(false)->default('NO');
            $table->dateTime('enables_at')->nullable(true);
            $table->dateTime('expires_at')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic');
        Schema::dropIfExists('tag');
    }
};
