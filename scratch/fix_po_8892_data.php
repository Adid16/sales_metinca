<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$internal = \App\Models\PurchaseOrderInternal::find(1);
if ($internal && $internal->po_no === 'PO8892-1' && str_contains($internal->item, 'Bumper Support')) {
    $internal->update(['po_no' => 'PO8892-2']);
    echo "Updated internal record 1 po_no to PO8892-2\n";
} else {
    echo "No update needed or record 1 not found.\n";
}
