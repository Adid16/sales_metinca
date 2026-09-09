<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$contract = Contract::first();
if (!$contract) {
    echo "No contracts found\n";
    exit(0);
}

$salesUser = App\Models\User::where('divisi', 'sales')->first();
$qcUser = App\Models\User::where('divisi', 'quality')->first();

try {
    $controller = app(\App\Http\Controllers\ContractController::class);

    // Test 1: Sales User
    \Illuminate\Support\Facades\Auth::login($salesUser);
    $viewSales = $controller->show($contract);
    $htmlSales = $viewSales->render();
    $hasRpSales = strpos($htmlSales, 'Rp ') !== false;
    echo "[PASS] Sales User ({$salesUser->name}): Rendered successfully! (Has 'Rp ': " . ($hasRpSales ? 'YES' : 'NO') . ")\n";

    // Test 2: Quality User (Non-sales)
    \Illuminate\Support\Facades\Auth::login($qcUser);
    $viewQc = $controller->show($contract);
    $htmlQc = $viewQc->render();
    $hasLockMask = strpos($htmlQc, 'Khusus Divisi Sales') !== false;
    echo "[PASS] Quality User ({$qcUser->name}): Rendered successfully! (Has 'Khusus Divisi Sales' Mask: " . ($hasLockMask ? 'YES' : 'NO') . ")\n";
} catch (\Throwable $e) {
    echo "[ERROR] " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
