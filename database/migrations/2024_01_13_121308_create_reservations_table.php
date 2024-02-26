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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('court_id');
            $table->unsignedBigInteger('rental_session_id');
            $table->date('date');
            // $table->dateTime('end_time');
            // $table->string('payment_status')->default('pending');
            // $table->decimal('total_amount', 10, 2);


            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('cascade');
            $table->foreign('rental_session_id')->references('id')->on('rental_sessions')->onDelete('NO ACTION');
       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
