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
        Schema::create('work_accidents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('age');
            $table->string('department');
            $table->date('occurred_on');
            $table->text('chronology');
            $table->string('injured_body_part');
            $table->text('action_taken');
            $table->text('therapy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_accidents');
    }
};
