<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Quotation;

echo "=== QUOTATIONS COLUMNS ===\n";
$columns = Schema::getColumnListing('quotations');
print_r($columns);

echo "\n=== ALL QUOTATIONS & THEIR SALES PIC ===\n";
$quotes = Quotation::with(['request.assignment.sales', 'customer'])->get();
foreach ($quotes as $q) {
    $salesPic = $q->request->assignment->sales ?? null;
    echo "Quotation #{$q->quotation_no} (ID: {$q->id}): Customer: {$q->customer->name}, Sales PIC: " . ($salesPic ? "{$salesPic->name} (ID: {$salesPic->id})" : "No Sales PIC") . "\n";
}
