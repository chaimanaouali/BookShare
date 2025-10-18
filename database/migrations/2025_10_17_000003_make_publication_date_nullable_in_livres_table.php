<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure publication_date is nullable to avoid 1364 errors
        if (DB::getDriverName() === 'mysql') {
            // Convert to DATE NULL (keeps existing values)
            DB::statement("ALTER TABLE `livres` MODIFY `publication_date` DATE NULL");
        }
    }

    public function down(): void
    {
        // No rollback to NOT NULL to avoid breaking inserts; leave as is
    }
};


