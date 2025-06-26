<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('booking_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('package_id');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->integer('booking_pax');
            $table->string('booking_status')->default('Pending');
            $table->string('payment_method');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
