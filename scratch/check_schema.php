<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== DESCRIBE negotiate ===\n";
foreach (DB::select("DESCRIBE negotiate") as $col) {
    echo "{$col->Field} ({$col->Type}) Null: {$col->Null} Default: {$col->Default}\n";
}

echo "\n=== DESCRIBE quotation_items ===\n";
foreach (DB::select("DESCRIBE quotation_items") as $col) {
    echo "{$col->Field} ({$col->Type}) Null: {$col->Null} Default: {$col->Default}\n";
}

echo "\n=== DESCRIBE quotations ===\n";
foreach (DB::select("DESCRIBE quotations") as $col) {
    echo "{$col->Field} ({$col->Type})\n";
}

echo "\n=== DESCRIBE articles ===\n";
foreach (DB::select("DESCRIBE articles") as $col) {
    echo "{$col->Field} ({$col->Type})\n";
}
