<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'reference_no')) {
                $table->string('reference_no', 50)->nullable()->after('booking_no');
            }
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (Schema::hasColumn('bookings', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
            if (Schema::hasColumn('bookings', 'phone')) {
                $table->dropColumn('phone');
            }
        });

        if (Schema::hasColumn('bookings', 'booking_no') && Schema::hasColumn('bookings', 'reference_no')) {
            DB::statement("UPDATE `bookings` SET `reference_no` = `booking_no` WHERE `reference_no` IS NULL OR `reference_no` = ''");
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `bookings` MODIFY `booking_type` ENUM('flight','hotel','visa','group','insurance','umrah','umrah_group','package') NOT NULL");
        }

        if (!Schema::hasColumn('bookings', 'reference_no')) {
            return;
        }

        $hasUnique = collect(DB::select("SHOW INDEX FROM `bookings` WHERE Key_name = 'bookings_reference_no_unique'"))->isNotEmpty();
        if (!$hasUnique) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->unique('reference_no', 'bookings_reference_no_unique');
            });
        }

        $indexNames = collect(DB::select("SHOW INDEX FROM `bookings`"))->pluck('Key_name')->all();
        Schema::table('bookings', function (Blueprint $table) use ($indexNames) {
            if (!in_array('bookings_agent_id_index', $indexNames, true) && Schema::hasColumn('bookings', 'agent_id')) {
                $table->index('agent_id', 'bookings_agent_id_index');
            }
            if (!in_array('bookings_user_id_index', $indexNames, true) && Schema::hasColumn('bookings', 'user_id')) {
                $table->index('user_id', 'bookings_user_id_index');
            }
            if (!in_array('bookings_status_index', $indexNames, true) && Schema::hasColumn('bookings', 'status')) {
                $table->index('status', 'bookings_status_index');
            }
            if (!in_array('bookings_booking_type_index', $indexNames, true) && Schema::hasColumn('bookings', 'booking_type')) {
                $table->index('booking_type', 'bookings_booking_type_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'agent_id')) {
                $table->dropForeign(['agent_id']);
                $table->dropIndex('bookings_agent_id_index');
                $table->dropColumn('agent_id');
            }
            if (Schema::hasColumn('bookings', 'reference_no')) {
                $table->dropUnique('bookings_reference_no_unique');
                $table->dropColumn('reference_no');
            }
            if (Schema::hasColumn('bookings', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};

