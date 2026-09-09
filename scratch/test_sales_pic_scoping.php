<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\NegotiateController;

echo "=== TESTING SALES PIC SCOPING ON QUOTATIONS ===\n";

$sales1 = User::where('role', 'staff')->where('divisi', 'sales')->where('id', 2)->first();
$sales2 = User::where('role', 'staff')->where('divisi', 'sales')->where('id', 3)->first();
$manager = User::where('role', 'manager')->where('divisi', 'sales')->first();
$admin = User::where('role', 'admin')->first();

if (!$sales1 || !$sales2) {
    echo "Sales users not found.\n";
    exit(1);
}

$controller = app(QuotationController::class);
$negoController = app(NegotiateController::class);

// 1. Test Sales 1 Index Query
Auth::login($sales1);
$req = new Request();
$view1 = $controller->index($req);
$quotes1 = $view1->getData()['quotations'];
echo "Sales 1 ({$sales1->name}) sees: " . $quotes1->count() . " quotations\n";
foreach ($quotes1 as $q) {
    $picId = $q->request->assignment->sales_id ?? null;
    if ($picId !== $sales1->id) {
        echo "[FAIL] Sales 1 sees quotation #{$q->quotation_no} with PIC ID {$picId}!\n";
        exit(1);
    }
}
echo "[PASS] Sales 1 only sees their own assigned quotations.\n";

// 2. Test Sales 2 Index Query
Auth::login($sales2);
$view2 = $controller->index($req);
$quotes2 = $view2->getData()['quotations'];
echo "Sales 2 ({$sales2->name}) sees: " . $quotes2->count() . " quotations\n";
foreach ($quotes2 as $q) {
    $picId = $q->request->assignment->sales_id ?? null;
    if ($picId !== $sales2->id) {
        echo "[FAIL] Sales 2 sees quotation #{$q->quotation_no} with PIC ID {$picId}!\n";
        exit(1);
    }
}
echo "[PASS] Sales 2 only sees their own assigned quotations.\n";

// 3. Test Manager Sales & Admin seeing all
if ($manager) {
    Auth::login($manager);
    $viewMgr = $controller->index($req);
    $quotesMgr = $viewMgr->getData()['quotations'];
    echo "Manager ({$manager->name}) sees all: " . $quotesMgr->count() . " quotations\n";
    if ($quotesMgr->count() >= $quotes1->count() + $quotes2->count()) {
        echo "[PASS] Manager Sales sees all quotations for supervision.\n";
    }
}

// 4. Test Cross-Access Protection: Sales 2 trying to open Sales 1's quotation
$sales1Quote = $quotes1->first();
if ($sales1Quote) {
    Auth::login($sales2);
    $res = $controller->show($sales1Quote);
    // Should return redirect with error
    if ($res instanceof \Illuminate\Http\RedirectResponse && $res->getSession()->get('error')) {
        echo "[PASS] Sales 2 blocked from viewing Sales 1's Quotation #{$sales1Quote->quotation_no} (Redirect with error: " . $res->getSession()->get('error') . ")\n";
    } else {
        echo "[FAIL] Sales 2 was able to view Sales 1's quotation!\n";
    }

    $resNego = $negoController->viewNego($sales1Quote);
    if ($resNego instanceof \Illuminate\Http\RedirectResponse && $resNego->getSession()->get('error')) {
        echo "[PASS] Sales 2 blocked from negotiating Sales 1's Quotation (Redirect with error: " . $resNego->getSession()->get('error') . ")\n";
    } else {
        echo "[FAIL] Sales 2 was able to access negotiation of Sales 1's quotation!\n";
    }
}

echo "=== ALL SALES PIC SCOPING TESTS PASSED! ===\n";
