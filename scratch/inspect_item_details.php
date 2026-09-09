<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ARTICLES ===\n";
print_r(DB::getSchemaBuilder()->getColumnListing('articles'));

echo "\n=== CONTRACTS ===\n";
print_r(DB::getSchemaBuilder()->getColumnListing('contracts'));

echo "\n=== CONTRACT_REQUIREMENTS ===\n";
print_r(DB::getSchemaBuilder()->getColumnListing('contract_requirements'));

echo "\n=== PURCHASE_ORDER_INTERNALS ===\n";
print_r(DB::getSchemaBuilder()->getColumnListing('purchase_order_internals'));

echo "\n=== SAMPLE CONTRACT WITH REQUIREMENTS ===\n";
$c = App\Models\Contract::with(['requirements', 'article', 'customer', 'internalItem'])->latest()->first();
if ($c) {
    echo "Contract No: {$c->contract_no}, Order: {$c->order_no}, Part Name: {$c->part_name}, Part No: {$c->part_no}, Status: {$c->status}\n";
    if ($c->article) {
        echo "Article: No={$c->article->article_no}, Mat={$c->article->material}, Dwg={$c->article->drawing_no} Rev={$c->article->drawing_rev}, Berat={$c->article->berat}, Lokasi={$c->article->lokasi_pengerjaan}, Die={$c->article->die_no}\n";
    }
    echo "Requirements:\n";
    foreach ($c->requirements as $r) {
        echo "- [{$r->requirement_from}] {$r->requirement} => {$r->requirement_value}\n";
    }
}
