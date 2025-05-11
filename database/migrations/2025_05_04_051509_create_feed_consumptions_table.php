<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flock_id')->constrained()->onDelete('cascade');
            $table->foreignId('feed_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_kg', 8, 2);
            $table->date('consumption_date');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_consumptions');
    }
}
