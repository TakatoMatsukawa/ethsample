<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false)->default('');
            $table->string('writer', 50)->nullable(false)->default('');
            $table->string('era', 100)->nullable(false)->default('');
            $table->text('description')->nullable(false);
            $table->string('thumbnail_original_name', 50)->nullable(false)->default('');
            $table->string('thumbnail_file_name', 50)->nullable(false)->default('');
            $table->unsignedInteger('license')->nullable(false)->default(0);
            $table->string('unique_id', 10)->nullable(false)->default('');
            $table->unsignedInteger('public_flg')->nullable(false)->default(0);
            $table->unsignedInteger('iiif_flg')->nullable(false)->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manuscripts');
    }
};
