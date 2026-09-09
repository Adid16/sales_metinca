<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contract = App\Models\Contract::where('contract_no', 'CT-2026-009')->first();
$basePoNo = preg_replace('/-\d+$/', '', $contract->order_no);
$purchaseOrder = $contract->purchaseOrder ?? ($contract->internalItem ? $contract->internalItem->purchaseOrder : null) ?? App\Models\PurchaseOrder::where('po_no', $basePoNo)->first();
$quotation = $contract->quotation ?? ($purchaseOrder ? $purchaseOrder->quotation : null) ?? App\Models\Quotation::find($contract->quotation_id);
$requestProject = $quotation ? $quotation->request : null;
$salesPic = $contract->sales_pic ?? ($requestProject && $requestProject->assignment ? $requestProject->assignment->sales : null);

$orderActivities = App\Models\HistoryActivity::where(function($q) use ($contract, $basePoNo, $quotation) {
    $q->where('activity', 'LIKE', '%' . $contract->order_no . '%')
      ->orWhere('activity', 'LIKE', '%' . $basePoNo . '%')
      ->orWhere('activity', 'LIKE', '%' . $contract->contract_no . '%');
    if ($quotation) {
        $q->orWhere('activity', 'LIKE', '%' . $quotation->quotation_no . '%');
    }
})->with('user')->orderBy('activity_time', 'asc')->get();

$amendmentEvents = $orderActivities->filter(function($act) {
    return stripos($act->activity, 'amandemen') !== false;
});

echo "Amendment events found:\n";
foreach ($amendmentEvents as $ev) {
    echo " - [{$ev->activity_time}] by " . ($ev->user->name ?? '-') . ": {$ev->activity}\n";
}
