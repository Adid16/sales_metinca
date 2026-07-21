<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah kolom status ke VARCHAR(50) agar bebas menampung 'rejected', 'approved', dll.
        DB::statement("ALTER TABLE contracts MODIFY COLUMN status VARCHAR(50) DEFAULT 'created'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE contracts MODIFY COLUMN status VARCHAR(50) DEFAULT 'created'");
    }
};