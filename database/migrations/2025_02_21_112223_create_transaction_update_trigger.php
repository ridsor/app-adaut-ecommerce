<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_transaction_update
            AFTER UPDATE ON transactions
            FOR EACH ROW
            BEGIN
                DECLARE new_status VARCHAR(20);

                -- Tentukan status baru berdasarkan status transaksi
                SET new_status = 
                    CASE 
                        WHEN NEW.status = "pending" THEN "pending"
                        WHEN NEW.status = "success" THEN "completed"
                        WHEN NEW.status = "expired" THEN "cancelled"
                        WHEN NEW.status = "failed" THEN "cancelled"
                        ELSE NULL
                    END;

                -- Update hanya jika status baru tidak NULL
                IF new_status IS NOT NULL THEN
                    UPDATE orders SET status = new_status WHERE id = OLD.order_id;
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_transaction_update');
    }
};
