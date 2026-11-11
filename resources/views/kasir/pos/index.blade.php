<x-layouts.kasir title="POS - Kasir Sultaf" pageTitle="Pesanan Offline">

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 -m-8 h-[calc(100vh-89px)]">

        {{-- ===== KIRI: Daftar Menu ===== --}}
        <div class="p-8 overflow-y-auto">
            @if (session('error'))
                <div class="mb-4 bg-sultaf-danger-soft border border-sultaf-danger/20 text-sultaf-danger text-sm rounded-xl px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            {{-- FILTER KATEGORI --}}
            <div class="flex gap-2 mb-5 overflow-x-auto no-scrollbar pb-1">
                <a href="{{ route('kasir.pos') }}"
                   class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                          {{ !request('category') ? 'bg-sultaf-maroon text-white' : 'bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon/40' }}">
                    Semua
                </a>
                <a href="{{ route('kasir.pos', ['category' => 'makanan']) }}"
                   class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                          {{ request('category') === 'makanan' ? 'bg-sultaf-maroon text-white' : 'bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon/40' }}">
                    Makanan
                </a>
                <a href="{{ route('kasir.pos', ['category' => 'minuman']) }}"
                   class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                          {{ request('category') === 'minuman' ? 'bg-sultaf-maroon text-white' : 'bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon/40' }}">
                    Minuman
                </a>
                <a href="{{ route('kasir.pos', ['category' => 'camilan']) }}"
                   class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                          {{ request('category') === 'camilan' ? 'bg-sultaf-maroon text-white' : 'bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon/40' }}">
                    Camilan
                </a>
            </div>

            {{-- GRID MENU --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @forelse ($menus as $menu)
                    <form method="POST" action="{{ route('kasir.pos.add', $menu) }}">
                        @csrf
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="w-full text-left bg-white rounded-xl overflow-hidden border border-sultaf-border hover:border-sultaf-maroon/40 hover:shadow-md transition-all group flex flex-col h-full">
                            
                            {{-- GAMBAR MENU --}}
                            <div class="h-32 bg-sultaf-cream relative w-full overflow-hidden shrink-0">
                                @if ($menu->foto_menu)
                                    <img src="{{ asset($menu->foto_menu) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl">🍽️</div>
                                @endif
                                <span class="absolute top-2 right-2 bg-white/95 text-[10px] font-bold text-sultaf-maroon px-2 py-0.5 rounded shadow-sm">
                                    Rp {{ number_format($menu->harga / 1000, 0) }}k
                                </span>
                            </div>
                            
                            {{-- INFO MENU --}}
                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <p class="text-sm font-semibold text-sultaf-ink leading-tight group-hover:text-sultaf-maroon line-clamp-2">
                                    {{ $menu->nama_menu }}
                                </p>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-[10px] uppercase font-bold tracking-wide text-sultaf-muted">
                                        {{ $menu->kategori ?? 'Menu' }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <p class="col-span-full text-center text-sultaf-muted py-10">Tidak ada menu tersedia di kategori ini.</p>
                @endforelse
            </div>
        </div>

        {{-- ===== KANAN: Keranjang / Current Order ===== --}}
        <div class="bg-white border-l border-sultaf-border p-6 flex flex-col overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-serif text-lg font-bold text-sultaf-ink">Current Order</h3>
                @if ($cartItems->isNotEmpty())
                    <form method="POST" action="{{ route('kasir.pos.clear') }}">
                        @csrf
                        <button class="text-xs text-sultaf-muted hover:text-sultaf-danger">Clear</button>
                    </form>
                @endif
            </div>

            <div class="flex-1 space-y-3 mb-4">
                @forelse ($cartItems as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-sultaf-cream overflow-hidden shrink-0 border border-sultaf-border">
                            @if ($item['menu']->foto_menu)
                                <img src="{{ asset($item['menu']->foto_menu) }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-sultaf-ink truncate">{{ $item['menu']->nama_menu }}</p>
                            <p class="text-xs text-sultaf-muted">Rp {{ number_format($item['menu']->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <form method="POST" action="{{ route('kasir.pos.decrease', $item['menu']) }}">
                                @csrf
                                <button class="w-6 h-6 rounded-full border border-sultaf-border text-sultaf-ink text-sm hover:border-sultaf-maroon flex items-center justify-center">−</button>
                            </form>
                            <span class="text-sm font-semibold w-5 text-center">{{ $item['qty'] }}</span>
                            <form method="POST" action="{{ route('kasir.pos.add', $item['menu']) }}">
                                @csrf
                                <input type="hidden" name="qty" value="1">
                                <button class="w-6 h-6 rounded-full bg-sultaf-maroon text-white text-sm hover:bg-sultaf-maroon-dark flex items-center justify-center">+</button>
                            </form>
                        </div>
                        <span class="text-sm font-semibold text-sultaf-ink w-20 text-right shrink-0">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-sultaf-muted/60 text-sm py-16">Belum ada item.<br>Pilih menu di sebelah kiri.</p>
                @endforelse
            </div>

            @if ($cartItems->isNotEmpty())
                <div class="border-t border-sultaf-border pt-4 space-y-2 text-sm mb-4">
                    <div class="flex justify-between text-sultaf-muted">
                        <span>Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sultaf-muted">
                        <span>Tax (10%)</span><span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- BARIS DISKON (Awalnya disembunyikan) --}}
                    <div class="flex justify-between text-sultaf-success font-semibold" id="diskonRow" style="display: none;">
                        <span id="promoName">Diskon Member</span>
                        <span id="diskonAmount">-Rp 0</span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-sultaf-border">
                        <span class="font-semibold text-sultaf-ink">Total</span>
                        {{-- ID="totalAmount" ditambahkan agar bisa diubah JS --}}
                        <span class="font-serif text-xl font-bold text-sultaf-maroon" id="totalAmount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('kasir.pos.checkout') }}" class="space-y-3" id="checkoutForm">
                    @csrf

                    {{-- TIPE PESANAN --}}
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="dine_in" class="peer hidden" checked
                                   onchange="document.getElementById('mejaField').classList.remove('hidden')">
                            <div class="text-center text-xs font-semibold py-2 rounded-lg border border-sultaf-border text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                Dine-In
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="tipe_pesanan" value="takeaway" class="peer hidden"
                                   onchange="document.getElementById('mejaField').classList.add('hidden')">
                            <div class="text-center text-xs font-semibold py-2 rounded-lg border border-sultaf-border text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                Takeaway
                            </div>
                        </label>
                    </div>

                    <div id="mejaField">
                        <select name="nomor_meja" class="w-full text-sm border border-sultaf-border rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-sultaf-maroon">
                            <option value="" disabled selected>Pilih meja...</option>
                            @for ($n = 1; $n <= 20; $n++)
                                <option value="{{ $n }}">Meja {{ $n }}</option>
                            @endfor
                        </select>
                    </div>

                    <input type="text" name="nama_pelanggan" placeholder="Nama pelanggan (opsional)"
                           class="w-full text-sm border border-sultaf-border rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-sultaf-maroon">

                    {{-- INPUT NO HP DENGAN ID UNTUK AJAX --}}
                    <div>
                        <input type="text" name="no_telepon" id="inputNoTelepon" placeholder="No. HP pelanggan (cek member & poin)"
                               class="w-full text-sm border border-sultaf-border rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-sultaf-maroon">
                        <p class="text-[11px] text-sultaf-muted mt-1" id="memberInfo">
                            Isi nomor HP untuk memeriksa promo.
                        </p>
                    </div>

                    {{-- METODE PEMBAYARAN --}}
                    <div class="pt-2 border-t border-sultaf-border mt-4">
                        <label class="block text-sm font-semibold text-sultaf-ink mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="cash" class="peer hidden" checked onchange="togglePaymentMethod('cash')">
                                <div class="text-center text-xs font-semibold py-2 rounded-lg border border-sultaf-border text-sultaf-ink
                                            peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                    💵 Cash
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" value="qris" class="peer hidden" onchange="togglePaymentMethod('qris')">
                                <div class="text-center text-xs font-semibold py-2 rounded-lg border border-sultaf-border text-sultaf-ink
                                            peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                    📱 Non Tunai
                                </div>
                            </label>
                        </div>

                        {{-- SECTION CASH --}}
                        <div id="cashSection" class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-sultaf-ink mb-1">Uang Diterima (Rp)</label>
                                <input type="number" name="uang_bayar" id="inputUangBayar" min="0"
                                       class="w-full text-sm border border-sultaf-border rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-sultaf-maroon"
                                       placeholder="Masukkan nominal...">
                            </div>
                            <div class="bg-sultaf-success-soft border border-sultaf-success/20 rounded-lg p-3 flex justify-between items-center">
                                <span class="text-xs font-semibold text-sultaf-success">Kembalian</span>
                                <span id="textKembalian" class="font-bold text-sultaf-success text-sm">Rp 0</span>
                            </div>
                        </div>

                        {{-- SECTION QRIS --}}
                        <div id="qrisSection" class="hidden space-y-3">
                            <div class="bg-sultaf-cream rounded-lg p-3 text-center">
                                <p class="text-[11px] text-sultaf-muted uppercase">Total Tagihan (QRIS)</p>
                                {{-- ID="qrisTotalAmount" ditambahkan agar bisa diubah JS --}}
                                <p class="font-serif text-lg font-bold text-sultaf-maroon" id="qrisTotalAmount">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold rounded-xl py-3.5 transition-colors mt-4">
                        Process Payment
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- ===================== JAVASCRIPT LOGIC ===================== --}}
    <script>
        // Toggle pembayaran Cash / QRIS
        function togglePaymentMethod(method) {
            const cashSection = document.getElementById('cashSection');
            const qrisSection = document.getElementById('qrisSection');

            if (method === 'cash') {
                cashSection.classList.remove('hidden');
                qrisSection.classList.add('hidden');
            } else {
                cashSection.classList.add('hidden');
                qrisSection.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Data Awal dari PHP
            const subtotal = {{ $subtotal }};
            const tax = {{ $tax }};
            let currentTotal = {{ $total }}; // Total bisa berubah jika ada diskon

            // DOM Elements
            const inputNoTelp = document.getElementById('inputNoTelepon');
            const memberInfo = document.getElementById('memberInfo');
            const diskonRow = document.getElementById('diskonRow');
            const diskonAmount = document.getElementById('diskonAmount');
            const promoName = document.getElementById('promoName');
            const totalAmountText = document.getElementById('totalAmount');
            const qrisTotalAmountText = document.getElementById('qrisTotalAmount');
            const inputUang = document.getElementById('inputUangBayar');
            const textKembalian = document.getElementById('textKembalian');

            // Helper Format Rupiah
            const formatRp = (angka) => {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
            };

            // 1. LOGIKA KALKULATOR KEMBALIAN
            const updateKembalian = () => {
                if (!inputUang) return;
                let uang = parseFloat(inputUang.value) || 0;
                let kembalian = uang - currentTotal;

                if (kembalian >= 0) {
                    textKembalian.innerText = formatRp(kembalian);
                    textKembalian.classList.remove('text-sultaf-danger');
                    textKembalian.classList.add('text-sultaf-success');
                } else {
                    textKembalian.innerText = 'Uang Kurang!';
                    textKembalian.classList.remove('text-sultaf-success');
                    textKembalian.classList.add('text-sultaf-danger');
                }
            };

            if (inputUang) {
                inputUang.addEventListener('input', updateKembalian);
            }

            // 2. LOGIKA CEK MEMBER OTOMATIS (AJAX)
            inputNoTelp.addEventListener('blur', function() {
                let noTelp = this.value;
                
                if (noTelp.length < 8) {
                    resetDiskon();
                    return;
                }

                memberInfo.innerText = 'Mengecek member...';
                memberInfo.classList.remove('text-sultaf-success', 'text-sultaf-danger');
                memberInfo.classList.add('text-sultaf-muted');

                // Panggil API Route
                fetch("{{ route('kasir.pos.check-member') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ no_telepon: noTelp })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.diskon > 0) {
                        // Jika ada diskon, tampilkan & potong total
                        diskonRow.style.display = 'flex';
                        diskonAmount.innerText = '-' + formatRp(data.diskon);
                        promoName.innerText = 'Diskon';
                        
                        currentTotal = (subtotal + tax) - data.diskon;
                        if (currentTotal < 0) currentTotal = 0;
                        
                        totalAmountText.innerText = formatRp(currentTotal);
                        if(qrisTotalAmountText) qrisTotalAmountText.innerText = formatRp(currentTotal);
                        
                        memberInfo.innerText = `Member: ${data.nama_pelanggan} (Poin: ${data.total_poin})`;
                        memberInfo.classList.remove('text-sultaf-muted');
                        memberInfo.classList.add('text-sultaf-success', 'font-semibold');
                        
                        updateKembalian(); // Recalculate change
                    } else {
                        // Jika tidak ada diskon (tapi mungkin terdaftar sebagai member)
                        resetDiskon();
                        if(data.total_poin > 0) {
                            memberInfo.innerText = `Member: ${data.nama_pelanggan} (Poin: ${data.total_poin}), belum ada promo.`;
                            memberInfo.classList.remove('text-sultaf-muted');
                            memberInfo.classList.add('text-sultaf-ink');
                        } else if (data.nama_pelanggan) {
                             memberInfo.innerText = `Member: ${data.nama_pelanggan}, poin belum cukup.`;
                             memberInfo.classList.remove('text-sultaf-muted');
                             memberInfo.classList.add('text-sultaf-ink');
                        } else {
                            memberInfo.innerText = 'Nomor HP tidak terdaftar sebagai member.';
                            memberInfo.classList.add('text-sultaf-muted');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resetDiskon();
                    memberInfo.innerText = 'Gagal mengecek member.';
                    memberInfo.classList.add('text-sultaf-danger');
                });
            });

            // Fungsi Reset Diskon jika No HP kosong/dihapus
            function resetDiskon() {
                diskonRow.style.display = 'none';
                currentTotal = subtotal + tax;
                totalAmountText.innerText = formatRp(currentTotal);
                if(qrisTotalAmountText) qrisTotalAmountText.innerText = formatRp(currentTotal);
                memberInfo.innerText = 'Isi nomor HP untuk memeriksa promo.';
                memberInfo.classList.remove('text-sultaf-success', 'text-sultaf-ink', 'font-semibold');
                memberInfo.classList.add('text-sultaf-muted');
                updateKembalian();
            }
        });
    </script>
</x-layouts.kasir>