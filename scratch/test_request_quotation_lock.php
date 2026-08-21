<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\RequestProject;
use App\Models\Quotation;

echo "=== TESTING REQUEST PROJECT QUOTATION LOCKING ===\n";

$requests = RequestProject::with(['quotation', 'assignment'])->get();

echo "Total Request Projects: " . $requests->count() . "\n";

foreach ($requests as $req) {
    $hasAssignment = (bool) $req->assignment;
    $hasQuotation = (bool) $req->quotation;
    $quotationNo = $req->quotation ? $req->quotation->quotation_no : 'NONE';

    echo "Request ID #{$req->id} [{$req->subject}]: Assigned=" . ($hasAssignment ? 'YES' : 'NO') . ", Quotation=" . ($hasQuotation ? "YES ({$quotationNo})" : 'NO') . "\n";
}
