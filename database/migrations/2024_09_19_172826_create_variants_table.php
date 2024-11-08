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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('new', 1)->default('')->nullable(false);
            $table->string('old', 1)->default('')->nullable(false);
            $table->string('old2', 1)->default('')->nullable(false);
            $table->string('old3', 1)->default('')->nullable(false);
            $table->string('old4', 1)->default('')->nullable(false);
            $table->string('old5', 1)->default('')->nullable(false);
            $table->dateTime('deleted_at')->nullable();
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
