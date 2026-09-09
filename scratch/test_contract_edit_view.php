<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$sales = User::where('role', 'staff')->where('divisi', 'sales')->first() ?? User::where('role', 'staff')->first();
Auth::login($sales);

$contract = Contract::first();
if (!$contract) {
    echo "No contract found to test!\n";
    exit(0);
}

$contract->sales_reject_reason = "ganti di bagian draft angle jadi 118882";
$controller = new \App\Http\Controllers\ContractController();
$view = $controller->edit($contract);
$html = $view->render();

if (strpos($html, 'ganti di bagian draft angle jadi 118882') !== false && strpos($html, 'alert-permanent') !== false) {
    echo "[PASS] Successfully rendered contracts.edit with permanent rejection card banner!\n";
} else {
    echo "[FAIL] Banner not found in HTML\n";
}
