<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'negotiation_override_quota')) {
                $table->unsignedInteger('negotiation_override_quota')->default(0)
                    ->after('status')
                    ->comment('Tambahan kuota negosiasi dari Manager Override');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('negotiation_override_quota');
        });
    }
};
