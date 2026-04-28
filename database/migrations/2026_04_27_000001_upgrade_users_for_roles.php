<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'agent', 'user'])->default('user')->after('type');
            }
            if (!Schema::hasColumn('users', 'passport_no')) {
                $table->string('passport_no', 50)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('country');
            }
        });

        if (Schema::hasColumn('users', 'type') && Schema::hasColumn('users', 'role')) {
            DB::statement("UPDATE `users` SET `role` = `type` WHERE `role` IS NULL OR `role` = ''");
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'passport_no')) {
                $table->dropColumn('passport_no');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};

