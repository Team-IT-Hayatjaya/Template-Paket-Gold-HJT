@php
    $package = config('packages.active', config('packages.default', 'gold'));
    $isGold = strtolower($package) === 'gold';
    $isPlatinum = strtolower($package) === 'platinum';
@endphp

<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-sm border
    {{ $isGold ? 'bg-amber-500/15 text-amber-800 dark:text-amber-300 border-amber-500/40' : ($isPlatinum ? 'bg-purple-500/15 text-purple-800 dark:text-purple-300 border-purple-500/40' : 'bg-slate-500/15 text-slate-800 dark:text-slate-300 border-slate-500/40') }}">
    <span class="inline-block w-2 h-2 rounded-full {{ $isGold ? 'bg-amber-500 animate-pulse' : ($isPlatinum ? 'bg-purple-500' : 'bg-slate-400') }}"></span>
    <span>Paket {{ strtoupper($package) }}</span>
</div>
