@extends('layouts.app')

@section('title', ($halaman->meta_title ?: $halaman->judul) . ' - ' . ($siteSetting->institution_name ?? 'Portal Resmi'))
@section('meta_title', $halaman->meta_title ?: $halaman->judul)
@section('meta_description', $halaman->meta_description ?: ($halaman->ringkasan ?: Str::limit(strip_tags($halaman->isi), 160)))

@if($halaman->gambar_utama)
    @section('og_image', asset('storage/' . $halaman->gambar_utama))
@endif

@section('content')
<div class="space-y-space-xl max-w-5xl mx-auto">
    @php
        $navItem = \App\Models\NavigationMenu::where('url', '/halaman/' . $halaman->slug)->first();
        $parentMenu = $navItem?->parent;
        $parentLabel = $parentMenu ? $parentMenu->nama : 'Profil Instansi';
        $parentUrl = $parentMenu ? url($parentMenu->url) : route('profil');
    @endphp
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => $parentLabel, 'url' => $parentUrl],
        ['label' => $halaman->judul],
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        <!-- Main Article Content -->
        <article class="lg:col-span-8 bg-surface-container-lowest rounded-2xl border border-surface-container p-space-lg sm:p-space-xl shadow-2xs space-y-space-lg">
            <header class="space-y-space-sm border-b border-surface-container pb-space-lg">
                <div class="inline-flex items-center gap-space-xs bg-surface-container px-space-sm py-space-xs rounded-full text-secondary font-label-sm text-label-sm font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[16px]">account_balance</span>
                    <span>Informasi Resmi Instansi</span>
                </div>

                <h1 class="font-headline-xl text-3xl sm:text-4xl text-primary font-bold tracking-tight leading-tight">
                    {{ $halaman->judul }}
                </h1>

                <div class="flex items-center gap-4 text-xs text-on-surface-variant font-medium pt-1">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">update</span>
                        Diperbarui: {{ $halaman->updated_at->translatedFormat('d F Y') }}
                    </span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                        {{ $siteSetting->institution_name ?? 'Pemerintah Instansi' }}
                    </span>
                </div>
            </header>

            @if($halaman->gambar_utama)
                <div class="rounded-xl overflow-hidden shadow-xs border border-surface-container max-h-[420px]">
                    <img src="{{ asset('storage/' . $halaman->gambar_utama) }}" alt="{{ $halaman->judul }}" class="w-full h-full object-cover" />
                </div>
            @endif

            @if($halaman->ringkasan)
                <div class="p-space-md sm:p-space-lg bg-surface-container-low rounded-xl border-l-4 border-secondary font-headline-md text-base sm:text-lg text-primary italic leading-relaxed">
                    {{ $halaman->ringkasan }}
                </div>
            @endif

            <!-- Body Text / Rich HTML -->
            <div class="prose prose-slate max-w-none text-on-surface leading-relaxed space-y-4 font-body-lg text-body-lg">
                {!! $halaman->isi !!}
            </div>
        </article>

        <!-- Sidebar Navigation to Other Pages -->
        <aside class="lg:col-span-4 space-y-space-md">
            @if(isset($halamanLain) && $halamanLain->isNotEmpty())
                <div class="bg-surface-container-lowest p-space-md rounded-2xl border border-surface-container shadow-2xs space-y-space-sm">
                    <h3 class="font-headline-md text-base font-bold text-primary flex items-center gap-2 border-b border-surface-container pb-2">
                        <span class="material-symbols-outlined text-[20px] text-secondary">menu_book</span>
                        <span>Menu Halaman Terkait</span>
                    </h3>
                    <ul class="space-y-1">
                        @foreach($halamanLain as $item)
                            <li>
                                <a href="{{ route('halaman.show', $item->slug) }}" class="flex items-center justify-between p-2 rounded-lg text-sm font-medium transition {{ $item->id === $halaman->id ? 'bg-primary text-on-primary font-bold' : 'text-on-surface hover:bg-surface-container-low' }}">
                                    <span>{{ $item->judul }}</span>
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Box Bantuan & PPID -->
            <div class="bg-primary text-on-primary p-space-lg rounded-2xl shadow-xs space-y-space-sm">
                <span class="material-symbols-outlined text-secondary-fixed text-[32px]">shield</span>
                <h4 class="font-headline-md text-lg font-bold text-on-primary">Layanan Keterbukaan Informasi</h4>
                <p class="text-xs text-on-primary-container leading-relaxed">
                    Perlu salinan dokumen atau klarifikasi lebih lanjut mengenai informasi di halaman ini? Hubungi Pejabat Pengelola Informasi dan Dokumentasi (PPID).
                </p>
                <div class="pt-2">
                    <a href="{{ route('publikasi') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-secondary text-on-secondary text-xs font-bold hover:brightness-105 transition">
                        <span>Daftar Informasi Publik</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
