<?php

namespace App\Services;

use App\Models\Berita;
use App\Models\DokumenInformasi;
use App\Models\Galeri;
use App\Models\Halaman;
use App\Models\PageView;
use App\Models\PengaturanPpid;
use App\Models\PermohonanInformasi;
use App\Models\SiteSetting;
use App\Models\StatusPermohonan;
use App\Support\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyReportService
{
    /**
     * Nama-nama bulan dalam Bahasa Indonesia.
     */
    public const NAMA_BULAN = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Kumpulkan seluruh data statistik untuk laporan bulanan.
     */
    public function getReportData(int $year, int $month): array
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $namaBulan = self::NAMA_BULAN[$month] ?? Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        // 1. Statistik Pengunjung
        $totalViews = PageView::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->sum('views');

        $viewsByDevice = [
            'desktop' => PageView::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('perangkat', 'desktop')
                ->sum('views'),
            'mobile' => PageView::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('perangkat', 'mobile')
                ->sum('views'),
            'tablet' => PageView::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('perangkat', 'tablet')
                ->sum('views'),
        ];

        // 2. Halaman Terpopuler (Top 10)
        $topPages = PageView::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->select('halaman', DB::raw('SUM(views) as total_views'))
            ->groupBy('halaman')
            ->orderByDesc('total_views')
            ->take(10)
            ->get()
            ->map(fn ($item) => [
                'halaman' => $item->halaman,
                'views' => (int) $item->total_views,
            ])
            ->toArray();

        // 3. Jumlah Pembaruan Konten
        $updatesBerita = Berita::whereBetween('updated_at', [$startDate, $endDate])->count();
        $updatesHalaman = Halaman::whereBetween('updated_at', [$startDate, $endDate])->count();
        $updatesDokumen = DokumenInformasi::whereBetween('updated_at', [$startDate, $endDate])->count();
        $updatesGaleri = Galeri::whereBetween('updated_at', [$startDate, $endDate])->count();
        $totalContentUpdates = $updatesBerita + $updatesHalaman + $updatesDokumen + $updatesGaleri;

        $contentQuotaLimit = Package::limit('content_updates_per_month');

        // 4. Permohonan Informasi PPID dan Statusnya
        $totalPermohonan = PermohonanInformasi::whereBetween('created_at', [$startDate, $endDate])->count();

        $statuses = StatusPermohonan::orderBy('urutan')->get();
        $statusBreakdown = [];

        foreach ($statuses as $st) {
            $count = PermohonanInformasi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status_id', $st->id)
                ->count();

            $statusBreakdown[] = [
                'nama' => $st->nama,
                'slug' => $st->slug,
                'warna' => $st->warna,
                'jumlah' => $count,
                'persentase' => $totalPermohonan > 0 ? round(($count / $totalPermohonan) * 100, 1) : 0,
            ];
        }

        // Permohonan yang telah dijawab / selesai
        $dijawabCount = PermohonanInformasi::whereBetween('tanggal_dijawab', [$startDate, $endDate])->count();

        // 5. Informasi Instansi
        $siteSetting = SiteSetting::first();
        $pengaturanPpid = PengaturanPpid::first();

        return [
            'year' => $year,
            'month' => $month,
            'nama_bulan' => $namaBulan,
            'period_label' => "{$namaBulan} {$year}",
            'total_views' => (int) $totalViews,
            'views_by_device' => $viewsByDevice,
            'top_pages' => $topPages,
            'content_updates' => [
                'total' => $totalContentUpdates,
                'berita' => $updatesBerita,
                'halaman' => $updatesHalaman,
                'dokumen' => $updatesDokumen,
                'galeri' => $updatesGaleri,
                'quota_limit' => $contentQuotaLimit,
            ],
            'permohonan' => [
                'total' => $totalPermohonan,
                'dijawab' => $dijawabCount,
                'breakdown' => $statusBreakdown,
            ],
            'instansi' => [
                'nama' => $siteSetting->institution_name ?? 'Pemerintah Instansi',
                'acronym' => $siteSetting->institution_acronym ?? 'PPID',
                'alamat' => $siteSetting->address ?? '-',
                'email' => $siteSetting->email ?? '-',
                'telepon' => $siteSetting->phone ?? '-',
                'atasan_ppid' => $pengaturanPpid->nama_atasan_ppid ?? 'Pimpinan Instansi',
                'pejabat_ppid' => $pengaturanPpid->nama_pejabat_ppid ?? 'Pejabat Pengelola Informasi dan Dokumentasi',
            ],
        ];
    }

    /**
     * Ekspor laporan bulanan ke berkas PDF resmi.
     */
    public function exportPdf(int $year, int $month)
    {
        $data = $this->getReportData($year, $month);

        $pdf = Pdf::loadView('reports.laporan-bulanan-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);

        $fileName = sprintf('Laporan-Bulanan-PPID-%s-%02d.pdf', $year, $month);

        return $pdf->download($fileName);
    }
}
