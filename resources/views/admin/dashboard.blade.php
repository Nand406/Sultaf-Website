<x-layouts.admin title="Dashboard - Admin Sultaf" pageTitle="Dashboard Admin">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Total Menu</p>
            <p class="font-serif text-3xl font-bold text-gray-800">{{ $stats['total_menu'] }}</p>
            @if ($stats['menu_habis'] > 0)
                <p class="text-xs text-red-500 mt-1">{{ $stats['menu_habis'] }} menu berstatus habis</p>
            @endif
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Total Member</p>
            <p class="font-serif text-3xl font-bold text-sultaf-maroon">{{ $stats['total_member'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Promosi Aktif</p>
            <p class="font-serif text-3xl font-bold text-amber-600">{{ $stats['promosi_aktif'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Pesan Promo Terkirim</p>
            <p class="font-serif text-3xl font-bold text-blue-600">{{ $stats['pesan_terkirim'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.menu.create') }}" class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white rounded-2xl p-5 flex items-center gap-4 transition-colors">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0 text-xl font-light">+</div>
            <div>
                <p class="font-semibold">Tambah Menu</p>
                <p class="text-xs text-white/70">Buat item menu baru</p>
            </div>
        </a>
        <a href="{{ route('admin.benefit.create') }}" class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-sultaf-maroon transition-colors">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Buat Promosi</p>
                <p class="text-xs text-gray-400">Atur benefit member baru</p>
            </div>
        </a>
        <a href="{{ route('admin.promo.index') }}" class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 hover:border-sultaf-maroon transition-colors">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Kirim Pesan Promo</p>
                <p class="text-xs text-gray-400">Broadcast ke member</p>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Menu terlaris --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Menu Terlaris</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($menuTerlaris as $item)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ $item['menu']->nama_makanan ?? '-' }}</span>
                        <span class="text-sm font-semibold text-sultaf-maroon">{{ $item['qty'] }} terjual</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada data penjualan.</p>
                @endforelse
            </div>
        </div>

        {{-- Pesan promo terakhir --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Pesan Promo Terakhir</h3>
                <a href="{{ route('admin.promo.index') }}" class="text-xs text-sultaf-maroon font-medium">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($pesanTerakhir as $pesan)
                    <div class="px-5 py-3">
                        <p class="text-sm font-medium text-gray-800">{{ $pesan->judul }}</p>
                        <p class="text-xs text-gray-400">{{ $pesan->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada pesan terkirim.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
