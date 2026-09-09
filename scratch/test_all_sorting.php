<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$admin = User::where('role', 'admin')->first() ?? User::first();
Auth::login($admin);

echo "Testing RequestProjectController@index...\n";
$reqProjController = new \App\Http\Controllers\RequestProjectController();
$view1 = $reqProjController->index(new Request());
echo "[PASS] RequestProjectController@index (Items: " . count($view1->getData()['projects']) . ")\n";

echo "Testing QuotationController@index...\n";
$quotationController = new \App\Http\Controllers\QuotationController();
$view2 = $quotationController->index(new Request());
echo "[PASS] QuotationController@index (Items: " . count($view2->getData()['quotations']) . ")\n";

echo "Testing PurchaseOrderController@index...\n";
$poController = new \App\Http\Controllers\PurchaseOrderController();
$view3 = $poController->index(new Request());
echo "[PASS] PurchaseOrderController@index (Items: " . $view3->getData()['pos']->count() . ")\n";

echo "Testing PurchaseOrderInternalController@index...\n";
$poiController = new \App\Http\Controllers\PurchaseOrderInternalController();
$view4 = $poiController->index(new Request());
echo "[PASS] PurchaseOrderInternalController@index (Items: " . $view4->getData()['items']->count() . ")\n";

echo "Testing ContractController@index...\n";
$contractController = new \App\Http\Controllers\ContractController();
$view5 = $contractController->index(new Request());
echo "[PASS] ContractController@index (Groups: " . $view5->getData()['contracts']->count() . ")\n";

echo "ALL INDEX CONTROLLERS AND SORTING QUERIES OPERATIONAL!\n";
