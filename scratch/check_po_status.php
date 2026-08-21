<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;
use App\Models\PurchaseOrderInternal;

echo "=== CHECKING ALL PURCHASE ORDERS & CONTRACTS ===\n";

$pos = PurchaseOrder::with(['internals', 'contracts'])->get();
foreach ($pos as $po) {
    echo "PO ID: {$po->id} | PO No: {$po->po_no} | Status: '{$po->status}' | Customer ID: {$po->customer_id}\n";
    foreach ($po->internals as $item) {
        $c = Contract::where('purchase_order_internal_id', $item->id)->orderByDesc('id')->first();
        echo "  - Item ID: {$item->id} | Name: {$item->item} | Contract Status: '" . ($c->status ?? 'NO CONTRACT') . "'\n";
    }
    echo "\n";
}
