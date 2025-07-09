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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('rental_history_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('amount');
            $table->string('description')->nullable(); 
            $table->string('invoice_id')->nullable();
            $table->string('tripay_reference')->nullable();
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'expired', 'cancelled'])->default('unpaid');
            $table->string('type')->default('income');
            $table->string('payment_method')->nullable();
            $table->boolean('is_penalty')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
