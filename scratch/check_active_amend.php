<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;

echo "=== CHECKING ALL POS STATUS & ITEMS ===\n";
foreach (PurchaseOrder::with(['internals.contract', 'contracts'])->get() as $po) {
    $hasActivePoAmend = in_array(strtolower($po->status), ['amandement', 'amandement_pending']);
    $hasActiveItemAmend = $po->internals->contains(function ($item) {
        return in_array(strtolower($item->status ?? ''), ['amandement', 'amandement_pending'])
            || ($item->contract && in_array(strtolower($item->contract->status ?? ''), ['amandement_pending']));
    });
    $hasActiveContractAmend = $po->contracts->contains(function ($c) {
        return in_array(strtolower($c->status ?? ''), ['amandement_pending']);
    });

    $isActiveAmend = $hasActivePoAmend || $hasActiveItemAmend || $hasActiveContractAmend;

    echo "PO #{$po->id} [{$po->po_no}] - PO Status: {$po->status}\n";
    echo "  - Is Active Amend: " . ($isActiveAmend ? 'YES (PO Amandemen Tab)' : 'NO (PO Baru Tab)') . "\n";
    foreach ($po->internals as $item) {
        echo "    * Item: {$item->item} | Item Status: {$item->status} | Contract Status: " . ($item->contract->status ?? 'none') . "\n";
    }
}
