<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('sent', 'review', 'amandement', 'amandement_pending', 'contract', 'production', 'ship') DEFAULT 'sent'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('sent', 'review', 'amandement', 'contract', 'production', 'ship') DEFAULT 'sent'");
    }
};