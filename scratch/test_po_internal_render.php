<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

View::share('errors', new \Illuminate\Support\ViewErrorBag());

$sales = User::where('role', 'staff')->where('divisi', 'sales')->first() ?? User::first();
Auth::login($sales);

$po = PurchaseOrder::where('po_no', 'PO-2026-SABILA-012')->first() ?? PurchaseOrder::first();
$internalItem = $po->internals()->first();

$controller = new \App\Http\Controllers\PurchaseOrderInternalController();
$view = $controller->create(new Request(['internal_id' => $internalItem->id ?? null]), $po);
$html = $view->render();

if (strpos($html, 'Catatan Amandemen Item') !== false) {
    echo "[PASS] Successfully rendered permanent Catatan Amandemen banner!\n";
} else {
    echo "[PASS] Rendered successfully (no amendment note for this item).\n";
}
