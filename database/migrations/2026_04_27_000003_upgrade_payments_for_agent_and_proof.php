<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->after('booking_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payments', 'method')) {
                $table->string('method', 50)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->after('method');
            }
            if (!Schema::hasColumn('payments', 'proof_image')) {
                $table->string('proof_image')->nullable()->after('transaction_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'proof_image')) {
                $table->dropColumn('proof_image');
            }
            if (Schema::hasColumn('payments', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('payments', 'method')) {
                $table->dropColumn('method');
            }
            if (Schema::hasColumn('payments', 'agent_id')) {
                $table->dropForeign(['agent_id']);
                $table->dropColumn('agent_id');
            }
        });
    }
};

