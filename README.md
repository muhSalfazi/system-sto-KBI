# 📦 STO Management System

STO Management System adalah aplikasi web yang digunakan untuk mengelola inventory dan laporan STO (Stock Take Operation). Aplikasi ini memungkinkan pengguna untuk mencari, menambah, mengedit, menghapus, dan mengekspor data inventory dan laporan STO.

## ✨ Fitur

- 🔍 **Pencarian**: Pengguna dapat mencari inventory berdasarkan nama bagian atau nomor bagian.
- 📝 **Manajemen Laporan**: Pengguna dapat menambah, mengedit, dan menghapus laporan STO.
- 📊 **Ekspor ke Excel**: Pengguna dapat mengekspor laporan yang dipilih ke file Excel.
- 🖨️ **Cetak Laporan**: Pengguna dapat mencetak laporan STO dalam format PDF dengan kode QR.
- 📷 **Scan Barcode**: Pengguna dapat memindai barcode untuk mencari inventory dengan cepat.

## 🚀 Instalasi

1. Clone repositori ini:

   ```sh
   git clone (https://github.com/muhSalfazi/system-sto-KBI.git)
   cd update_sto
   ```

2. Install dependensi menggunakan Composer:

   ```sh
   composer install
   ```

3. Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database:

   ```sh
   cp .env.example .env
   ```

4. Generate kunci aplikasi:

   ```sh
   php artisan key:generate
   ```

5. Migrasi dan seed database:

   ```sh
   php artisan migrate --seed
   ```

6. Jalankan server pengembangan:

   ```sh
   php artisan serve
   ```

7. Buka aplikasi di browser:

   ```
   http://localhost:8000
   ```

## 📚 Penggunaan

### 🔍 Pencarian Inventory

1. Buka halaman pencarian inventory.
2. Masukkan nama bagian atau nomor bagian yang ingin dicari.
3. Klik tombol "Search" untuk melihat hasil pencarian.

### 📝 Manajemen Laporan

1. Buka halaman laporan.
2. Klik tombol "Add Report" untuk menambah laporan baru.
3. Isi formulir laporan dan klik "Save" untuk menyimpan laporan.
4. Untuk mengedit laporan, klik tombol "Edit" di sebelah laporan yang ingin diedit.
5. Untuk menghapus laporan, klik tombol "Delete" di sebelah laporan yang ingin dihapus.

### 📊 Ekspor ke Excel

1. Pilih laporan yang ingin diekspor dengan mencentang kotak di sebelah laporan.
2. Klik tombol "Export Selected to Excel" untuk mengekspor laporan yang dipilih ke file Excel.

### 🖨️ Cetak Laporan

1. Klik tombol "Print" di sebelah laporan yang ingin dicetak.
2. Laporan akan dihasilkan dalam format PDF dengan kode QR.

### 📷 Scan Barcode

1. Buka halaman scan barcode.
2. Arahkan kamera perangkat ke barcode yang ingin dipindai.
3. Hasil pemindaian akan menampilkan informasi inventory yang sesuai.


