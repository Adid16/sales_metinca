# PENGEMBANGAN APLIKASI SISTEM INFORMASI PELACAKAN STATUS PESANAN PELANGGAN PADA DIVISI PURCHASING SALES MARKETING DI PT. METINCA PRIMA INDUSTRIAL WORKS JAKARTA

Aplikasi berbasis web enterprise untuk mendigitalisasi, mengotomatisasi, serta melacak status pesanan pelanggan (*Order Tracking System*) secara *end-to-end* pada divisi **Purchasing Sales Marketing** PT. Metinca Prima Industrial Works Jakarta. 

Sistem ini memfasilitasi integrasi rantai transaksi mulai dari penerimaan permintaan proyek (*Request Project*), pembuatan dan negosiasi penawaran harga (*Quotation & Multi-Round Negotiation*), penerbitan *Purchase Order* (PO Pelanggan & PO Internal), lembar peninjauan kontrak lintas divisi (*Contract Review Sheet* melibatkan 4 Divisi Manajerial), amandemen pesanan dan pelacakan riwayat alasan amandemen, hingga serah terima finalisasi ke lini produksi (*In Production*), dilengkapi antarmuka modern yang responsif dan mendukung fitur *Dark Mode*.

---

## Daftar Isi
1. [Latar Belakang & Tujuan Sistem](#1-latar-belakang--tujuan-sistem)
2. [Teknologi yang Digunakan (Tech Stack)](#2-teknologi-yang-digunakan-tech-stack)
3. [Hak Akses & Struktur Pengguna (User Roles)](#3-hak-akses--struktur-pengguna-user-roles)
4. [Alur Kerja Transaksi Utama (End-to-End Workflow)](#4-alur-kerja-transaksi-utama-end-to-end-workflow)
5. [Fitur Utama & Logika Bisnis (Business Rules)](#5-fitur-utama--logika-bisnis-business-rules)
6. [Tampilan Antarmuka UI/UX (Responsive & Dark Mode)](#6-tampilan-antarmuka-uiux-responsive--dark-mode)
7. [Struktur Database & Relasi Model](#7-struktur-database--relasi-model)
8. [Petunjuk Instalasi & Menjalankan Aplikasi](#8-petunjuk-instalasi--menjalankan-aplikasi)
9. [Akun Demo Pengujian (Demo Accounts)](#9-akun-demo-pengujian-demo-accounts)
10. [Pengujian Otomatis (Automated Testing Suite)](#10-pengujian-otomatis-automated-testing-suite)
11. [Lisensi & Hak Cipta](#11-lisensi--hak-cipta)

---

## 1. Latar Belakang & Tujuan Sistem

PT. Metinca Prima Industrial Works adalah perusahaan manufaktur pengecoran logam (*foundry*) dan permesinan presisi (*machining*). Dalam aktivitas bisnisnya, koordinasi antara pelanggan (*buyer*), tim penjualan (*sales*), dan departemen operasional pabrik memerlukan transparansi status pesanan, akurasi spesifikasi teknis, serta validitas komitmen harga dan jadwal pengiriman.

### Tujuan Utama Pengembangan Aplikasi:
- **Transparansi Status Real-Time**: Memberikan kemampuan pelacakan status pesanan secara mandiri (*self-service tracking*) bagi pelanggan dan internal perusahaan.
- **Akuntabilitas Sales PIC**: Menerapkan mekanisme kepemilikan tiket pesanan (*sales assignment & portfolio scoping*) sehingga setiap pesanan ditangani secara eksklusif oleh Sales PIC penanggung jawab.
- **Proteksi Profitabilitas & Modal**: Mencegah penjualan di bawah harga dasar modal (*floor price*) yang mencakup modal bahan baku dan modal proses kerja permesinan.
- **Kolaborasi Lintas 4 Divisi Manajerial**: Memastikan kelayakan produksi sebelum pesanan diproduksi melalui lembar tinjauan kontrak (*Contract Review Sheet*) dengan persetujuan tanda tangan digital dari Manager Sales, Quality/QC, PPC, dan Design Engineering.
- **Pencatatan Audit Amandemen**: Mendokumentasikan alasan amandemen secara terstruktur ketika terjadi perubahan spesifikasi, kuantiti, atau tanggal kirim setelah PO diterbitkan.
- **Standarisasi Batas SOP**: Menetapkan batasan ruang lingkup kerja Purchasing Sales Marketing yang tuntas pada status **`In Production`** (saat seluruh klausul telah disetujui dan diserahterimakan ke lantai produksi pabrik).

---

## 2. Teknologi yang Digunakan (Tech Stack)

- **Backend Framework**: [Laravel 11.x](https://laravel.com/) (PHP 8.2+ / PHP 8.5)
- **Database Engine**: MySQL 8.0+ / MariaDB (Database: `metinca_db2`)
- **Frontend Architecture**: Blade Templating Engine, Mazer Layout Framework, Bootstrap 5 UI Components, Vanilla CSS & CSS Variables
- **Theme Engine**: Dual Theme System (Light Mode & Dark Mode) dengan persistensi LocalStorage
- **Digital Signature**: HTML5 Canvas / Signature Pad untuk Tanda Tangan Digital Manajerial
- **Document Rendering**: Barryvdh DomPDF (Ekspor PDF Penawaran Harga & Lembar Tinjauan Kontrak)
- **Spreadsheet Engine**: Maatwebsite Laravel Excel (Ekspor Laporan Transaksi ke XLSX)
- **Automated Testing**: PHPUnit Test Suite dengan SQLite In-Memory Database & Laravel Testing Helpers

---

## 3. Hak Akses & Struktur Pengguna (User Roles)

Sistem menerapkan pembagian hak akses (*Role-Based Access Control*) yang ketat berdasarkan peran dan divisi:

```
                                  ┌──────────────────────────┐
                                  │       Super Admin        │
                                  └─────────────┬────────────┘
                                                │
                 ┌──────────────────────────────┼──────────────────────────────┐
                 ▼                              ▼                              ▼
    ┌──────────────────────────┐  ┌──────────────────────────┐  ┌──────────────────────────┐
    │      Manager Sales       │  │    Staff Sales (PIC)     │  │   Customer (Pelanggan)   │
    └────────────┬─────────────┘  └─────────────┬────────────┘  └──────────────┬───────────┘
                 │                              │                              │
                 ▼                              │                              ▼
┌─────────────────────────────────┐             │                ┌───────────────────────────┐
│ Manager Divisi Lain:            │             │                │ Portal Tracking & Nego    │
│ - Manager Quality (QC)          │             │                └───────────────────────────┘
│ - Manager PPC (PPIC)            │             │
│ - Manager Design Eng. (DE)      │             │
└────────────────┬────────────────┘             │
                 │                              │
                 ▼                              ▼
    ┌────────────────────────────────────────────────────────┐
    │    Contract Review Sheet Approval & Finalisasi Produksi │
    └────────────────────────────────────────────────────────┘
```

| Role | Divisi | Tanggung Jawab & Hak Akses |
|---|---|---|
| **Super Admin** | *All* | Akses penuh (*super-privilege*) ke seluruh modul sistem. **Satu-satunya role yang memiliki hak akses ke User Management** (`Data Customer` & `Data Employee`), konfigurasi batas sistem, override negosiasi, instant approval 4 divisi, dan supervisi transaksi. |
| **Manager** | `sales` | Supervisi portofolio seluruh staf sales, approval/penolakan harga khusus negosiasi, override kuota batas tawar, dan tanda tangan digital kontrak divisi sales. *(Tidak memiliki akses ke User Management)*. |
| **Manager** | `quality` | Peninjauan aspek mutu produk, toleransi ukuran, visual check, persyaratan sertifikat uji material (CoA/Mill Sheet), dan approval/penolakan klausul Quality. |
| **Manager** | `ppc` | Peninjauan kapasitas lini produksi, ketersediaan bahan baku (*ingot/scrap*), estimasi lead time pengiriman, dan approval/penolakan klausul PPC. |
| **Manager** | `design engineering` | Peninjauan gambar teknik (*drawing 2D/3D*), toleransi permesinan, komposisi bahan logam (FC/FCD), dan approval/penolakan klausul DE. |
| **Staff** | `sales` | Mengambil tiket request (*claim assignment*), membuat & mengirim *Quotation*, merespons negosiasi harga, menginput PO Internal, mengelola lembar kontrak, hingga finalisasi pesanan ke tahap produksi. |
| **Customer** | *Pelanggan* | Mengajukan *Request Project*, melihat & menawar penawaran harga (*Negotiate*), menerbitkan PO, mengajukan amandemen pesanan beserta alasannya, mengganti password mandiri, serta melacak status pesanan secara publik/internal. |

---

## 4. Alur Kerja Transaksi Utama (End-to-End Workflow)

```mermaid
sequenceDiagram
    autonumber
    actor C as Customer
    actor S as Staff Sales (PIC)
    actor M as Manager (4 Divisi)
    actor P as Lantai Produksi (Pabrik)

    C->>S: 1. Mengirim Request Project (Inquiry Pesanan)
    Note over S: Sales PIC mengklaim tiket pesanan (Assign PIC)
    S->>C: 2. Menerbitkan Penawaran Harga (Quotation)
    opt Negosiasi Harga (Multi-Round)
        C->>S: Mengajukan Penawaran Harga Baru (Bebas / Fleksibel)
        S->>C: Memberikan Counter-Offer (Terkunci Min. Floor Price Modal)
        C->>S: Menyetujui Harga Final (Deal / Accept)
    end
    C->>S: 3. Menerbitkan Purchase Order Resmi (Customer PO & PDF)
    S->>S: 4. Membuat Rincian Purchase Order Internal (PO Internal Per-Item)
    S->>M: 5. Mengajukan Lembar Tinjauan Kontrak (Contract Review Sheet)
    par Review 4 Departemen
        M->>S: Manager Sales Approve Tanda Tangan
        M->>S: Manager Quality Approve Tanda Tangan
        M->>S: Manager PPC Approve Tanda Tangan
        M->>S: Manager Design Engineering Approve Tanda Tangan
    end
    Note over S,M: Status Kontrak menjadi 'Approved'
    S->>P: 6. Sales PIC Melakukan Finalisasi Serah Terima (In Production)
    Note over C,P: Status Kontrak, PO Internal, dan PO Customer serentak 'In Production'
```

---

## 5. Fitur Utama & Logika Bisnis (Business Rules)

### A. Otorisasi Sales PIC & Scoping Portofolio
- **Tiket Mandiri**: Tiket inquiry yang masuk berstatus *unassigned*. Staf sales pertama yang mengambil tiket akan menjadi **Sales PIC**.
- **Perlindungan Tiket**: Staf sales lain dilarang memanipulasi, mengedit, menghapus, atau membuat penawaran dari tiket milik sales lain.
- **Portofolio Terisolasi**: Pada menu *Quotation*, *Purchase Order*, dan *PO Internal*, staf sales hanya disajikan daftar transaksi yang ditugaskan kepada dirinya.

### B. Master Artikel, Price List, & Floor Price
- Setiap produk memiliki master harga resmi (*Price List*) dan **Harga Dasar Modal (*Floor Price / Modal Bahan + Modal Proses*)**.
- **Customer Bebas Menawar**: Pelanggan memiliki fleksibilitas penuh untuk mengajukan harga negosiasi berapa pun tanpa terhalang pesan eror validasi sistem.
- **Proteksi Tim Sales**: Sistem menerapkan proteksi otomatis yang mencegah staf sales mengirimkan harga tawaran balik (*counter-offer*) atau menyetujui harga di bawah harga dasar modal bahan & proses.

### C. Sistem Negosiasi Multi-Round & Batas Kuota
- **Mekanisme Turn-Based**: Negosiasi dilakukan secara bergantian (Pelanggan $\rightarrow$ Sales $\rightarrow$ Pelanggan). Pihak yang sama tidak dapat mengirim pengajuan beruntun sebelum mendapat balasan.
- **Batas Kuota Negosiasi**: Dibatasi maksimal 6 kali pengajuan transaksi (3 kali saling balas).
- **Managerial Override**: Jika kuota habis namun negosiasi masih dibutuhkan, Manager Sales memiliki wewenang memberikan kuota tambahan (*override limit*).

### D. Lembar Tinjauan Kontrak (Contract Review Sheet) & Persetujuan 4 Divisi
- Mengakomodasi peninjauan teknis per-item produk sebelum proses pengecoran dan permesinan dilakukan.
- Memerlukan tanda tangan digital (*signature canvas*) dari 4 divisi:
  1. **Sales**: Kesepakatan harga, syarat pembayaran (*payment terms*), spesifikasi khusus pelanggan, dan tanggal pengiriman.
  2. **Quality (QC)**: Toleransi ukuran, standar uji kekerasan material, inspeksi visual, sertifikat CoA & Mill Sheet.
  3. **PPC**: Ketersediaan *scrap/ingot*, kapasitas cetak pasir/furnace, dan jadwal permesinan.
  4. **Design Engineering**: Kesesuaian gambar teknik (*2D/3D CAD drawing*), shrinkage allowance, dan pola cetakan.
- **Revisi Bertarget (*Targeted Revision*)**: Apabila salah satu manajer menolak (*reject*), status berubah ke `revision` disertai alasan penolakan wajib. Hanya divisi yang menolak yang perlu meninjau ulang setelah Sales merevisi data tanpa membatalkan *approval* divisi lain yang telah sah.

### E. Amandemen Pesanan Pelanggan (*PO Amendment*) & Integrasi Alasan
- Pelanggan dapat mengajukan amandemen data pesanan (spesifikasi, kuantiti, tanggal kirim) dengan mencantumkan alasan amandemen.
- **Riwayat Alasan Amandemen**: Alasan amandemen otomatis ditampilkan saat pembuatan dan peninjauan kembali Contract Review Sheet pasca-amandemen.
- **Batas Amandemen**: Maksimal 2 kali amandemen per item pesanan.
- **Kunci Produksi (*Production Lock*)**: Amandemen otomatis dikunci jika pesanan sudah masuk tahap produksi (*In Production*).

### F. Isolasi Sub-PO & Finalisasi Parsial ke Produksi (*Multi-Item Sub-PO Partial Finalization*)
- **Siklus Mandiri Per Sub-PO**: Setiap item pesanan (*Sub-PO Internal*) memiliki lembar kontrak dan matriks persetujuan 4 divisi secara independen.
- **Pencegahan Finalisasi Prematur**: Finalisasi satu sub-PO ke lini produksi oleh Sales PIC tidak akan menyeret item lain yang masih dalam proses verifikasi/revisi.
- **Agregasi Status Master PO (*Earliest Progress Stage*)**: Status PO Utama dihitung secara dinamis mengambil tahapan paling awal/minimum dari seluruh sub-itemnya:
  - Jika ada item yang belum diproses $\rightarrow$ Status PO Utama: `Sent`.
  - Jika ada item yang masih dalam tahap persetujuan (misal: 2 item `In Production`, 2 item `Review`) $\rightarrow$ Status PO Utama: `Review` (badge kuning).
  - Status PO Utama **HANYA** berubah menjadi **`In Production`** apabila **seluruh item (100%)** telah selesai difinalisasi ke tahap produksi (`production` / `done`).
- **Kunci Produksi Per-Item (*Per-Item Production Lock*)**: Sub-PO yang masih berstatus `Review` tetap dapat diajukan amandemen oleh pelanggan, sedangkan sub-PO yang sudah `In Production` terkunci otomatis.

### G. Hak Eksklusif Super Admin & Pembatasan User Management
- **User Management Terisolasi**: Menu dan rute `User Management` (`Data Customer` dan `Data Employee`) dibatasi secara ketat **hanya dapat diakses oleh role Super Admin**.
- **Perlindungan Non-Admin**: Role Manager Sales, Manager Divisi Teknis, Staff Sales, maupun Customer tidak dapat melihat menu User Management di sidebar dan otomatis diblokir (HTTP `403 Forbidden`) jika mencoba mengakses URL secara langsung.
- **Hak Override Master**: Super Admin memiliki wewenang *instant approve* 4 divisi, penugasan ulang (*re-assign*) tiket Sales PIC, penambahan kuota negosiasi (*override quota*), dan audit log seluruh transaksi.

### H. Sistem Notifikasi Transaksi Terpadu (*Real-Time Notification System*)
- **Notifikasi Bel Navbar**: Dilengkapi lonceng notifikasi interaktif di top navbar dengan penanda badge jumlah notifikasi baru (*unread counter*) dan tombol *Mark all as read*.
- **Lifecycle Otomatis**: Notifikasi terkirim otomatis pada setiap tahapan penting: penerimaan Request Project, penerbitan & negosiasi Quotation, penerbitan PO & PO Internal, pengajuan & persetujuan Amandemen, penolakan kontrak manajerial, approval 4 divisi, hingga serah terima produksi.

### I. Konfigurasi Batas Sistem Dinamis (*Dynamic System Settings Service*)
- **Sentralisasi Parameter Bisnis**: Batas aturan operasional dikelola secara terpusat melalui [`SystemSettingService.php`](file:///c:/laragon/www/sales_metinca/app/Services/SystemSettingService.php) dan tabel `system_settings`:
  - `max_negotiation_limit`: Batas maksimal putaran penawaran negosiasi harga (Default: 6 kali / 3 putaran saling balas).
  - `max_amandement_limit`: Batas maksimal pengajuan amandemen pesanan per item (Default: 2 kali per item PO).
  - `min_price_margin_percentage`: Persentase toleransi margin harga dasar modal / floor price (Default: 10%).
- **Managerial Override Kuota**: Manager Sales dapat memberikan kuota tambahan (`overrideNegotiationLimit`) per transaksi Quotation jika negosiasi memerlukan putaran tawar-menawar lebih lanjut.

### J. Pelacakan Status Pesanan Terpadu & Keamanan Akun Pelanggan (Customer Portal)
- Pelanggan yang telah login dapat melacak progres dan riwayat seluruh pesanan secara real-time melalui lencana status, linimasa riwayat aktivitas (*activity history*), serta status verifikasi per-item pada menu *Purchase Orders* dan *Dashboard*.
- **Ganti Password Mandiri**: Pelanggan dapat memperbarui profil dan mengubah password akun secara mandiri melalui menu profil dengan verifikasi kata sandi lama dan enkripsi Bcrypt.

---

## 6. Tampilan Antarmuka UI/UX (Responsive & Dark Mode)

Sistem mengadopsi standar antarmuka modern yang nyaman digunakan di berbagai perangkat dan kondisi pencahayaan:

1. **Tata Letak Baku Mazer Vertical Navbar (`layout-navbar navbar-fixed`)**:
   - Struktur tata letak memisahkan area navigasi sidebar dan area kerja konten utama secara presisi.
   - Top navbar berada rapi di samping sidebar pada layar desktop (lebar $\ge 1200\text{px}$) sehingga tidak menutupi logo maupun switcher tema.
   - Pada layar mobile/tablet, top navbar menyediakan tombol burger menu untuk membuka dan menutup laci navigasi off-canvas secara intuitif.

2. **Dukungan Penuh Dual-Theme (Dark Mode & Light Mode)**:
   - Dilengkapi *Dark Theme Switcher* interaktif di header sidebar.
   - Menggunakan token CSS variabel berbasis palet kontras tinggi yang teruji ramah mata pada malam hari.
   - Mendukung penyesuaian otomatis untuk seluruh komponen: tabel data, modal konfirmasi, sweetalert, form input, badge status, hingga dropdown menu.
   - Pilihan tema tersimpan otomatis di *LocalStorage* browser pengguna (`initTheme.js`).

3. **Optimasi Responsif Multi-Device**:
   - **Desktop / Laptop ($\ge 1200\text{px}$)**: Layout multi-kolom penuh, sidebar statis, tabel data lengkap.
   - **Tablet ($768\text{px} - 1199\text{px}$)**: Padding konten proporsional, filter bar fleksibel, burger navigation.
   - **Smartphone ($< 768\text{px}$)**: Laci sidebar off-canvas, tabel berkemampuan *touch horizontal scroll*, kartu dan tombol aksi yang nyaman disentuh (*touch-friendly*).

---

## 7. Struktur Database & Relasi Model

```
                    ┌─────────────────────────┐
                    │          users          │
                    └────────────┬────────────┘
                                 │
           ┌─────────────────────┼─────────────────────┐
           │ 1:N                 │ 1:N                 │ 1:N
           ▼                     ▼                     ▼
┌─────────────────────┐┌───────────────────┐┌──────────────────────┐
│  request_projects   ││    quotations     ││   purchase_orders    │
└──────────┬──────────┘└─────────┬─────────┘└──────────┬───────────┘
           │ 1:1                 │ 1:N                 │ 1:N
           ▼                     ▼                     ▼
┌─────────────────────┐┌───────────────────┐┌──────────────────────┐
│ request_project_    ││ quotation_items   ││ purchase_order_      │
│ assignments         │└─────────┬─────────┘│ internals (per-item) │
└─────────────────────┘          │          └──────────┬───────────┘
                                 │ 1:N                 │ 1:1
                                 ▼                     ▼
                       ┌───────────────────┐┌──────────────────────┐
                       │    negotiates     ││      contracts       │
                       └───────────────────┘└──────────┬───────────┘
                                                       │ 1:N
                                                       ▼
                                            ┌──────────────────────┐
                                            │ contract_requirements│
                                            └──────────────────────┘
```

---

## 8. Petunjuk Instalasi & Menjalankan Aplikasi

### Prasyarat Sistem
- **PHP**: Versi 8.2 atau lebih tinggi (Direkomendasikan PHP 8.5)
- **Composer**: Dependency Manager for PHP
- **Database**: MySQL Server 8.0+ atau MariaDB
- **Web Server**: Apache / Nginx / Laragon / PHP Built-in Server

### Langkah-Langkah Instalasi:

1. **Clone Repository & Buka Direktori Proyek**:
   ```bash
   cd c:\laragon\www\sales_metinca
   ```

2. **Install Dependensi Composer**:
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`)**:
   Salin berkas `.env.example` menjadi `.env` dan sesuaikan koneksi database:
   ```env
   APP_NAME="PT. Metinca Prima Industrial Works"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=metinca_db2
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi Database & Seeding Data Lengkap**:
   ```bash
   php artisan migrate:fresh --seed
   php artisan db:seed --class=DummyTenTransactionsSeeder
   ```

6. **Buat Symlink Storage Publik**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser pada URL: `http://localhost:8000` atau `http://sales_metinca.test` (jika menggunakan Laragon).

---

## 9. Akun Demo Pengujian (Demo Accounts)

Berikut adalah daftar akun siap pakai yang telah disediakan melalui database seeder untuk keperluan pengujian alur bisnis:

| Role / Peran | Divisi | Alamat Email | Password | Kegunaan Pengujian |
|---|---|---|---|---|
| **Super Admin** | *Management* | `admin@example.com` | `admin123` | Konfigurasi sistem, manajemen master data, supervisi transaksi. |
| **Manager Sales** | `sales` | `msales@example.com` | `msales123` | Approval negosiasi harga, override batas tawar, approval kontrak divisi sales. |
| **Manager Quality** | `quality` | `mquality@example.com` | `mquality123` | Tanda tangan digital persetujuan mutu & penolakan klausul QC. |
| **Manager PPC** | `ppc` | `mppc@example.com` | `mppc123` | Tanda tangan digital persetujuan jadwal & kapasitas lini pabrik. |
| **Manager Design Eng.** | `design engineering` | `mde@example.com` | `mde123` | Tanda tangan digital persetujuan gambar teknik (drawing CAD). |
| **Staff Sales 1 (PIC A)** | `sales` | `ssales1@example.com` | `ssales123` | Klaim tiket, buat penawaran, proses PO Internal, finalisasi pesanan PIC A. |
| **Staff Sales 2 (PIC B)** | `sales` | `ssales2@example.com` | `ssales2abc` | Pengujian isolasi portofolio & pembatasan akses non-PIC. |
| **Customer 1 (Sabila)** | *Buyer* | `sabila@example.com` | `password` | Kirim request, negosiasi harga, kirim PO, ajukan amandemen. |
| **Customer 2 (Adi Dwi)** | *Buyer* | `adidwinugroho@gmail.com` | `password` | Akun pelanggan kedua untuk pengujian multi-perusahaan. |

---

## 10. Pengujian Otomatis (Automated Testing Suite)

Aplikasi dilengkapi dengan rangkaian unit & feature test komprehensif menggunakan PHPUnit untuk memvalidasi integritas logika bisnis secara otomatis.

### Menjalankan Pengujian:
```powershell
php artisan test
```
*atau menggunakan PHPUnit binary langsung:*
```powershell
& "C:\laragon\bin\php\php-8.5.5\php.exe" vendor/bin/phpunit
```

### Rangkuman Test Suite:
1. **`MultiItemPoPartialFinalizationTest`**:
   - Memvalidasi isolasi finalisasi per item Sub-PO: finalisasi salah satu Sub-PO tidak memaksa item lain ke tahap produksi.
   - Memvalidasi status agregat Master PO tetap `Review` dan hanya menjadi `In Production` jika seluruh sub-item (100%) telah berstatus produksi.
   - Memvalidasi amandemen pelanggan tetap dapat diajukan pada item yang belum berstatus produksi.
2. **`SuperAdminFullAccessTest`**:
   - Memvalidasi hak eksklusif Super Admin terhadap User Management (Employee & Customer per plant) dan pemblokiran total (403) bagi Manager/Staff/Customer.
   - Memvalidasi hak master Super Admin untuk instant approve 4 divisi, bypass penugasan, override kuota negosiasi, dan finalisasi kontrak.
3. **`NotificationFeatureTest`**:
   - Memvalidasi pengiriman notifikasi otomatis pada seluruh siklus transaksi (Request, Quotation, Nego, PO, Amandemen, Kontrak).
   - Memvalidasi fitur baca notifikasi (*mark all as read*) dan perolehan badge counter unread.
4. **`CustomerChangePasswordTest`**:
   - Memvalidasi pembaharuan profil pelanggan dan penggantian kata sandi mandiri dengan validasi password lama & Bcrypt hash.
5. **`EndToEndSalesPicLifecycleScopingTest`**:
   - Memvalidasi pembatasan akses staf sales non-PIC dari tahap Request Project, Quotation, Negosiasi, Purchase Order, PO Internal, hingga Contract Finalization.
   - Memvalidasi keberhasilan Sales PIC penanggung jawab dan manajerial dalam mengeksekusi pesanan.
6. **`AmendmentLimitAndProductionLockTest`**:
   - Memvalidasi kuota pengajuan amandemen maksimal 2 kali per item.
   - Memvalidasi penguncian amandemen saat pesanan sudah masuk tahap `production`.
7. **`ContractRejectionAndRevisionWorkflowTest`**:
   - Memvalidasi alur penolakan manajer dengan alasan wajib dan mekanisme revisi bertarget (*targeted revision*).
   - Memvalidasi keharusan finalisasi oleh Sales PIC setelah persetujuan lengkap 4 divisi.
8. **`PriceListAndApprovalWorkflowTest`**:
   - Memvalidasi kebebasan pelanggan menawar harga dan proteksi keras bagi tim sales dari penjualan di bawah harga dasar modal bahan & proses (*floor price*).

**Status Pengujian Terbaru**:
```
PASS Tests\Unit\ExampleTest
PASS Tests\Feature\AmendmentLimitAndProductionLockTest
PASS Tests\Feature\ContractRejectionAndRevisionWorkflowTest
PASS Tests\Feature\CustomerChangePasswordTest
PASS Tests\Feature\EndToEndSalesPicLifecycleScopingTest
PASS Tests\Feature\ExampleTest
PASS Tests\Feature\MultiItemPoPartialFinalizationTest
PASS Tests\Feature\NotificationFeatureTest
PASS Tests\Feature\PriceListAndApprovalWorkflowTest
PASS Tests\Feature\SuperAdminFullAccessTest

Tests:    15 passed (282 assertions)
Status:   100% Passed
```

---

## 11. Lisensi & Hak Cipta
Aplikasi ini dikembangkan khusus untuk **PT. Metinca Prima Industrial Works Jakarta** sebagai bagian dari sistem informasi manajemen rantai pesanan divisi Purchasing Sales Marketing. Seluruh hak cipta dilindungi undang-undang.
