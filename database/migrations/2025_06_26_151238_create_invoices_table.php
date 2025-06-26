<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('invoice_id');
            $table->decimal('total_price', 10, 2);
            $table->timestamp('generated_at')->useCurrent();

            $table->integer('booking_id')->unsigned();
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
