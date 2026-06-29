<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil user pertama yang ada di database
        $customer = User::first();

        // 2. JIKA TABEL USERS KOSONG: Buat satu user dummy untuk mengamankan relasi Foreign Key
        if (!$customer) {
            $customer = User::create([
                'name'     => 'PT. Astra Honda Motor',
                'email'    => 'ahm@customer.com',
                'password' => Hash::make('password'),
                'role'     => 'customer', // Sesuaikan nama kolom role jika ada di proyek Anda
            ]);
        }

        $customerId = $customer->id;

        $articles = [
            [
                'internal_part_no'  => 'IPN-MIPW-001',
                'article_no'        => 'ART-BRK-01',
                'part_name'         => 'Bracket Front Bumper Support',
                'index_no'          => 'Rev 0',
                'berat'             => 1.45,
                'die_no'            => 'DIE-ST-101',
                'material'          => 'SPCC-SD t=2.0mm',
                'drawing_no'        => 'DWG-2026-0001',
                'drawing_rev'       => '3',
                'effective_date'    => '2026-06-01',
                'customer_id'       => $customerId,
                'lokasi_pengerjaan' => 1,
                'remark'            => 'Data uji coba struktur baru tanpa harga.',
                'casting_price'     => 0,
                'machining_price'   => 0,
                'price'             => 0,
                'pdf_attachment'    => null,
            ],
            [
                'internal_part_no'  => 'IPN-MIPW-002',
                'article_no'        => 'ART-HNG-02',
                'part_name'         => 'Hinge Rear Door Lower LH',
                'index_no'          => 'Rev 1',
                'berat'             => 0.98,
                'die_no'            => 'DIE-ST-204',
                'material'          => 'S45C Spheroidized',
                'drawing_no'        => 'DWG-2026-0052',
                'drawing_rev'       => 'A',
                'effective_date'    => '2026-06-10',
                'customer_id'       => $customerId,
                'lokasi_pengerjaan' => 2,
                'remark'            => 'Data uji coba struktur baru tanpa harga.',
                'casting_price'     => 0,
                'machining_price'   => 0,
                'price'             => 0,
                'pdf_attachment'    => null,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}