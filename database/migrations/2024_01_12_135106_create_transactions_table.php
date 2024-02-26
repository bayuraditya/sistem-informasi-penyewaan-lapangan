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
        Schema::create('transactions', function (Blueprint $table) {
            //masi belum pasti
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('total_amount');
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['authorize', 'capture', 'settlement','deny','pending','cancel','refund','partial_refund','chargeback','partial_chargeback','expire','failure'])->default('pending');
            $table->dateTime('transaction_time')->nullable();
            $table->dateTime('settlement_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
