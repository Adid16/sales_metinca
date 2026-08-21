<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== RESETTING ALL PURCHASE ORDERS, PO INTERNALS & CONTRACT DATA ===\n";

Schema::disableForeignKeyConstraints();

$tablesToClear = [
    'contract_requirements',
    'contracts',
    'purchase_order_internals',
    'purchase_orders',
];

foreach ($tablesToClear as $table) {
    if (Schema::hasTable($table)) {
        DB::table($table)->truncate();
        echo "✓ Table '{$table}' cleared.\n";
    }
}

// Reset quotation status so fresh POs can be created
if (Schema::hasTable('quotations')) {
    DB::table('quotations')->update([
        'status' => 'sent',
        'accepted_date' => null
    ]);
    echo "✓ Table 'quotations' status reset.\n";
}

Schema::enableForeignKeyConstraints();

echo "=== RESET COMPLETED SUCCESSFULLY ===\n";
