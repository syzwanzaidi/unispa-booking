<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('package_id'); // Primary Key (PK)
            $table->string('package_name');
            $table->text('package_desc')->nullable();
            $table->decimal('package_price', 8, 2); // e.g., 999999.99
            $table->string('duration');
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
