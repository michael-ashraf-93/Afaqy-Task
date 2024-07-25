<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        if (!$this->checkIfExists('vehicles_expenses_view')) {
            DB::statement('
            CREATE VIEW vehicles_expenses_view AS
            SELECT
                v.id AS vehicle_id,
                v.name AS vehicle_name,
                v.plate_number AS vehicle_plate_number,
                "fuel" AS type,
                fe.cost AS cost,
                fe.entry_date AS created_at
            FROM fuel_entries fe
            JOIN vehicles v ON fe.vehicle_id = v.id
            UNION ALL
            SELECT
                v.id AS vehicle_id,
                v.name AS vehicle_name,
                v.plate_number AS vehicle_plate_number,
                "insurance" AS type,
                ip.amount AS cost,
                ip.contract_date AS created_at
            FROM insurance_payments ip
            JOIN vehicles v ON ip.vehicle_id = v.id
            UNION ALL
            SELECT
                v.id AS vehicle_id,
                v.name AS vehicle_name,
                v.plate_number AS vehicle_plate_number,
                "service" AS type,
                s.total AS cost,
                s.created_at AS created_at
            FROM services s
            JOIN vehicles v ON s.vehicle_id = v.id;
        ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vehicles_expenses_view');
    }

    private function checkIfExists(string $tableName): bool
    {
        $exists = DB::select("
                SELECT
                    TABLE_NAME
                FROM
                    information_schema.tables
                WHERE
                    TABLE_TYPE = 'VIEW'
                    AND TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = ?
                ", [$tableName]);
        return !empty($exists);
    }
};
