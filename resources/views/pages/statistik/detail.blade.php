@extends('layouts.app')

@section('title', $statistik->judul . ' - ' . ($siteSetting->institution_name ?? 'Portal Resmi'))
@section('meta_title', 'Data Statistik: ' . $statistik->judul)
@section('meta_description', 'Publikasi data statistik ' . $statistik->kategori . ' periode ' . $statistik->periode . ' resmi.')

@section('content')
<div class="space-y-space-xl max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Data & Statistik', 'url' => route('statistik.index')],
        ['label' => $statistik->judul],
    ]" />

    <!-- Header Section -->
    <section class="bg-surface-container-lowest p-space-lg lg:p-space-xl rounded-2xl shadow-2xs border border-surface-container space-y-space-md">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-secondary-fixed text-primary uppercase tracking-wider">
                {{ $statistik->kategori }}
            </span>
            <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-3 py-1 rounded-full">
                Periode: {{ $statistik->periode }}
            </span>
            @if($statistik->sumber)
                <span class="text-xs text-on-surface-variant flex items-center gap-1 bg-surface-container px-3 py-1 rounded-full">
                    <span class="material-symbols-outlined text-[14px] text-secondary">source</span>
                    <span>Sumber: {{ $statistik->sumber }}</span>
                </span>
            @endif
        </div>

        <h1 class="font-headline-xl text-headline-xl text-primary font-bold tracking-tight">
            {{ $statistik->judul }}
        </h1>
    </section>

    <!-- Konten Data Statistik -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        <div class="lg:col-span-8 space-y-space-lg">
            <!-- Gambar Infografis jika ada -->
            @if(!empty($statistik->gambar_infografis))
                <div class="bg-surface-container-lowest p-space-md rounded-2xl border border-surface-container shadow-2xs overflow-hidden">
                    <h3 class="font-title-md text-sm font-bold text-primary mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-secondary">image</span>
                        <span>Visualisasi Infografis Data</span>
                    </h3>
                    <img src="{{ asset('storage/' . $statistik->gambar_infografis) }}" alt="{{ $statistik->judul }}" class="w-full h-auto rounded-xl border border-surface-container shadow-xs">
                </div>
            @endif

            <!-- Tabel Data Rich Text -->
            <div class="bg-surface-container-lowest p-space-lg rounded-2xl border border-surface-container shadow-2xs space-y-space-md">
                <div class="flex items-center justify-between border-b border-surface-container pb-3">
                    <h2 class="font-title-lg text-lg font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">table_chart</span>
                        <span>Tabel Rincian Data Statistik</span>
                    </h2>

                    @if(!empty($statistik->berkas_pdf))
                        <a href="{{ asset('storage/' . $statistik->berkas_pdf) }}" target="_blank" download class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition shadow-2xs">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Unduh PDF</span>
                        </a>
                    @endif
                </div>

                <div class="prose max-w-none text-sm text-on-surface leading-relaxed overflow-x-auto">
                    {!! $statistik->isi !!}
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan -->
        <div class="lg:col-span-4 space-y-space-md">
            <!-- Permintaan Data Tambahan PPID -->
            <div class="bg-surface-container-lowest p-space-md rounded-2xl border border-surface-container shadow-2xs space-y-space-sm">
                <h3 class="font-title-md text-sm font-bold text-primary border-b border-surface-container pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-secondary">help</span>
                    <span>Butuh Data Lebih Spesifik?</span>
                </h3>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Masyarakat atau peneliti yang memerlukan rincian data sektoral dapat mengajukan Permohonan Informasi Publik resmi kepada PPID.
                </p>
                <div class="pt-2">
                    <a href="{{ route('ppid.permohonan') }}" class="w-full text-center block py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition shadow-2xs">
                        Ajukan Permohonan Data PPID &rarr;
                    </a>
                </div>
            </div>

            <!-- Data Statistik Lainnya -->
            @if(isset($statistikLain) && $statistikLain->isNotEmpty())
                <div class="bg-surface-container-lowest p-space-md rounded-2xl border border-surface-container shadow-2xs space-y-space-sm">
                    <h3 class="font-title-md text-sm font-bold text-primary border-b border-surface-container pb-2">
                        Data Statistik Terkait
                    </h3>
                    <div class="divide-y divide-surface-container">
                        @foreach($statistikLain as $item)
                            <a href="{{ route('statistik.detail', $item->slug) }}" class="block py-2.5 hover:bg-surface-container-low transition rounded-lg px-2 group">
                                <div class="text-[11px] font-bold text-secondary uppercase">{{ $item->kategori }}</div>
                                <div class="text-xs font-bold text-primary group-hover:text-secondary transition-colors mt-0.5 line-clamp-2">{{ $item->judul }}</div>
                                <div class="text-[11px] text-on-surface-variant mt-1">{{ $item->periode }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
