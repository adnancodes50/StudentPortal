<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateUsersTable();
        $this->updateBookingsTable();
        $this->ensureServiceTables();
        $this->ensureUmrahTables();
        $this->ensureWalletTransactionsTable();
        $this->updatePaymentsTable();
    }

    private function updateUsersTable(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'agent', 'user'])->default('user')->after('password');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable();
            }

            if (! Schema::hasColumn('users', 'passport_no')) {
                $table->string('passport_no', 50)->nullable();
            }

            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
        });
    }

    private function updateBookingsTable(): void
    {
        if (! Schema::hasTable('bookings')) {
            return;
        }

        // Enum changes are database-specific; raw SQL keeps this migration as ALTER-style.
        DB::statement("ALTER TABLE bookings MODIFY booking_type ENUM('flight','hotel','visa','group','insurance','umrah','umrah_group') NOT NULL");

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }

            if (Schema::hasColumn('bookings', 'customer_name')) {
                $table->dropColumn('customer_name');
            }

            if (Schema::hasColumn('bookings', 'phone')) {
                $table->dropColumn('phone');
            }

            if (! Schema::hasColumn('bookings', 'reference_no')) {
                $table->string('reference_no', 100)->nullable()->after('booking_no');
            }

            if (! Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        DB::statement("UPDATE bookings SET reference_no = CONCAT('REF-', id) WHERE reference_no IS NULL OR reference_no = ''");

        if (! $this->hasUniqueIndex('bookings', 'bookings_reference_no_unique')) {
            DB::statement('ALTER TABLE bookings ADD UNIQUE bookings_reference_no_unique (reference_no)');
        }

        $this->addIndexIfMissing('bookings', 'bookings_agent_id_index', 'agent_id');
        $this->addIndexIfMissing('bookings', 'bookings_user_id_index', 'user_id');
        $this->addIndexIfMissing('bookings', 'bookings_status_index', 'status');
        $this->addIndexIfMissing('bookings', 'bookings_booking_type_index', 'booking_type');
    }

    private function ensureServiceTables(): void
    {
        $serviceTables = [
            'flight_bookings',
            'hotel_bookings',
            'visa_applications',
            'group_bookings',
            'insurance_bookings',
        ];

        foreach ($serviceTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                    $table->timestamps();
                });
                continue;
            }

            if (! Schema::hasColumn($tableName, 'booking_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
                });
            }
        }
    }

    private function ensureUmrahTables(): void
    {
        if (! Schema::hasTable('umrah_packages')) {
            Schema::create('umrah_packages', function (Blueprint $table) {
                $table->id();
                $table->string('package_name');
                $table->unsignedInteger('total_days');
                $table->decimal('price_per_person', 12, 2);
                $table->string('makkah_hotel');
                $table->string('madinah_hotel');
                $table->unsignedInteger('group_size')->default(1);
                $table->date('departure_date');
                $table->date('return_date');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('umrah_bookings')) {
            Schema::create('umrah_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->foreignId('package_id')->constrained('umrah_packages')->cascadeOnDelete();
                $table->timestamps();
            });
            return;
        }

        Schema::table('umrah_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('umrah_bookings', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            }

            if (! Schema::hasColumn('umrah_bookings', 'package_id')) {
                $table->foreignId('package_id')->nullable()->constrained('umrah_packages')->nullOnDelete();
            }
        });
    }

    private function ensureWalletTransactionsTable(): void
    {
        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 12, 2);
                $table->decimal('balance_after', 12, 2);
                $table->string('reference_type')->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->timestamps();
            });
            return;
        }

        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('wallet_transactions', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('wallet_transactions', 'type')) {
                $table->enum('type', ['credit', 'debit']);
            }

            if (! Schema::hasColumn('wallet_transactions', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('wallet_transactions', 'balance_after')) {
                $table->decimal('balance_after', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('wallet_transactions', 'reference_type')) {
                $table->string('reference_type')->nullable();
            }

            if (! Schema::hasColumn('wallet_transactions', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable();
            }
        });
    }

    private function updatePaymentsTable(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'agent_id')) {
                $table->foreignId('agent_id')->nullable()->after('booking_id')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('payments', 'method')) {
                $table->string('method', 50)->nullable()->after('amount');
            }

            if (! Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->after('method');
            }

            if (! Schema::hasColumn('payments', 'proof_image')) {
                $table->string('proof_image')->nullable()->after('status');
            }
        });
    }

    private function addIndexIfMissing(string $table, string $indexName, string $column): void
    {
        if (! $this->hasIndex($table, $indexName)) {
            DB::statement("ALTER TABLE {$table} ADD INDEX {$indexName} ({$column})");
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();
        $result = DB::selectOne(
            'SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$database, $table, $indexName]
        );

        return $result !== null;
    }

    private function hasUniqueIndex(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();
        $result = DB::selectOne(
            'SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? AND non_unique = 0 LIMIT 1',
            [$database, $table, $indexName]
        );

        return $result !== null;
    }

    public function down(): void
    {
        // This migration is intentionally forward-only because it standardizes
        // a live schema with conditional ALTER statements.
    }
};
