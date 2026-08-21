<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use App\Models\ContractRequirement;
use App\Models\HistoryActivity;
use Illuminate\Support\Facades\DB;

class TestSetupSeeder extends Seeder
{
    /**
     * Run the database seeds for testing fresh PO flow.
     */
    public function run(): void
    {
        // 1. Bersihkan Data PO, PO Internal, Kontrak, dan History terdahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ContractRequirement::truncate();
        Contract::truncate();
        PurchaseOrderInternal::truncate();
        PurchaseOrder::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ambil atau buat User Customer
        $customer = User::where('role', 'customer')->first();
        if (!$customer) {
            $customer = User::create([
                'name'     => 'Adi Dwi Nugroho',
                'email'    => 'adidwinugroho168@gmail.com',
                'password' => bcrypt('password'),
                'role'     => 'customer',
            ]);
        }

        // 3. Buat Data Quotation #1 (2 Items)
        $q1 = Quotation::create([
            'customer_id'  => $customer->id,
            'quotation_no' => 'QT-2026-07-0001',
            'company_name' => 'PT Metinca Industri Utama',
            'date_expired' => now()->addDays(30),
            'material'     => 'Cast Iron & Steel S45C',
        ]);

        QuotationItem::create([
            'quotation_id' => $q1->id,
            'item'         => 'Impeller Pump Cast Iron D200',
            'qty'          => 50,
            'price'        => 350000,
        ]);

        QuotationItem::create([
            'quotation_id' => $q1->id,
            'item'         => 'Shaft Coupling Steel S45C',
            'qty'          => 100,
            'price'        => 175000,
        ]);

        // 4. Buat PO External #1 (Status 'sent' / Menunggu Proses Internal oleh Sales)
        PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $q1->id,
            'po_no'            => 'PO-2026-07-001',
            'delivery_request' => now()->addDays(14)->format('Y-m-d'),
            'attachment'       => '1784693775_quotation-QT-2026-07-0002.pdf',
            'status'           => 'sent',
            'status_order'     => 'open',
        ]);

        // 5. Buat Data Quotation #2 (3 Items)
        $q2 = Quotation::create([
            'customer_id'  => $customer->id,
            'quotation_no' => 'QT-2026-07-0002',
            'company_name' => 'PT Surabaya Precision Engineering',
            'date_expired' => now()->addDays(30),
            'material'     => 'Stainless Steel SS316',
        ]);

        QuotationItem::create([
            'quotation_id' => $q2->id,
            'item'         => 'Housing Valve SS316',
            'qty'          => 20,
            'price'        => 850000,
        ]);

        QuotationItem::create([
            'quotation_id' => $q2->id,
            'item'         => 'Flange Disc 4 Inch SS316',
            'qty'          => 40,
            'price'        => 420000,
        ]);

        QuotationItem::create([
            'quotation_id' => $q2->id,
            'item'         => 'Bracket Mounting Heavy Duty',
            'qty'          => 80,
            'price'        => 210000,
        ]);

        // 6. Buat PO External #2 (Status 'sent' / Menunggu Proses Internal)
        PurchaseOrder::create([
            'customer_id'      => $customer->id,
            'quotation_id'     => $q2->id,
            'po_no'            => 'PO-2026-07-002',
            'delivery_request' => now()->addDays(20)->format('Y-m-d'),
            'attachment'       => '1784694257_quotation-QT-2026-07-0008.pdf',
            'status'           => 'sent',
            'status_order'     => 'open',
        ]);

        $this->command->info('Setup Data PO External Baru Berhasil Dibuat!');
    }
}
