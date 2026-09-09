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
        Schema::table('negotiate', function (Blueprint $table) {
            $table->boolean('requires_manager_approval')->default(false)->after('action');
            $table->string('manager_approval_status', 20)->nullable()->after('requires_manager_approval'); // pending | approved | rejected
            $table->text('manager_approval_note')->nullable()->after('manager_approval_status');
            $table->foreignId('manager_approved_by')->nullable()->constrained('users')->nullOnDelete()->after('manager_approval_note');
            $table->timestamp('manager_approved_at')->nullable()->after('manager_approved_by');
            $table->json('floor_price_snapshot')->nullable()->after('manager_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('negotiate', function (Blueprint $table) {
            $table->dropForeign(['manager_approved_by']);
            $table->dropColumn([
                'requires_manager_approval',
                'manager_approval_status',
                'manager_approval_note',
                'manager_approved_by',
                'manager_approved_at',
                'floor_price_snapshot'
            ]);
        });
    }
};
