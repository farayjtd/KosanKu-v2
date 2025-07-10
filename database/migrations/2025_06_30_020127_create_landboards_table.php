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
        Schema::create('landboards', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');

            $table->string('name')->nullable();           
            $table->string('phone')->nullable();    
            $table->string('kost_name')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('full_address')->nullable();

            $table->bigInteger('late_fee_amount')->default(0);
            $table->tinyInteger('late_fee_days')->default(0);
            $table->bigInteger('moveout_penalty_amount')->default(0);
            $table->bigInteger('room_change_penalty_amount')->default(0);

            $table->boolean('is_penalty_enabled')->default(false);
            $table->boolean('is_penalty_on_moveout')->default(false);
            $table->boolean('is_penalty_on_room_change')->default(false);
            $table->tinyInteger('decision_days_before_end')->default(5);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landboards');
    }
};
