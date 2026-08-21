<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Contract;

$pos = PurchaseOrder::with(['customer', 'contracts'])->get();

echo "=== DAFTAR PO DAN DOKUMEN DALAM DATABASE ===\n\n";

foreach ($pos as $po) {
    echo "PO ID: {$po->id} | No PO: {$po->po_no} | Status: {$po->status}\n";
    echo "Customer: " . ($po->customer ? $po->customer->name . " ({$po->customer->email})" : "No Customer") . "\n";
    
    $contracts = Contract::where('order_no', $po->po_no)->orWhereIn('purchase_order_internal_id', $po->internals->pluck('id'))->get();
    echo "Contracts count: " . $contracts->count() . "\n";
    foreach ($contracts as $c) {
        echo "  - Contract ID: {$c->id} | Status: {$c->status} | Alasan Amandemen: {$c->alasan_amandemen} | Alasan Penolakan: {$c->alasan_penolakan}\n";
    }
    echo "---------------------------------------------------------\n";
}
