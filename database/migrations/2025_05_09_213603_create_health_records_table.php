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
        Schema::create('health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flock_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('treatment_type');
            $table->string('medication')->nullable();
            $table->string('dosage')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('symptoms')->nullable();
            $table->decimal('treatment_cost', 10, 2)->default(0);
            $table->integer('mortality')->default(0);
            $table->text('notes')->nullable();
            $table->date('next_checkup_date')->nullable();
            $table->string('treated_by')->nullable();
            $table->enum('status', ['active', 'completed', 'pending', 'failed'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health');
    }
};
