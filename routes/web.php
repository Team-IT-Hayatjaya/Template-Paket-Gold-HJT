<?php

use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Website Resmi Instansi Pemerintah
|--------------------------------------------------------------------------
| Rute situs publik (Blade + Tailwind).
| Panel administrasi dikelola sepenuhnya oleh Filament di /admin.
|
*/

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/profil', [PublicPageController::class, 'profil'])->name('profil');
Route::get('/pemerintahan', [PublicPageController::class, 'pemerintahan'])->name('pemerintahan');
Route::get('/berita', [PublicPageController::class, 'berita'])->name('berita.index');
Route::get('/berita/{slug}', [PublicPageController::class, 'detailBerita'])->name('berita.detail');
Route::get('/galeri', [PublicPageController::class, 'galeri'])->name('galeri');
Route::get('/layanan', [PublicPageController::class, 'layanan'])->name('layanan');
Route::get('/layanan/{slug}', [PublicPageController::class, 'detailLayanan'])->name('layanan.detail');
Route::get('/data-statistik', [PublicPageController::class, 'statistik'])->name('statistik.index');
Route::get('/data-statistik/{slug}', [PublicPageController::class, 'detailStatistik'])->name('statistik.detail');
Route::get('/publikasi', [PublicPageController::class, 'publikasi'])->name('publikasi');
Route::get('/kontak', [PublicPageController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PublicPageController::class, 'kirimPengaduan'])
    ->middleware('throttle:10,1')
    ->name('kontak.kirim');
Route::get('/komponen', [PublicPageController::class, 'komponen'])->name('komponen');

// Halaman Statis Dinamis (Profil, Visi Misi, Struktur Organisasi, Tugas dan Fungsi)
Route::get('/halaman/{slug}', [PublicPageController::class, 'halaman'])->name('halaman.show');

// SEO Dasar: Sitemap XML & Robots.txt
Route::get('/sitemap.xml', [PublicPageController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicPageController::class, 'robots'])->name('robots');

// Layanan PPID (UU No. 14 Tahun 2008)
use App\Http\Controllers\PpidController;

Route::get('/ppid/daftar-informasi', [PpidController::class, 'daftarInformasi'])->name('ppid.daftar-informasi');
Route::get('/ppid/permohonan', [PpidController::class, 'formPermohonan'])->name('ppid.permohonan');
Route::post('/ppid/permohonan', [PpidController::class, 'kirimPermohonan'])
    ->middleware('throttle:10,1')
    ->name('ppid.kirim-permohonan');
Route::get('/ppid/lacak', [PpidController::class, 'lacak'])->name('ppid.lacak');

// Unduh berkas identitas privat pemohon PPID (Khusus Petugas PPID & Super Admin)
Route::get('/admin/permohonan/{permohonan}/identitas', [PpidController::class, 'downloadIdentitas'])
    ->middleware('auth')
    ->name('ppid.download-identitas');

// Ekspor Laporan Bulanan Resmi ke PDF
use App\Http\Controllers\Admin\ReportController;

Route::get('/admin/laporan-bulanan/pdf', [ReportController::class, 'downloadPdf'])
    ->middleware('auth')
    ->name('admin.laporan-bulanan.pdf');

// Pemeriksaan Kesehatan Sistem (Database, Storage, Queue)
use App\Http\Controllers\HealthCheckController;

Route::get('/health', HealthCheckController::class)->name('health');



