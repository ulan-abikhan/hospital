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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments');
            $table->string('job');
            $table->foreignId('monday')->nullable()->default(null);
            $table->foreignId('tuesday')->nullable()->default(null);
            $table->foreignId('wednesday')->nullable()->default(null);
            $table->foreignId('thursday')->nullable()->default(null);
            $table->foreignId('friday')->nullable()->default(null);
            $table->foreignId('saturday')->nullable()->default(null);
            $table->foreignId('sunday')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('monday')->references('id')->on('schedule_days');
            $table->foreign('tuesday')->references('id')->on('schedule_days');
            $table->foreign('wednesday')->references('id')->on('schedule_days');
            $table->foreign('thursday')->references('id')->on('schedule_days');
            $table->foreign('friday')->references('id')->on('schedule_days');
            $table->foreign('saturday')->references('id')->on('schedule_days');
            $table->foreign('sunday')->references('id')->on('schedule_days');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
