<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contract = App\Models\Contract::where('contract_no', 'CT-2026-009')->first();
if (!$contract) {
    $contract = App\Models\Contract::latest()->first();
}

echo "Testing data collection for Contract ID: " . $contract->id . " No: " . $contract->contract_no . "\n";

// 1. Resolve base PO and related contracts
$basePoNo = preg_replace('/-\d+$/', '', $contract->order_no);
$po = $contract->purchaseOrder ?? ($contract->internalItem ? $contract->internalItem->purchaseOrder : null) ?? App\Models\PurchaseOrder::where('po_no', $basePoNo)->first();

echo "Base PO: " . ($po ? $po->po_no : 'None') . "\n";

// 2. Resolve Quotation & Request Project
$quotation = $contract->quotation ?? ($po ? $po->quotation : null) ?? App\Models\Quotation::find($contract->quotation_id);
$requestProject = $quotation ? $quotation->request : null;
$salesPic = $contract->sales_pic ?? ($requestProject && $requestProject->assignment ? $requestProject->assignment->sales : null);

echo "Quotation: " . ($quotation ? $quotation->quotation_no : 'None') . "\n";
echo "Request Project: " . ($requestProject ? '#' . $requestProject->id . ' - ' . $requestProject->subject : 'None') . "\n";
echo "Sales PIC: " . ($salesPic ? $salesPic->name : 'Unassigned') . "\n";

// 3. Negotiations
$negotiations = collect();
if ($quotation) {
    $negotiations = App\Models\Negotiate::where('quotation_id', $quotation->id)
        ->with(['user', 'manager'])
        ->orderBy('created_at', 'asc')
        ->get();
}
echo "Negotiations count: " . $negotiations->count() . "\n";

// 4. All internal items in this PO
$internalItems = $po ? $po->internals()->with('contract')->get() : ($contract->internalItem ? collect([$contract->internalItem]) : collect());
echo "Internal Items count: " . $internalItems->count() . "\n";

// 5. All related contracts for this PO
$relatedContracts = App\Models\Contract::where('order_no', 'like', $basePoNo . '%')
    ->orWhere('id', $contract->id)
    ->with(['internalItem', 'salesApprover', 'qualityApprover', 'ppcApprover', 'devEngineeringApprover'])
    ->get();
echo "Related Contracts count: " . $relatedContracts->count() . "\n";

// 6. Comprehensive Activity Log & Amendment Events
$orderActivities = App\Models\HistoryActivity::where(function($q) use ($contract, $basePoNo, $quotation) {
    $q->where('activity', 'LIKE', '%' . $contract->order_no . '%')
      ->orWhere('activity', 'LIKE', '%' . $basePoNo . '%')
      ->orWhere('activity', 'LIKE', '%' . $contract->contract_no . '%');
    if ($quotation) {
        $q->orWhere('activity', 'LIKE', '%' . $quotation->quotation_no . '%');
    }
})->with('user')->orderBy('activity_time', 'desc')->get();

echo "Order Activities count: " . $orderActivities->count() . "\n";

echo "\n[PASS] All data successfully gathered!\n";
