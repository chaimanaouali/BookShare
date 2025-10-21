<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('livres', 'edition')) {
            Schema::table('livres', function (Blueprint $table) {
                $table->string('edition')->nullable()->after('isbn');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('livres', 'edition')) {
            Schema::table('livres', function (Blueprint $table) {
                $table->dropColumn('edition');
            });
        }
    }
};


