<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Account;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

echo "=== SEEDING COMPREHENSIVE TEST DATA ===\n";

// 1. SEED CUSTOMERS FIRST
// Customer AHM (PT. Astra Honda Motor)
$customerAHM = User::updateOrCreate(
    ['email' => 'ahm@customer.com'],
    [
        'name'     => 'Bambang Susanto (AHM)',
        'role'     => 'customer',
        'password' => Hash::make('password123')
    ]
);
Account::updateOrCreate(
    ['user_id' => $customerAHM->id],
    [
        'company' => 'PT. Astra Honda Motor (AHM)',
        'phone'   => '021-6518080',
        'address' => 'Jl. Laksda Yos Sudarso, Sunter 1, Jakarta Utara'
    ]
);

// Customer ADI (PT. Adi Jaya Tehnik)
$customerADI = User::updateOrCreate(
    ['email' => 'adi@customer.com'],
    [
        'name'     => 'Adi Wijaya',
        'role'     => 'customer',
        'password' => Hash::make('password123')
    ]
);
Account::updateOrCreate(
    ['user_id' => $customerADI->id],
    [
        'company' => 'PT. Adi Jaya Tehnik (ADI)',
        'phone'   => '021-8972100',
        'address' => 'Kawasan Industri Jababeka Phase III, Cikarang'
    ]
);

echo "✓ Customers PT. Astra Honda Motor (ahm@customer.com) & PT. Adi Jaya Tehnik (adi@customer.com) created/updated.\n";

// 2. SEED PRICELIST / ARTICLES (ART-001 s/d ART-010)
$articles = [
    ['internal_part_no' => 'IPN-001', 'article_no' => 'ART-001', 'part_name' => 'Bracket Front Bumper Support', 'price' => 150000, 'material' => 'SPCC Steel 1.2mm'],
    ['internal_part_no' => 'IPN-002', 'article_no' => 'ART-002', 'part_name' => 'Cover Engine Side LH', 'price' => 275000, 'material' => 'Aluminum Die Cast A380'],
    ['internal_part_no' => 'IPN-003', 'article_no' => 'ART-003', 'part_name' => 'Reinforce Center Pillar RH', 'price' => 420000, 'material' => 'High Tensile Steel 1.6mm'],
    ['internal_part_no' => 'IPN-004', 'article_no' => 'ART-004', 'part_name' => 'Flange Exhaust Manifold', 'price' => 95000, 'material' => 'SUS304 Stainless Steel'],
    ['internal_part_no' => 'IPN-005', 'article_no' => 'ART-005', 'part_name' => 'Housing Oil Pump Inner', 'price' => 310000, 'material' => 'FC250 Cast Iron'],
    ['internal_part_no' => 'IPN-006', 'article_no' => 'ART-006', 'part_name' => 'Plate Clutch Driven', 'price' => 185000, 'material' => 'SK5 Carbon Tool Steel'],
    ['internal_part_no' => 'IPN-007', 'article_no' => 'ART-007', 'part_name' => 'Bracket Radiator Upper', 'price' => 125000, 'material' => 'SPHC Steel 2.0mm'],
    ['internal_part_no' => 'IPN-008', 'article_no' => 'ART-008', 'part_name' => 'Arm Rear Suspension Upper', 'price' => 580000, 'material' => 'Forged Steel S45C'],
    ['internal_part_no' => 'IPN-009', 'article_no' => 'ART-009', 'part_name' => 'Spacer Front Wheel Hub', 'price' => 85000, 'material' => 'Aluminum 6061-T6'],
    ['internal_part_no' => 'IPN-010', 'article_no' => 'ART-010', 'part_name' => 'Guide Valve Intake & Exhaust', 'price' => 65000, 'material' => 'Bronze Alloy CuSn8'],
];

foreach ($articles as $art) {
    Article::updateOrCreate(
        ['article_no' => $art['article_no']],
        [
            'internal_part_no' => $art['internal_part_no'],
            'part_name'        => $art['part_name'],
            'price'            => $art['price'],
            'material'         => $art['material'] ?? 'Steel',
            'effective_date'   => now()->toDateString(),
            'customer_id'      => $customerAHM->id,
            'index_no'         => '00',
            'berat'            => '1.50',
            'die_no'           => 'DIE-01',
            'drawing_no'       => 'DWG-001',
            'drawing_rev'      => 'A',
            'lokasi_pengerjaan'=> 'Plant 1 Sunter'
        ]
    );
}
echo "✓ 10 Pricelist Articles (ART-001 s/d ART-010) created/updated in DB.\n";
echo "=== SEEDING COMPLETED SUCCESSFULLY ===\n";
