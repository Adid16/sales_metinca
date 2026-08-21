<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

echo "=== TESTING STRICT REASON FETCH PER ITEM ===\n";

$pos = PurchaseOrder::with(['internals'])->get();

foreach ($pos as $po) {
    echo "PO ID: {$po->id} ({$po->po_no})\n";
    foreach ($po->internals as $idx => $internalItem) {
        $targetPoNo = $po->po_no . '-' . ($idx + 1);
        
        $itemContract = Contract::where('purchase_order_internal_id', $internalItem->id)->latest('id')->first()
            ?? Contract::where('order_no', $targetPoNo)->latest('id')->first();
            
        $status = $itemContract->status ?? 'none';
        $reason = ($itemContract && $status === 'rejected') ? $itemContract->alasan_penolakan : null;
        
        echo "  Item #{$idx} [{$internalItem->item}]: Contract Status='{$status}', Reason='" . ($reason ?? 'NONE') . "'\n";
    }
}
