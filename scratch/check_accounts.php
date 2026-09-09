<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Account;

echo "=== USERS & ACCOUNTS ===\n";
$users = User::with('account')->where('role', 'customer')->get();
foreach ($users as $u) {
    echo "User #{$u->id} ({$u->name}): user.company='{$u->company}'";
    if ($u->account) {
        echo ", account.company='{$u->account->company}', account.phone='{$u->account->phone}'\n";
    } else {
        echo ", (NO ACCOUNT PROFILE)\n";
    }
}
