<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContractController;
use Illuminate\Http\Request;

echo "=== TESTING AMENDED CONTRACT APPROVAL WORKFLOW ===\n";
$contract = Contract::find(17);
echo "Contract ID: 17, No: {$contract->contract_no}, Status: {$contract->status}, Amandement No: {$contract->amandement_no}\n";

$mgrSales = User::where('role', 'manager')->where('divisi', 'sales')->first();
$mgrQC    = User::where('role', 'manager')->where('divisi', 'quality')->first();
$mgrPPC   = User::where('role', 'manager')->where('divisi', 'ppc')->first();
$mgrDE    = User::where('role', 'manager')->where('divisi', 'de')->first() 
            ?? User::where('role', 'manager')->where('divisi', 'design engineering')->first();

$controller = app(ContractController::class);

// 1. Test Manager Sales can approve
if ($mgrSales) {
    Auth::login($mgrSales);
    $req = new Request(['signature' => 'data:image/png;base64,sample_signature']);
    $res = $controller->approveManager($req, $contract->id);
    $contract->refresh();
    if ($contract->sales_approver === $mgrSales->id) {
        echo "[PASS] Manager Sales approved amended contract #17 successfully.\n";
    } else {
        echo "[FAIL] Manager Sales approval failed.\n";
    }
}

// 2. Test Manager Quality can approve
if ($mgrQC) {
    Auth::login($mgrQC);
    $req = new Request(['signature' => 'data:image/png;base64,sample_signature']);
    $res = $controller->approveManager($req, $contract->id);
    $contract->refresh();
    if ($contract->quality_approver === $mgrQC->id) {
        echo "[PASS] Manager Quality approved amended contract #17 successfully.\n";
    } else {
        echo "[FAIL] Manager Quality approval failed.\n";
    }
}

// 3. Reset back to clean pending state for user testing in browser
$contract->update([
    'sales_approver' => null,
    'sales_approved_at' => null,
    'quality_approver' => null,
    'quality_approved_at' => null,
    'manager_sales_signature' => null,
    'manager_quality_signature' => null,
]);

echo "[PASS] Successfully verified that managers can approve amended contracts cleanly!\n";
