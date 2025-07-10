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
        Schema::create('penalties', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->onDelete('cascade');

            $table->foreignId('rental_history_id')
                ->nullable()
                ->constrained('rental_histories')
                ->onDelete('cascade');

            $table->enum('type', ['late_fee', 'moveout', 'room_change']);
            $table->bigInteger('amount');
            $table->string('reason')->nullable();
            
            $table->timestamp('issued_at')->useCurrent();
            $table->boolean('resolved')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
