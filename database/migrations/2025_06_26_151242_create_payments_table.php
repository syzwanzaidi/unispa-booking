<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('payment_id');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->date('payment_date');

            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
