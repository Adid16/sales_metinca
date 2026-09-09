<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USERS ===\n";
foreach (App\Models\User::all() as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role} | Divisi: {$u->divisi}\n";
}

echo "\n=== ARTICLES ===\n";
foreach (App\Models\Article::all() as $a) {
    echo "ID: {$a->id} | Article: {$a->article_no} | Part No: {$a->internal_part_no} | Part Name: {$a->part_name} | Price: {$a->price}\n";
}

echo "\n=== POs ===\n";
foreach (App\Models\PurchaseOrder::with(['customer', 'quotation', 'internals'])->get() as $po) {
    echo "ID: {$po->id} | PO: {$po->po_no} | Status: {$po->status} | Customer: " . ($po->customer->name ?? '-') . " | Items: " . $po->internals->count() . "\n";
}
