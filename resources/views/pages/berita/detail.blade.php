@extends('layouts.app')

@section('title', ($berita['meta_title'] ?? $berita['judul']) . ' - ' . ($siteSetting->institution_name ?? 'Portal Resmi'))
@section('meta_title', $berita['meta_title'] ?? $berita['judul'])
@section('meta_description', $berita['meta_description'] ?? $berita['ringkasan'])
@section('og_type', 'article')
@if(!empty($berita['gambar']))
    @section('og_image', $berita['gambar'])
@endif

@section('content')
<div class="space-y-space-xl max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Berita & Publikasi', 'url' => route('berita.index')],
        ['label' => $berita['judul']],
    ]" />

    <!-- Artikel Utama -->
    <article class="bg-surface-container-lowest rounded-2xl border border-surface-container p-space-lg sm:p-space-xl shadow-2xs">
        <header class="mb-space-lg pb-space-lg border-b border-surface-container space-y-space-sm">
            <div class="flex items-center gap-space-xs flex-wrap">
                <span class="inline-block px-3 py-1 rounded-md font-label-sm text-label-sm font-bold bg-secondary-fixed text-primary">
                    {{ $berita['kategori'] }}
                </span>
                <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">verified</span> Rilis Resmi Instansi
                </span>
            </div>
            
            <h1 class="font-headline-xl text-headline-xl text-primary leading-tight font-bold tracking-tight">
                {{ $berita['judul'] }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-body-sm font-body-sm text-on-surface-variant pt-space-xs">
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="material-symbols-outlined text-[18px] text-secondary">calendar_today</span>
                    {{ $berita['tanggal'] }}
                </span>
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="material-symbols-outlined text-[18px] text-secondary">person</span>
                    Oleh: {{ $berita['penulis'] }}
                </span>
                <span class="flex items-center gap-1.5 font-medium">
                    <span class="material-symbols-outlined text-[18px] text-secondary">domain</span>
                    {{ $siteSetting->institution_name ?? 'Pemerintah Instansi' }}
                </span>
            </div>
        </header>

        @if(!empty($berita['gambar']))
            <div class="mb-space-lg rounded-xl overflow-hidden max-h-[480px] border border-surface-container shadow-xs">
                <img src="{{ $berita['gambar'] }}" alt="{{ $berita['judul'] }}" class="w-full h-full object-cover" />
            </div>
        @endif

        <!-- Ringkasan / Lead Paragraph -->
        @if(!empty($berita['ringkasan']))
            <div class="p-space-md sm:p-space-lg bg-surface-container-low rounded-xl border-l-4 border-secondary mb-space-lg font-headline-md text-[17px] text-primary italic leading-relaxed">
                {{ $berita['ringkasan'] }}
            </div>
        @endif

        <!-- Isi Artikel Lengkap -->
        <div class="prose prose-slate max-w-none text-on-surface leading-relaxed space-y-space-md font-body-lg text-body-lg">
            {!! $berita['isi'] !!}
        </div>

        <footer class="mt-space-xl pt-space-md border-t border-surface-container flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-sm">
            <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 px-space-md py-2.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md transition">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Daftar Berita</span>
            </a>
            <span class="font-label-sm text-label-sm text-on-surface-variant">
                Portal Resmi &bull; {{ $siteSetting->institution_acronym ?? 'Instansi' }}
            </span>
        </footer>
    </article>

    <!-- Berita Terkait -->
    @if(!empty($beritaTerkait))
        <section class="mt-space-xl space-y-space-md" aria-labelledby="heading-terkait">
            <div class="flex items-center justify-between border-b border-surface-container pb-space-xs">
                <h2 id="heading-terkait" class="font-title-lg text-title-lg text-primary font-bold">
                    Berita dan Informasi Terkait
                </h2>
                <a href="{{ route('berita.index') }}" class="font-label-sm text-label-sm text-secondary hover:underline font-bold">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-space-md">
                @foreach($beritaTerkait as $rel)
                    @if($rel['slug'] !== $berita['slug'])
                        <div class="p-space-md bg-surface-container-lowest rounded-xl border border-surface-container shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <span class="font-label-sm text-[11px] text-secondary font-bold block mb-1">{{ $rel['tanggal'] }}</span>
                                <h3 class="font-title-lg text-[15px] font-bold text-primary line-clamp-2 hover:text-secondary leading-snug">
                                    <a href="{{ route('berita.detail', $rel['slug']) }}">{{ $rel['judul'] }}</a>
                                </h3>
                            </div>
                            <a href="{{ route('berita.detail', $rel['slug']) }}" class="font-label-sm text-label-sm font-bold text-primary hover:text-secondary inline-flex items-center gap-1 mt-4">
                                <span>Baca rilis</span>
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
