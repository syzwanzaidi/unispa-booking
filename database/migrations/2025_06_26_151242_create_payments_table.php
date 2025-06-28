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
            $table->increments('payment_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('payment_status')->default('Pending');
            $table->timestamp('payment_date')->useCurrent();
            $table->unsignedInteger('invoice_id');
            $table->foreign('invoice_id')
                  ->references('invoice_id')
                  ->on('invoices')
                  ->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
