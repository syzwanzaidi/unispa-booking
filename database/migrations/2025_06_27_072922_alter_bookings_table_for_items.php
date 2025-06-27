<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'booking_pax', 'booking_time']);
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('payment_method');
            $table->text('notes')->nullable()->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->after('user_id');
            $table->integer('booking_pax')->after('package_id');
            $table->time('booking_time')->after('booking_date');
            $table->foreign('package_id')->references('package_id')->on('packages')->onDelete('cascade');
            $table->dropColumn(['total_amount', 'notes']);
        });
    }
};
