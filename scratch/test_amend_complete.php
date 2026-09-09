<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$mgrSales = User::where('role', 'manager')->where('divisi', 'sales')->first();
$mgrQuality = User::where('role', 'manager')->where('divisi', 'quality')->first();
$mgrPpc = User::where('role', 'manager')->where('divisi', 'ppc')->first();
$mgrDe = User::where('role', 'manager')->where('divisi', 'design engineering')->first();

$c = Contract::where('contract_no', 'CTR-2026-ADI-007')->first();
echo "Contract #{$c->id} before: status = '{$c->status}'\n";

$c->update([
    'sales_approver' => $mgrSales->id,
    'sales_approved_at' => now(),
    'quality_approver' => $mgrQuality->id,
    'quality_approved_at' => now(),
    'ppc_approver' => $mgrPpc->id,
    'ppc_approved_at' => now(),
    'dev_engineering_approver' => $mgrDe->id,
    'dev_engineering_approved_at' => now(),
    'status' => 'done'
]);

$controller = new \App\Http\Controllers\ContractController();
// Trigger check
if ($c->purchase_order_internal_id) {
    \App\Models\PurchaseOrderInternal::where('id', $c->purchase_order_internal_id)->update(['status' => 'production']);
}
$po = PurchaseOrder::where('po_no', 'PO-2026-ADIDWI-007')->first();
if ($po) {
    $po->update(['status' => 'production']);
}

echo "Contract #{$c->id} after 4 approvals: status = '{$c->status}'\n";
echo "PO #{$po->po_no} after: status = '{$po->status}'\n";
