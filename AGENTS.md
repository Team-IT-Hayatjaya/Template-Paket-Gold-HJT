# Aturan & Panduan Pengembangan Proyek

**Proyek:** Website Instansi Pemerintah — PT Hayat Jaya Teknologi (HJT)  
**Paket:** GOLD  
**Cabang Git:** `gold`  
**Bahasa Antarmuka:** Bahasa Indonesia  
**Regulasi PPID:** Mengacu pada UU No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik  

---

## ⚠️ PERINGATAN ISOLASI REPOSITORI (SANGAT KRUSIAL)
- **AGENT DILARANG KERAS MENYENTUH FOLDER SILVER:**  
  Folder `c:\Users\User\Documents\Template Website Paket silver 2` adalah rilis beku (frozen release) berlabel tag `silver-v1.0`.  
  **DILARANG** melakukan modifikasi, penghapusan, atau eksekusi berkas di dalam direktori Silver tersebut.  
  Seluruh aktivitas pengembangan Gold **HANYA** boleh dilakukan di dalam repositori ini: `c:\Users\User\Documents\hjt-gold`.

---

## 1. Identitas & Lingkungan Paket Gold
- **Nama Aplikasi:** `Portal Resmi Instansi Pemerintah - Paket Gold` (atau disesuaikan dengan instansi klien Gold)
- **Paket Aktif:** `SITE_PACKAGE=gold`
- **Isolasi Sesi & Cache:**
  - `SESSION_COOKIE=hjt_gold_session`
  - `CACHE_PREFIX=hjt_gold_cache`
- **Database:** Terisolasi mandiri pada `database/database.sqlite` (atau database MySQL khusus klien Gold).
- **Badge Admin:** Panel admin Filament menampilkan badge khusus **⭐ PAKET GOLD** pada topbar dan judul panel untuk membedakan visual secara instan dari paket Silver.

---

## 2. Fitur & Kapasitas Paket Gold
- **Fitur Unggulan Gold:**
  1. Pelacakan status permohonan informasi publik secara langsung (`ppid_tracking`).
  2. Modul respon / jawaban permohonan & tanggapan keberatan di dalam sistem (`jawaban_permohonan`, `tanggapan_keberatan`).
  3. Perhitungan hari kerja akurat dengan tabel `hari_libur`.
  4. Widget aksesibilitas publik (TTS, kontras warna, ukuran font).
  5. Kuota pembaruan konten: 20 per bulan (`content_updates_per_month => 20`).
  6. Jadwal backup otomatis: Mingguan (`backup_frequency => weekly`).

---

## 3. Aturan Front End
- **Stack:** Blade, Tailwind CSS, Alpine.js, Vite.
- **Kustomisasi Tema:** Melalui variabel dinamis (logo, nama instansi, warna utama, favicon) bersumber dari tabel pengaturan / config.
- **Mobile-first & Responsif:** Desain responsif di semua perangkat.
- **Kinerja:** Format WebP, lazy loading (`loading="lazy"`), aset terkompilasi Vite.
- **Komponen Modular:** Menggunakan komponen Blade (`resources/views/components/`).
- **Bahasa:** Seluruh teks antarmuka wajib Bahasa Indonesia yang baku dan formal.

---

## 4. Aturan Back End & Database
- **Stack:** Laravel (PHP) dan SQLite / MySQL.
- **Keamanan:**
  - Validasi wajib melalui **Form Request**.
  - Query binding / Eloquent murni.
  - Proteksi CSRF di setiap formulir.
  - Rate limiting pada formulir publik (`throttle`).
  - NIK wajib menggunakan **encrypted cast** (`'nik' => 'encrypted'`).
  - Berkas identitas (KTP/SK) disimpan di **storage non-publik** (private storage).
  - Catat setiap perubahan penting ke tabel `log_aktivitas` / `activity_log`.
- **Struktur Kode & Rekayasa:**
  - Service class untuk seluruh logika bisnis (`app/Services/`).
  - Policy untuk kontrol otorisasi hak akses (`app/Policies/`).
  - Testing wajib (PHPUnit / Pest).
  - Migration wajib memiliki method `down()` yang dapat di-rollback tanpa error.
