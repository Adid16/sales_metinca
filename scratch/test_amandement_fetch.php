<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

echo "=== TESTING AMANDEMENT CONTRACTS FETCH ===\n";

$pendingContracts = Contract::with(['customer', 'quotation', 'purchaseOrderInternal.purchaseOrder.customer'])
    ->where('status', 'amandement_pending')
    ->latest('updated_at')
    ->get();

echo "Found " . $pendingContracts->count() . " pending amandement contracts.\n\n";

foreach ($pendingContracts as $c) {
    $item = $c->purchaseOrderInternal;
    $po = $c->purchase_order ?? ($item ? $item->purchaseOrder : null);
    $customer = $c->customer ?? ($po ? $po->customer : null);
    
    echo "Contract ID: {$c->id}\n";
    echo "  - Order No: {$c->order_no}\n";
    echo "  - Alasan Amandemen: '{$c->alasan_amandemen}'\n";
    echo "  - Resolved PO No: " . ($po->po_no ?? 'N/A') . " (ID: " . ($po->id ?? 'N/A') . ")\n";
    echo "  - Resolved Customer Name: " . ($customer->name ?? 'N/A') . "\n";
    echo "  - Resolved Item Name: " . ($item->item ?? 'N/A') . " (" . ($item->qty ?? 0) . " pcs)\n\n";
}
