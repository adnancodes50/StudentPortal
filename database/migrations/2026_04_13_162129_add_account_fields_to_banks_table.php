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
    Schema::table('banks', function (Blueprint $table) {
        $table->string('account_title')->after('bank_name');
        $table->string('account_number')->after('account_title');
    });
}

public function down(): void
{
    Schema::table('banks', function (Blueprint $table) {
        $table->dropColumn(['account_title', 'account_number']);
    });
}
};
