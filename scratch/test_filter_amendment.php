<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;

echo "=== TESTING ACTIVE AMENDMENT FILTER ===\n";
$queryAmend = PurchaseOrder::query();
$queryAmend->where(function ($q) {
    $q->whereIn('purchase_orders.status', ['amandement', 'amandement_pending'])
        ->orWhereHas('internals', function ($qSub) {
            $qSub->whereIn('purchase_order_internals.status', ['amandement', 'amandement_pending']);
        })
        ->orWhereHas('contracts', function ($qSub) {
            $qSub->whereIn('contracts.status', ['amandement', 'amandement_pending']);
        })
        ->orWhereHas('internalContracts', function ($qSub) {
            $qSub->whereIn('contracts.status', ['amandement', 'amandement_pending']);
        });
});

$amendPos = $queryAmend->with('internals')->get();
echo "Total in [PO Amandemen Revisi]: " . $amendPos->count() . "\n";
foreach ($amendPos as $po) {
    echo "- PO ID: {$po->id} | No: {$po->po_no} | Status: {$po->status} | Items: " . $po->internals->count() . "\n";
}

echo "\n=== TESTING PO BARU FILTER ===\n";
$queryNew = PurchaseOrder::query();
$queryNew->whereNotIn('purchase_orders.status', ['amandement', 'amandement_pending'])
    ->whereDoesntHave('internals', function ($q) {
        $q->whereIn('purchase_order_internals.status', ['amandement', 'amandement_pending']);
    })
    ->whereDoesntHave('contracts', function ($q) {
        $q->whereIn('contracts.status', ['amandement', 'amandement_pending']);
    })
    ->whereDoesntHave('internalContracts', function ($q) {
        $q->whereIn('contracts.status', ['amandement', 'amandement_pending']);
    });

$newPos = $queryNew->with('internals')->get();
echo "Total in [PO Baru]: " . $newPos->count() . "\n";
foreach ($newPos as $po) {
    echo "- PO ID: {$po->id} | No: {$po->po_no} | Status: {$po->status} | Items: " . $po->internals->count() . "\n";
}
