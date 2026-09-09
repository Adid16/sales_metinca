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
        Schema::table('contracts', function (Blueprint $table) {
            $table->text('sales_reject_reason')->nullable()->after('sales_approved_at');
            $table->timestamp('sales_rejected_at')->nullable()->after('sales_reject_reason');

            $table->text('quality_reject_reason')->nullable()->after('quality_approved_at');
            $table->timestamp('quality_rejected_at')->nullable()->after('quality_reject_reason');

            $table->text('ppc_reject_reason')->nullable()->after('ppc_approved_at');
            $table->timestamp('ppc_rejected_at')->nullable()->after('ppc_reject_reason');

            $table->text('dev_engineering_reject_reason')->nullable()->after('dev_engineering_approved_at');
            $table->timestamp('dev_engineering_rejected_at')->nullable()->after('dev_engineering_reject_reason');

            $table->string('rejected_by_dept')->nullable()->after('dev_engineering_rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn([
                'sales_reject_reason',
                'sales_rejected_at',
                'quality_reject_reason',
                'quality_rejected_at',
                'ppc_reject_reason',
                'ppc_rejected_at',
                'dev_engineering_reject_reason',
                'dev_engineering_rejected_at',
                'rejected_by_dept'
            ]);
        });
    }
};
