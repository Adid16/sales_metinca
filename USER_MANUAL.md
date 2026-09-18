# BUKU PANDUAN PENGGUNA (USER MANUAL & SOP SISTEM)
## SISTEM INFORMASI PELACAKAN STATUS PESANAN PELANGGAN
### PT. METINCA PRIMA INDUSTRIAL WORKS JAKARTA

---

## DAFTAR ISI
1. [PENDAHULUAN & PETUNJUK UMUM](#1-pendahuluan--petunjuk-umum)
2. [PANDUAN PENGGUNA: CUSTOMER (PELANGGAN)](#2-panduan-pengguna-customer-pelanggan)
   - 2.1 [Registrasi & Login Akun](#21-registrasi--login-akun)
   - 2.2 [Mengajukan Permintaan Proyek (Request Project)](#22-mengajukan-permintaan-proyek-request-project)
   - 2.3 [Menerima & Menegosiasikan Penawaran Harga (Quotation & Negotiation)](#23-menerima--menegosiasikan-penawaran-harga-quotation--negotiation)
   - 2.4 [Menerbitkan Purchase Order (PO Pelanggan)](#24-menerbitkan-purchase-order-po-pelanggan)
   - 2.5 [Mengajukan Amandemen Pesanan (PO Amendment)](#25-mengajukan-amandemen-pesanan-po-amendment)
   - 2.6 [Pelacakan Status Pesanan pada Portal Pelanggan (Customer Order Tracking)](#26-pelacakan-status-pesanan-pada-portal-pelanggan-customer-order-tracking)
3. [PANDUAN PENGGUNA: STAFF SALES (SALES PIC)](#3-panduan-pengguna-staff-sales-sales-pic)
   - 3.1 [Klaim Penugasan Tiket (Claim / Assign Sales PIC)](#31-klaim-penugasan-tiket-claim--assign-sales-pic)
   - 3.2 [Pembuatan Penawaran Harga Resmi (Create Quotation)](#32-pembuatan-penawaran-harga-resmi-create-quotation)
   - 3.3 [Merespons Negosiasi Harga Pelanggan (Counter-Offer)](#33-merespons-negosiasi-harga-pelanggan-counter-offer)
   - 3.4 [Penerbitan PO Internal Per-Item (Create PO Internal)](#34-penerbitan-po-internal-per-item-create-po-internal)
   - 3.5 [Penyusunan Lembar Tinjauan Kontrak (Contract Review Sheet)](#35-penyusunan-lembar-tinjauan-kontrak-contract-review-sheet)
   - 3.6 [Penanganan Revisi Bertarget (Targeted Revision)](#36-penanganan-revisi-bertarget-targeted-revision)
   - 3.7 [Finalisasi Serah Terima ke Lini Produksi (In Production)](#37-finalisasi-serah-terima-ke-lini-produksi-in-production)
   - 3.8 [Persetujuan Amandemen Pesanan (Approval Amandemen)](#38-persetujuan-amandemen-pesanan-approval-amandemen)
4. [PANDUAN PENGGUNA: 4 DIVISI MANAJERIAL (MANAGER SALES, QUALITY, PPC, DESIGN ENG.)](#4-panduan-pengguna-4-divisi-manajerial)
   - 4.1 [Review Aspek Divisi Sales (Manager Sales)](#41-review-aspek-divisi-sales-manager-sales)
   - 4.2 [Review Aspek Mutu & Standar Spesifikasi (Manager Quality / QC)](#42-review-aspek-mutu--standar-spesifikasi-manager-quality--qc)
   - 4.3 [Review Aspek Kapasitas & Jadwal (Manager PPC)](#43-review-aspek-kapasitas--jadwal-manager-ppc)
   - 4.4 [Review Aspek Gambar Teknik & Cetakan (Manager Design Engineering)](#44-review-aspek-gambar-teknik--cetakan-manager-design-engineering)
   - 4.5 [Persetujuan Tanda Tangan Digital & Penolakan Berita Acara](#45-persetujuan-tanda-tangan-digital--penolakan-berita-acara)
5. [PANDUAN PENGGUNA: SUPER ADMIN](#5-panduan-pengguna-super-admin)
   - 5.1 [Manajemen Pengguna (User Management: Customer & Employee)](#51-manajemen-pengguna-user-management)
   - 5.2 [Manajemen Master Data Produk & Floor Price](#52-manajemen-master-data-produk--floor-price)
   - 5.3 [Supervisi & Audit Log Transaksi](#53-supervisi--audit-log-transaksi)
6. [FITUR TAMBAHAN: DUAL THEME (DARK MODE) & RESPONSIVE MOBILE](#6-fitur-tambahan-dual-theme-dark-mode--responsive-mobile)
7. [RANGKUMAN MATRIKS STATUS PESANAN (STATUS REFERENCE)](#7-rangkuman-matriks-status-pesanan-status-reference)

---

## 1. PENDAHULUAN & PETUNJUK UMUM

Buku panduan ini disusun sebagai standar operasional prosedur (SOP) penggunaan **Sistem Informasi Pelacakan Status Pesanan Pelanggan** pada divisi *Purchasing Sales Marketing* di PT. Metinca Prima Industrial Works. 

### Alamat URL Sistem:
- **Aplikasi Web**: `http://localhost:8000` atau `http://sales_metinca.test`

---

## 2. PANDUAN PENGGUNA: CUSTOMER (PELANGGAN)

### 2.1 Registrasi & Login Akun
1. Buka halaman utama aplikasi, klik tombol **Register** jika belum memiliki akun, atau masukkan **Email** dan **Password** pada form login.
2. Pastikan data profil perusahaan (Nama PT/CV, Alamat, Nomor Telepon, Nama PIC) telah terisi dengan benar.

---

### 2.2 Mengajukan Permintaan Proyek (Request Project)
1. Pada menu sebelah kiri (sidebar), pilih menu **Request Project**.
2. Klik tombol hijau **+ Add Request Project**.
3. Isi formulir pengajuan proyek:
   - **Nama Proyek / Part Name**: Nama komponen/produk cor atau permesinan.
   - **Kuantiti**: Jumlah unit yang diminta (pcs/unit).
   - **Target Tanggal Kirim**: Estimasi tanggal kebutuhan barang di pabrik pelanggan.
   - **Spesifikasi Material**: Jenis logam cor (contoh: *FC 250, FCD 450, Cast Iron, Bronze*).
   - **Lampiran Drawing/Berkas**: Unggah berkas gambar teknik 2D (PDF/DWG) atau 3D CAD.
4. Klik **Submit Request**. Tiket Anda akan terbit dengan status awal `Submitted / Unassigned`.

---

### 2.3 Menerima & Menegosiasikan Penawaran Harga (Quotation & Negotiation)
1. Setelah staf sales membuatkan penawaran, status tiket akan berubah menjadi `Quotation Sent`.
2. Buka menu **Quotations**, lalu klik tombol **Detail** pada nomor penawaran yang ingin ditinjau.
3. Anda dapat mengunduh dokumen penawaran resmi dalam format PDF melalui tombol **Download PDF**.
4. **Jika Harga Sesuai**: Klik tombol **Accept Quotation / Deal**. Status akan langsung menjadi `Accepted` dan siap diterbitkan PO.
5. **Jika Ingin Menawar Harga**:
   - Masukkan nominal harga yang Anda ajukan pada kolom **Offered Price**.
   - Masukkan catatan/alasan penawaran (contoh: *"Mohon diskon untuk pembelian volume 500 pcs"*).
   - Klik **Submit Negotiation**.
   - Sistem bersifat *turn-based* (bergantian). Tunggu tanggapan harga balik (*counter-offer*) dari tim sales. Anda memiliki kuota negosiasi hingga 3 putaran (maksimal 6 interaksi).

---

### 2.4 Menerbitkan Purchase Order (PO Pelanggan)
1. Setelah status Quotation menjadi `Accepted`, buka menu **Purchase Orders**.
2. Klik tombol **Create Customer PO**.
3. Lengkapi formulir PO:
   - **PO Number**: Masukkan nomor PO resmi dari sistem internal perusahaan Anda.
   - **Tanggal PO & Delivery Date**.
   - **Unggah Dokumen PO (PDF)**: Wajib melampirkan berkas PO bertanda tangan/stempel resmi.
4. Klik **Submit Purchase Order**. PO Anda otomatis masuk ke dashboard Sales PIC untuk diproses ke tahap verifikasi teknis pabrik.

---

### 2.5 Mengajukan Amandemen Pesanan (PO Amendment)
*Amandemen digunakan jika terjadi perubahan kuantiti, tanggal kirim, atau spesifikasi setelah PO diterbitkan.*

1. Buka menu **Purchase Orders**, pilih nomor PO terkait, lalu klik tab **Amandemen / Ajukan Perubahan**.
2. Ubah data yang diinginkan (contoh: penambahan kuantiti atau pergeseran tanggal kirim).
3. **Wajib Mengisi Alasan Amandemen**: Masukkan penjelasan yang valid (contoh: *"Revisi tanggal kirim menyesuaikan jadwal shutdown maintenance lini perakitan pelanggan"*).
4. Klik **Kirim Amandemen**.
   > **Catatan Penting**:
   > - Setiap item pesanan dibatasi maksimal **2 kali amandemen**.
   > - Kunci Produksi Parsial (*Per-Item Production Lock*): Fitur amandemen hanya terkunci pada sub-item yang sudah masuk tahap produksi (*In Production*). Sub-item lain pada PO yang sama yang masih berstatus *Review* tetap dapat diajukan amandemen.

---

### 2.6 Pelacakan Status Pesanan pada Portal Pelanggan (Customer Order Tracking)
1. Setelah login ke portal pelanggan, buka menu **Purchase Orders** atau **Dashboard**.
2. Pada tabel daftar pesanan, Anda dapat memantau progres seluruh pesanan secara real-time melalui lencana status:
   - **Sent / Draft**: PO resmi Anda telah masuk ke sistem dan sedang ditinjau Sales PIC.
   - **Review**: Pesanan sedang dalam tahap verifikasi teknis 4 divisi (Sales, Quality, PPC, Design Engineering).
   - **In Production**: Seluruh klausul kontrak seluruh item telah disetujui dan pesanan sedang aktif diproduksi di lantai pabrik.
3. Klik tombol **Detail / View Progress** pada baris pesanan untuk melihat linimasa (*timeline* aktivitas), rincian item, dan status verifikasi per-item secara transparan.

---

### 2.7 Mengelola Profil & Mengubah Password Mandiri
1. Klik avatar profil Anda di pojok kanan atas navbar, lalu pilih **Profile / Akun Saya**.
2. Anda dapat memperbarui informasi nama kontak, telepon, dan alamat perusahaan.
3. Untuk mengubah kata sandi:
   - Masukkan **Password Saat Ini (Current Password)**.
   - Masukkan **Password Baru** (minimal 6 karakter) dan **Konfirmasi Password Baru**.
   - Klik **Simpan Perubahan**. Sistem akan mengenkripsi kata sandi baru Anda secara aman menggunakan algoritma Bcrypt.

---

## 3. PANDUAN PENGGUNA: STAFF SALES (SALES PIC)

### 3.1 Klaim Penugasan Tiket (Claim / Assign Sales PIC)
1. Buka menu **Request Project**.
2. Pada tab/filter *Unassigned Requests*, cari inquiry pesanan baru dari pelanggan.
3. Klik tombol **Claim Ticket / Assign to Me**.
4. Anda resmi menjadi **Sales PIC** penanggung jawab atas pesanan tersebut. Staf sales lain otomatis terisolasi dan tidak dapat memanipulasi tiket Anda.

---

### 3.2 Pembuatan Penawaran Harga Resmi (Create Quotation)
1. Buka menu **Quotations**, lalu klik **+ Create Quotation**.
2. Pilih nomor *Request Project* yang telah Anda klaim.
3. Pilih produk dari daftar **Master Pricelist** atau masukkan rincian harga kustom:
   - Harga Satuan (*Unit Price*).
   - Syarat Pembayaran (*Payment Terms*, misal: *30 Days Net, DP 50%*).
   - Masa Berlaku Penawaran (*Quotation Validity*).
4. Klik **Save & Send to Customer**. Status akan berubah menjadi `Quotation Sent`.

---

### 3.3 Merespons Negosiasi Harga Pelanggan (Counter-Offer)
1. Buka menu **Negotiations** jika ada notifikasi penawaran baru dari pelanggan.
2. Klik tombol **Review & Counter Offer**.
3. Masukkan harga penawaran balik dari Sales.
   > **Proteksi Modal (Floor Price Enforcement)**:
   > Sistem secara otomatis **memblokir** penginputan harga di bawah harga modal bahan & proses kerja (*Floor Price*). Staf sales dilarang menjual rugi di bawah batas modal.
4. Klik **Submit Counter-Offer**.

---

### 3.4 Penerbitan PO Internal Per-Item (Create PO Internal)
1. Setelah PO Customer berstatus `Approved/Accepted`, buka menu **PO Internal**.
2. Klik tombol **Generate PO Internal from Customer PO**.
3. Sistem akan memecah pesanan menjadi item-item internal pabrik:
   - Nomor PO Internal unik (contoh: `POI-2026-XXXX`).
   - Alokasi nomor drawing dan kuantiti produksi per item.
4. Simpan data PO Internal.

---

### 3.5 Penyusunan Lembar Tinjauan Kontrak (Contract Review Sheet)
1. Buka menu **Contract Review Sheet**, pilih item PO Internal yang ingin diajukan, lalu klik **Create Contract Review**.
2. Rincian aspek tinjauan akan terbagi menjadi 4 bagian:
   - **Sales Requirements**: Harga final, terms of payment, tanggal kirim, dan catatan khusus pesanan.
   - **Quality Requirements**: Rencana sertifikat mutu (*CoA, Mill Sheet*), standar toleransi drawing, dan metode inspeksi (*Visual / Go-NoGo Gauge*).
   - **PPC Requirements**: Kebutuhan bahan baku (*scrap/ingot*), alokasi kapasitas tungku induksi/cetak pasir, dan target *lead time*.
   - **Design Engineering Requirements**: Verifikasi kelayakan *drawing 2D/3D*, *shrinkage allowance*, dan pola kayu/aluminium.
3. Jika pesanan berasal dari amandemen, **Alasan Amandemen** sebelumnya akan otomatis ditampilkan sebagai referensi tinjauan.
4. Klik **Submit for 4-Division Approval**.

---

### 3.6 Penanganan Revisi Bertarget (Targeted Revision)
1. Jika salah satu manajer menolak (*Reject*), status lembar kontrak akan menjadi `Revision`.
2. Buka lembar kontrak tersebut dan lihat **Catatan Alasan Penolakan** dari manajer yang bersangkutan.
3. Klik tombol **Edit / Revise Data**.
4. Perbaiki klausul yang diminta tanpa menghapus persetujuan dari divisi lain yang sudah menyetujui.
5. Klik **Resubmit Revision**. Dokumen hanya akan ditinjau kembali oleh manajer yang menolak sebelumnya.

---

### 3.7 Finalisasi Serah Terima ke Lini Produksi (In Production)
1. Setelah seluruh 4 divisi manajerial memberikan tanda tangan digital pada lembar kontrak sub-item (Status: `Approved`), tombol **Finalize to Production** akan aktif khusus untuk Sales PIC pemilik pesanan.
2. Klik tombol **Finalize to Production** pada nomor item / Sub-PO terkait.
3. **Mekanisme Finalisasi Parsial Sub-PO**:
   - Status Kontrak dan status PO Internal untuk sub-item yang difinalisasi akan berubah menjadi **`In Production`**.
   - Sub-item lain pada PO yang sama yang belum selesai diverifikasi atau masih dalam revisi **tidak akan terpengaruh** (tetap berstatus `Review` atau `Sent`).
   - **Status PO Utama (Master PO)** akan tetap berstatus **`Review`** (mengambil status progress minimum) dan **HANYA** berubah menjadi **`In Production`** setelah **100% seluruh sub-item (misal 4/4 data)** telah difinalisasi masuk ke lini produksi.

---

### 3.8 Persetujuan Amandemen Pesanan (Approval Amandemen)
1. Buka menu **PO Amandemen**.
2. Periksa pengajuan amandemen pelanggan beserta alasan perubahannya.
3. Klik **Approve** jika kapasitas dan jadwal memungkinkan, atau klik **Reject** dengan memberikan alasan penolakan.

---

## 4. PANDUAN PENGGUNA: 4 DIVISI MANAJERIAL

### 4.1 Review Aspek Divisi Sales (Manager Sales)
- **Menu Akses**: `Contract Review Sheet` $\rightarrow$ Filter `Sales Review`.
- **Fokus Tinjauan**: Memastikan profitabilitas pesanan, kecukupan batas kredit/termin pembayaran pelanggan, dan klausul khusus komersial.
- **Wewenang Khusus**: Memiliki tombol **Override Negotiation Limit** jika pelanggan membutuhkan perpanjangan putaran tawar-menawar harga. *(Catatan: Manager Sales tidak memiliki akses ke User Management)*.

---

### 4.2 Review Aspek Mutu & Standar Spesifikasi (Manager Quality / QC)
- **Menu Akses**: `Contract Review Sheet` $\rightarrow$ Filter `Quality Review`.
- **Fokus Tinjauan**:
  - Kesesuaian standar drawing teknis (*Blueprint Revision*).
  - Persyaratan pengujian laboratorium (misal: *Spectrometry test, Tensile Strength, Hardness Test HB/HRC*).
  - Wajib tidaknya melampirkan *Certificate of Analysis (CoA)* dan *Mill Sheet* saat pengiriman.

---

### 4.3 Review Aspek Kapasitas & Jadwal (Manager PPC)
- **Menu Akses**: `Contract Review Sheet` $\rightarrow$ Filter `PPC Review`.
- **Fokus Tinjauan**:
  - Ketersediaan stok material logam mentah (*Pig Iron, Return Scrap, Ferro Alloys*).
  - Antrean peleburan furnace cor dan utilisasi mesin CNC/Bubut/Milling.
  - Kesanggupan tanggal pengiriman (*Delivery Commitment Date*).

---

### 4.4 Review Aspek Gambar Teknik & Cetakan (Manager Design Engineering)
- **Menu Akses**: `Contract Review Sheet` $\rightarrow$ Filter `Design Eng. Review`.
- **Fokus Tinjauan**:
  - Ketepatan dimensi, sudut tekukan, dan *machining allowance*.
  - Desain sistem saluran masuk (*Gating & Riser System*) dan faktor penyusutan cor (*Shrinkage*).
  - Kesiapan pola cetakan (*Wooden/Aluminium Pattern*).

---

### 4.5 Persetujuan Tanda Tangan Digital & Penolakan Berita Acara
1. Buka detail lembar kontrak yang perlu ditinjau.
2. Periksa seluruh spesifikasi teknis pada tabel rincian.
3. **Jika Disetujui (Approve)**:
   - Gunakan mouse (pada desktop) atau layar sentuh (pada tablet/HP) pada area kotak **Digital Signature Pad**.
   - Bubuhkan tanda tangan digital Anda.
   - Klik tombol hijau **Approve & Sign**.
4. **Jika Ditolak (Reject)**:
   - Klik tombol merah **Reject with Reason**.
   - Masukkan alasan penolakan yang spesifik dan jelas (contoh: *"Toleransi lubang diameter 20mm terlalu sempit, perlu penyesuaian drawing rev 2"*).
   - Klik **Confirm Rejection**. Dokumen akan kembali ke Sales PIC untuk diperbaiki.

---

## 5. PANDUAN PENGGUNA: SUPER ADMIN

### 5.1 Manajemen Pengguna (User Management - Hak Eksklusif Super Admin)
> **Hak Akses Eksklusif**: Menu **User Management** hanya dapat diakses oleh akun dengan peran **Super Admin**. Seluruh manajer divisi, staf, dan customer dibatasi total dari menu ini.
1. Buka menu **User Management** pada sidebar:
   - **Data Customer**: Menambah, mengedit, atau menonaktifkan akun buyer dan menghubungkannya dengan profil perusahaan.
   - **Data Employee**: Mengatur akun staf sales, manajer per divisi (Sales, Quality, PPC, Design Engineering), penugasan cabang pabrik (*Jakarta, Bekasi, Salatiga*), serta peran Super Admin.
2. Klik tombol **+ Add New User**, isi data dan pilih Role & Divisi yang sesuai.

---

### 5.2 Manajemen Master Data Produk & Floor Price
1. Buka menu **Pricelist Products**.
2. Kelola daftar komponen/artikel manufaktur:
   - **Article Name / Code**: Kode part unik dan nama barang.
   - **Standard Price List**: Harga jual katalog resmi.
   - **Floor Price (Modal)**: Batas harga dasar modal (Bahan + Biaya Mesin) yang menjadi acuan proteksi sistem saat staf sales merespons negosiasi.
3. Klik **Save Article**.

---

### 5.3 Supervisi & Audit Log Transaksi
1. Super Admin memiliki akses pantau (*Bypass & Audit Access*) untuk seluruh tahapan pesanan lintas divisi.
2. Dapat mengekspor seluruh transaksi ke berkas Excel/PDF untuk kebutuhan audit laporan bulanan direksi.

---

## 6. FITUR TAMBAHAN: DUAL THEME (DARK MODE) & RESPONSIVE MOBILE

### 6.1 Beralih Antara Mode Terang & Gelap (Dark Mode Switcher)
1. Perhatikan bagian pojok atas sidebar di samping logo **Product Sales**.
2. Klik sakelar (*toggle switch*) berbentuk ikon matahari/bulan.
3. Antarmuka sistem akan seketika beralih ke palet warna kontras tinggi yang nyaman di mata. Pilihan tema Anda akan **tersimpan otomatis** di browser dan tetap aktif saat membuka halaman lain.

---

### 6.2 Navigasi pada Perangkat Mobile & Tablet (Smartphone / iPad)
1. **Membuka Menu Navigasi**: Pada layar HP atau tablet, klik ikon tiga garis horizontal (**Burger Button $\equiv$**) di pojok kiri atas navbar.
2. **Tabel Data Responsif**: Tabel transaksi yang memiliki banyak kolom dapat digeser ke kanan dan kiri menggunakan sentuhan jari (*swipe horizontal*).
3. **Tanda Tangan Digital Sentuh**: Manajer dapat membubuhkan tanda tangan langsung menggunakan jari atau stylus pen pada layar smartphone/tablet secara presisi.

---

## 7. RANGKUMAN MATRIKS STATUS PESANAN (STATUS REFERENCE)

| Status Kode | Label Status | Makna Operasional |
|---|---|---|
| `unassigned` | **Request Submitted** | Permintaan proyek masuk dari pelanggan dan menunggu klaim Sales PIC. |
| `assigned` | **PIC Assigned** | Sales PIC telah ditugaskan dan sedang menghitung penawaran harga. |
| `quotation_sent` | **Quotation Sent** | Dokumen penawaran resmi telah dikirim ke pelanggan. |
| `negotiating` | **In Negotiation** | Pelanggan atau sales sedang melakukan tawar-menawar harga bergantian. |
| `accepted` | **Quotation Accepted** | Kesepakatan harga telah disetujui kedua belah pihak. |
| `po_received` | **Customer PO Issued** | Pelanggan telah mengunggah berkas Purchase Order resmi. |
| `poi_created` | **PO Internal Ready** | Sales PIC telah menerbitkan rincian nomor item PO Internal pabrik. |
| `review_4_div` | **Contract Reviewing** | Lembar peninjauan kontrak sedang dalam proses tanda tangan 4 Manajer. |
| `revision` | **Contract Revision** | Salah satu manajer meminta revisi teknis/komersial sebelum menyetujui. |
| `approved` | **Contract Approved** | 4 Divisi Manajerial telah lengkap menandatangani kontrak secara digital. |
| `production` | **In Production** | Sales PIC telah melakukan serah terima final dan pesanan resmi diproduksi di pabrik. |

---

*Buku Panduan Pengguna ini disusun secara resmi untuk operasional sistem PT. Metinca Prima Industrial Works Jakarta.*
