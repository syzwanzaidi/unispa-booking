<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->unsignedInteger('package_id');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
            $table->integer('item_pax')->default(1);
            $table->time('item_start_time');
            $table->integer('item_duration_minutes');
            $table->decimal('item_price', 10, 2);
            $table->string('for_whom_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
