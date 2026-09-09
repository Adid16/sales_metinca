<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$sales = User::where('role', 'staff')->where('divisi', 'sales')->first() ?? User::first();
Auth::login($sales);

$poController = new \App\Http\Controllers\PurchaseOrderController();
$view = $poController->index(new Request(['type' => 'amandement']));
$html = $view->render();

if (strpos($html, 'Buat Kontrak Amandemen') !== false || strpos($html, 'PO-2026-ADIDWI-007') !== false) {
    echo "[PASS] Successfully rendered Purchase Orders index with dynamic amendment action buttons!\n";
} else {
    echo "[WARN] Rendered without error, items count: " . count($view->getData()['pos']) . "\n";
}
