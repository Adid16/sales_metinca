<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

echo "=== ALL CONTRACT RECORDS IN DB ===\n";

$contracts = Contract::all();
foreach ($contracts as $c) {
    echo "ID: {$c->id} | PO Internal ID: {$c->purchase_order_internal_id} | Order No: {$c->order_no} | Status: '{$c->status}' | Alasan Amandemen: '{$c->alasan_amandemen}' | Alasan Penolakan: '{$c->alasan_penolakan}'\n";
}
