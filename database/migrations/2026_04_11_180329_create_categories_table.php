<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Category name (Umrah, UAE)
            $table->enum('type', ['flight','umrah','tour']); // Type
            $table->text('description')->nullable(); // Optional description
            $table->enum('status', ['active','inactive'])->default('active'); // Status
            $table->timestamps(); // created_at + updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};