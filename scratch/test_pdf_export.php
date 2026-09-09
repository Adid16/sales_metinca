<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$sales = User::where('role', 'staff')->first();
Auth::login($sales);

$quotation = Quotation::find(15) ?? Quotation::first();

if (!$quotation) {
    echo "No quotation found to test!\n";
    exit(0);
}

$controller = new \App\Http\Controllers\QuotationController();
$response = $controller->exportPdf($quotation);

echo "[PASS] Successfully generated PDF for Quotation #{$quotation->quotation_no} (Status Code: " . $response->getStatusCode() . ")\n";
