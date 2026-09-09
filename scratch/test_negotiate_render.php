<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Quotation;
use App\Models\Negotiate;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

View::share('errors', new \Illuminate\Support\ViewErrorBag());

$customer = User::where('role', 'customer')->first();
Auth::login($customer);

$quotation = Quotation::with(['customer', 'items.article'])->first();
if ($quotation) {
    $negotiations    = Negotiate::where('quotation_id', $quotation->id)->latest()->get();
    $lastNegotiation = $negotiations->first();
    $customerAccount = Account::where('user_id', $quotation->customer_id)->first();
    $floorPrices = [];
    foreach ($quotation->items as $item) {
        $floorPrices[$item->id] = \App\Http\Controllers\NegotiateController::resolveItemFloorPrice($item->item);
    }

    $view = view('quotations.negotiate', compact('quotation', 'customerAccount', 'negotiations', 'lastNegotiation', 'floorPrices'));
    $html = $view->render();

    if (strpos($html, 'alert-permanent') !== false && strpos($html, 'Batas Negosiasi Harga') !== false) {
        echo "[PASS] Successfully rendered permanent Batas Negosiasi Harga banner in negotiate view!\n";
    } else {
        echo "[FAIL] Banner not found.\n";
    }
}
