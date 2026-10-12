# Aturan & Panduan Pengembangan Proyek

**Proyek:** Website Instansi Pemerintah — PT Hayat Jaya Teknologi (HJT)  
**Paket:** SILVER  
**Bahasa Antarmuka:** Bahasa Indonesia  
**Regulasi PPID:** Mengacu pada UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik  

---

## 1. Aturan Front End
- **Stack:** Blade, Tailwind CSS, Alpine.js, Vite.
- **Satu Template Standar:** SATU template standar untuk semua klien Silver.
- **Kustomisasi Tema:** Hanya diatur lewat variabel dinamis (logo, nama instansi, warna utama, favicon) yang bersumber dari tabel `pengaturan_ppid` / config, BUKAN desain kustom per klien.
- **Mobile-first & Responsif:** Desain responsif di semua ukuran layar.
- **Aksesibilitas Standar (Tanpa Widget):** Walau tanpa widget aksesibilitas (TTS/contrast switch), tetap terapkan HTML semantik, label formulir yang jelas, alt gambar, fokus terlihat (visible focus), dan kontras warna memadai.
- **Kinerja:** Format gambar WebP, lazy loading (`loading="lazy"`), hindari library eksternal berat yang tidak perlu.
- **Komponen Modular:** Buat komponen Blade (`resources/views/components/`) agar dapat digunakan kembali oleh template Gold dan Platinum kelak.
- **Bahasa:** Seluruh teks antarmuka wajib Bahasa Indonesia yang baku dan formal.

---

## 2. Aturan Back End & Database
- **Stack:** Laravel (PHP) dan MySQL / SQLite (lokal).
- **Tabel Paket Silver:**
  1. `users`
  2. `roles` & permissions via `spatie/laravel-permission`
  3. `kategori_informasi`
  4. `dokumen_informasi`
  5. `permohonan_informasi`
  6. `status_permohonan`
  7. `keberatan`
  8. `pengaturan_ppid`
  9. `log_aktivitas`
- **Tabel DILARANG DIBUAT (Fitur Gold):**
  - ❌ `riwayat_permohonan`
  - ❌ `jawaban_permohonan`
  - ❌ `tanggapan_keberatan`
  - ❌ `hari_libur`
  *(Catatan: Kolom `tenggat_jawab` dan `tenggat_diperpanjang` pada tabel permohonan boleh disiapkan sebagai nullable untuk kompatibilitas Gold, tetapi dibiarkan kosong).*
- **Alur Pemrosesan Jawaban:**
  - Jawaban permohonan dan keberatan diproses admin **DI LUAR SISTEM** (melalui email resmi / surat luring).
  - Sistem HANYA mencatat permohonan & keberatan, menyimpan status manual internal (tidak ada pelacakan/tracking publik), dan mengirim email konfirmasi registrasi.
- **Keamanan:**
  - Validasi wajib melalui **Form Request**.
  - Query binding / Eloquent murni.
  - Proteksi CSRF di setiap formulir.
  - Rate limiting pada formulir publik (`throttle`).
  - NIK wajib menggunakan **encrypted cast** (`'nik' => 'encrypted'`).
  - Berkas identitas (KTP/SK) disimpan di **storage non-publik** (private storage), bukan `public`.
  - Catat setiap perubahan penting ke tabel `log_aktivitas`.
- **Struktur Kode & Rekayasa:**
  - Service class untuk seluruh logika bisnis (`app/Services/`).
  - Policy untuk kontrol otorisasi hak akses (`app/Policies/`).
  - Testing wajib (PHPUnit / Pest).
  - Migration wajib memiliki method `down()` yang dapat di-rollback tanpa error.

---

## 3. Hosting & Ketergantungan Eksternal
- **Target:** VPS kecil / Cloud hosting standar (SSH & cron tersedia).
- **Ketergantungan:** DILARANG bergantung pada Redis, daemon worker konstan, atau Docker di produksi.
- **Queue:** Driver `database` atau `sync`.
- **Cron:** `php artisan schedule:run`.
