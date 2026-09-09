<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

echo "=== ALL POs ===\n";
$pos = PurchaseOrder::with(['internals.contracts', 'contracts'])->get();
foreach ($pos as $po) {
    $hasDirectContractAmend = $po->contracts()->where('amandement_no', '>', 0)->exists();
    $hasInternalContractAmend = Contract::whereIn('purchase_order_internal_id', $po->internals->pluck('id'))
        ->where('amandement_no', '>', 0)->exists();
    $hasPoStatusAmend = in_array(strtolower($po->status), ['amandement', 'amandement_pending']);
    $hasInternalStatusAmend = $po->internals()->whereIn('status', ['amandement', 'amandement_pending'])->exists();

    echo "PO ID: {$po->id} | No: {$po->po_no} | Status: {$po->status} | Items: " . $po->internals->count() . "\n";
    echo "  - hasDirectContractAmend: " . ($hasDirectContractAmend ? 'YES' : 'NO') . "\n";
    echo "  - hasInternalContractAmend: " . ($hasInternalContractAmend ? 'YES' : 'NO') . "\n";
    echo "  - hasPoStatusAmend: " . ($hasPoStatusAmend ? 'YES' : 'NO') . "\n";
    echo "  - hasInternalStatusAmend: " . ($hasInternalStatusAmend ? 'YES' : 'NO') . "\n";
}
