<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make defi_id nullable to align with controller validation and forms
        // SQLite does not support MODIFY; skip on sqlite as it treats columns as nullable by default unless explicitly NOT NULL
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `book_events` MODIFY `defi_id` BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        // Revert to NOT NULL (may fail if nulls exist; ensure cleanup before rollback)
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE `book_events` MODIFY `defi_id` BIGINT UNSIGNED NOT NULL');
    }
};



