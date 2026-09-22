<x-layouts.admin title="Dashboard - Admin Sultaf" pageTitle="Dashboard Admin">

    <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 divide-x divide-white/10">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Total Menu</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['total_menu'] }}</p>
                @if ($stats['menu_habis'] > 0)
                    <p class="text-xs text-red-300 mt-1">{{ $stats['menu_habis'] }} berstatus habis</p>
                @endif
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Total Member</p>
                <p class="font-serif text-3xl font-bold text-sultaf-gold-light">{{ $stats['total_member'] }}</p>
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Promosi Aktif</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['promosi_aktif'] }}</p>
            </div>
            <div class="pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Pesan Terkirim</p>
                <p class="font-serif text-3xl font-bold">{{ $stats['pesan_terkirim'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.menu.create') }}" class="staff-card p-5 flex items-center gap-4 !bg-sultaf-maroon hover:!bg-sultaf-maroon-dark transition-colors">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0 text-xl font-light text-white">+</div>
            <div>
                <p class="font-semibold text-white">Tambah Menu</p>
                <p class="text-xs text-white/70">Buat item menu baru</p>
            </div>
        </a>
        <a href="{{ route('admin.benefit.create') }}" class="staff-card p-5 flex items-center gap-4 hover:border-sultaf-maroon/40 transition-colors">
            <div class="w-11 h-11 rounded-xl bg-sultaf-warning-soft flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-sultaf-maroon-dark" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sultaf-ink">Buat Promosi</p>
                <p class="text-xs text-sultaf-muted">Atur benefit member baru</p>
            </div>
        </a>
        <a href="{{ route('admin.promo.index') }}" class="staff-card p-5 flex items-center gap-4 hover:border-sultaf-maroon/40 transition-colors">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-sultaf-ink">Kirim Pesan Promo</p>
                <p class="text-xs text-sultaf-muted">Broadcast ke member</p>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">Menu Terlaris</h3>
            </div>
            <div class="divide-y divide-sultaf-border">
                @forelse ($menuTerlaris as $item)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-sm text-sultaf-ink">{{ $item['menu']->nama_makanan ?? '-' }}</span>
                        <span class="text-sm font-semibold text-sultaf-maroon">{{ $item['qty'] }} terjual</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sultaf-muted text-sm">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>

        <div class="staff-card overflow-hidden">
            <div class="px-5 py-4 border-b border-sultaf-border flex items-center justify-between">
                <h3 class="font-semibold text-sultaf-ink">Pesan Promo Terakhir</h3>
                <a href="{{ route('admin.promo.index') }}" class="text-xs text-sultaf-maroon font-medium">Lihat semua →</a>
            </div>
            <div class="divide-y divide-sultaf-border">
                @forelse ($pesanTerakhir as $pesan)
                    <div class="px-5 py-3">
                        <p class="text-sm font-medium text-sultaf-ink">{{ $pesan->judul }}</p>
                        <p class="text-xs text-sultaf-muted">{{ $pesan->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sultaf-muted text-sm">Belum ada pesan terkirim.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
