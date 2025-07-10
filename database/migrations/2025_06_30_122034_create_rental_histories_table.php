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
        Schema::create('rental_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->onDelete('cascade');

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->decimal('duration_months', 5, 2); 

            $table->enum('status', ['upcoming', 'active', 'ended', 'transferred'])
                ->default('upcoming');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_histories');
    }
};
