<x-layouts.kasir title="POS - Kasir Sultaf" pageTitle="Point of Sale">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 -m-8 h-[calc(100vh-73px)]">

        {{-- ===== KIRI: Daftar Menu ===== --}}
        <div class="p-8 overflow-y-auto">
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex gap-2 mb-5 overflow-x-auto no-scrollbar pb-1">
                @foreach ($categories as $cat)
                    <a href="{{ route('kasir.pos', ['category' => $cat->slug]) }}"
                       class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                              {{ $activeCategory === $cat->slug
                                    ? 'bg-sultaf-maroon text-white'
                                    : 'bg-white text-gray-600 border border-gray-200' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse ($menus as $menu)
                    <form method="POST" action="{{ route('kasir.pos.add', $menu) }}">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="w-full text-left bg-white rounded-xl border border-gray-100 overflow-hidden hover:border-sultaf-maroon transition-colors group">
                            <div class="h-24 bg-sultaf-cream-dark relative">
                                @if ($menu->foto_makanan)
                                    <img src="{{ asset('storage/'.$menu->foto_makanan) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl">🍽️</div>
                                @endif
                                <span class="absolute top-1.5 right-1.5 bg-white/95 text-[10px] font-bold text-sultaf-maroon px-1.5 py-0.5 rounded">
                                    Rp {{ number_format($menu->harga_makanan / 1000, 0) }}k
                                </span>
                            </div>
                            <div class="p-2.5">
                                <p class="text-sm font-semibold text-gray-800 leading-tight group-hover:text-sultaf-maroon">
                                    {{ $menu->nama_makanan }}
                                </p>
                            </div>
                        </button>
                    </form>
                @empty
                    <p class="col-span-full text-center text-gray-400 py-10">Tidak ada menu tersedia di kategori ini.</p>
                @endforelse
            </div>
        </div>

        {{-- ===== KANAN: Keranjang / Current Order ===== --}}
        <div class="bg-white border-l border-gray-100 p-6 flex flex-col overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-serif text-lg font-bold text-gray-800">Current Order</h3>
                @if ($cartItems->isNotEmpty())
                    <form method="POST" action="{{ route('kasir.pos.clear') }}">
                        @csrf
                        <button class="text-xs text-gray-400 hover:text-red-500">Clear</button>
                    </form>
                @endif
            </div>

            <div class="flex-1 space-y-3 mb-4">
                @forelse ($cartItems as $item)
                    <div class="flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $item['menu']->nama_makanan }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format($item['menu']->harga_makanan, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <form method="POST" action="{{ route('kasir.pos.decrease', $item['menu']) }}">
                                @csrf
                                <button class="w-6 h-6 rounded-full border border-gray-200 text-gray-600 text-sm hover:border-sultaf-maroon">−</button>
                            </form>
                            <span class="text-sm font-semibold w-5 text-center">{{ $item['qty'] }}</span>
                            <form method="POST" action="{{ route('kasir.pos.add', $item['menu']) }}">
                                @csrf
                                <input type="hidden" name="qty" value="1">
                                <button class="w-6 h-6 rounded-full bg-sultaf-maroon text-white text-sm hover:bg-sultaf-maroon-dark">+</button>
                            </form>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 w-20 text-right shrink-0">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-300 text-sm py-16">Belum ada item.<br>Pilih menu di sebelah kiri.</p>
                @endforelse
            </div>

            @if ($cartItems->isNotEmpty())
                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm mb-4">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Tax (10%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="font-serif text-xl font-bold text-sultaf-maroon">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('kasir.pos.checkout') }}" class="space-y-3">
                    @csrf

                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="dine_in" class="peer hidden" checked
                                   onchange="document.getElementById('mejaField').classList.remove('hidden')">
                            <div class="text-center text-xs font-semibold py-2 rounded-lg border border-gray-200 text-gray-600
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon">
                                Dine-In
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="takeaway" class="peer hidden"
                                   onchange="document.getElementById('mejaField').classList.add('hidden')">
                            <div class="text-center text-xs font-semibold py-2 rounded-lg border border-gray-200 text-gray-600
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon">
                                Takeaway
                            </div>
                        </label>
                    </div>

                    <div id="mejaField">
                        <select name="nomor_meja" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-sultaf-maroon">
                            <option value="" disabled selected>Pilih meja...</option>
                            @for ($n = 1; $n <= 20; $n++)
                                <option value="{{ $n }}">Meja {{ $n }}</option>
                            @endfor
                        </select>
                    </div>

                    <input type="text" name="nama_pelanggan" placeholder="Nama pelanggan (opsional)"
                           class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-sultaf-maroon">

                    {{-- Nomor HP — dipakai sistem untuk cek & tambah poin kalau terdaftar sbg member --}}
                    <div>
                        <input type="text" name="no_telepon" placeholder="No. HP pelanggan (cek member & poin)"
                               class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-sultaf-maroon">
                        <p class="text-[11px] text-gray-400 mt-1">
                            Isi nomor HP untuk memeriksa apakah pelanggan terdaftar sebagai member.
                            Kalau cocok, poin akan otomatis ditambahkan.
                        </p>
                    </div>

                    <select name="metode_pembayaran" required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:border-sultaf-maroon">
                        <option value="" disabled selected>Metode pembayaran...</option>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="gopay">GoPay</option>
                        <option value="ovo">OVO</option>
                        <option value="dana">DANA</option>
                        <option value="card">Kartu Debit/Kredit</option>
                    </select>

                    <button type="submit"
                            class="w-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl py-3.5 transition-colors">
                        Process Payment
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-layouts.kasir>
