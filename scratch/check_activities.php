<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$activities = App\Models\HistoryActivity::with('user')->orderBy('id', 'desc')->take(20)->get();
foreach ($activities as $a) {
    $uName = $a->user ? $a->user->name . " (" . ($a->user->role ?? '') . " " . ($a->user->divisi ?? '') . ")" : "System";
    echo "[#{$a->id}] [{$a->activity_time}] [{$uName}]: {$a->activity}\n";
}
