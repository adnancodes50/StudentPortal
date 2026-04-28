<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('flights', 'category_id')) {
            return;
        }

        Schema::table('flights', function (Blueprint $table) {
            try {
                $table->dropForeign(['category_id']);
            } catch (\Throwable $e) {
                // Ignore when foreign key does not exist in current schema.
            }
        });

        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('flights', 'category_id')) {
            return;
        }

        Schema::table('flights', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('arrival_time')->constrained('categories')->onDelete('cascade');
        });
    }
};

