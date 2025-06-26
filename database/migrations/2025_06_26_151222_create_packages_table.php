<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('package_id');
            $table->string('package_name');
            $table->text('package_desc')->nullable();
            $table->decimal('package_price', 8, 2);
            $table->string('duration')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
