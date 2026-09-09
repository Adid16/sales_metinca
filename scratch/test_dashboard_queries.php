<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\RequestProject;
use App\Models\Quotation;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;

echo "=== TESTING DASHBOARD METRICS QUERIES ===\n";

$reqCount = RequestProject::count();
$reqPending = RequestProject::whereDoesntHave('quotation')->count();
echo "Request Total: {$reqCount} (Belum Jadi Quotation: {$reqPending})\n";

$qtCount = Quotation::count();
$qtNeedProcess = Quotation::whereIn('status', ['draft', 'sent', 'waiting_manager_approval'])->count();
echo "Quotation Total: {$qtCount} (Perlu Diproses: {$qtNeedProcess})\n";

$poExtCount = PurchaseOrder::count();
$poExtUnprocessed = PurchaseOrder::whereDoesntHave('internals')->count();
echo "PO External Total: {$poExtCount} (Belum Diproses Internal: {$poExtUnprocessed})\n";

$poIntCount = PurchaseOrderInternal::count();
echo "PO Internal Total Items: {$poIntCount}\n";

$amendPending = Contract::where('status', 'amandement_pending')->count()
    + PurchaseOrder::where('status', 'amandement_pending')->whereDoesntHave('contracts')->count();
echo "PO Amandemen Pending: {$amendPending}\n";

$contractRejected = Contract::where('status', 'rejected')->count();
echo "Kontrak Ditolak (Rejected): {$contractRejected}\n";

echo "ALL QUERIES RUN PERFECTLY!\n";
