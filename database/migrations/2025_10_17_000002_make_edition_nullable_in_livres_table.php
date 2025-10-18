<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // On MySQL, ensure 'edition' is nullable to avoid 1364 errors
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `livres` MODIFY `edition` VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        // No-op: we won't force NOT NULL back to avoid breaking inserts
    }
};


