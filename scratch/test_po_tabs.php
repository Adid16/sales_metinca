<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$sales = User::where('role', 'staff')->where('divisi', 'sales')->first() ?? User::first();
Auth::login($sales);

$poController = new \App\Http\Controllers\PurchaseOrderController();

echo "=== TAB: PO AMANDEMEN (REVISI) ===\n";
$viewAmend = $poController->index(new Request(['type' => 'amandement']));
$amendPos = $viewAmend->getData()['pos'];
echo "Total in PO Amandemen tab: " . $amendPos->total() . "\n";
foreach ($amendPos as $p) {
    echo "  - {$p->po_no} (Status: {$p->status})\n";
}

echo "\n=== TAB: PO BARU (NEW) ===\n";
$viewNew = $poController->index(new Request(['type' => 'new']));
$newPos = $viewNew->getData()['pos'];
echo "Total in PO Baru tab: " . $newPos->total() . "\n";
foreach ($newPos as $p) {
    echo "  - {$p->po_no} (Status: {$p->status})\n";
}
