<x-layouts.kasir title="Verifikasi Pembayaran - Kasir" pageTitle="Order Verification">

    @if (session('success'))
        <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- ===================== HERO BAND ===================== --}}
    <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-white/10">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Waiting</p>
                <p class="font-serif text-3xl font-bold text-sultaf-gold-light">{{ str_pad($menunggu->count(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6">
                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">Total Value</p>
                <p class="font-serif text-3xl font-bold">Rp {{ number_format($menunggu->sum('total_bayar'), 0, ',', '.') }}</p>
            </div>
            <div class="pt-6 sm:pt-0 sm:pl-6 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-300"></span>
                <span class="text-sm font-semibold text-emerald-300">{{ $terverifikasiHariIni }} verified today</span>
            </div>
        </div>
    </div>

    {{-- ===================== DAFTAR TRANSAKSI ===================== --}}
    <div class="space-y-4">
        @forelse ($menunggu as $transaksi)
            <div class="staff-card overflow-hidden">
                <div class="p-5 flex flex-col lg:flex-row lg:items-start gap-5">
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="font-semibold text-sultaf-ink">
                                {{ $transaksi->nama_pelanggan ?? $transaksi->user->username ?? 'Pelanggan' }}
                            </h3>
                            @if ($transaksi->no_meja)
                                <span class="text-xs font-semibold bg-sultaf-maroon/10 text-sultaf-maroon px-2 py-0.5 rounded-full">
                                    Table {{ str_pad($transaksi->no_meja, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @else
                                <span class="text-xs font-semibold bg-sultaf-cream text-sultaf-muted px-2 py-0.5 rounded-full">Takeaway</span>
                            @endif
                            <span class="text-xs font-semibold uppercase bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $transaksi->metode_pembayaran }}
                            </span>
                        </div>

                        <p class="text-sm text-sultaf-muted mb-3">
                            Order:
                            {{ $transaksi->detail_transaksi->map(fn ($i) => "{$i->jumlah}x {$i->menu->nama_menu}")->join(', ') }}
                        </p>

                        <div class="flex items-center gap-4 text-sm">
                            <span class="font-serif text-xl font-bold text-sultaf-maroon">
                                Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                            </span>
                            <span class="text-sultaf-border">•</span>
                            <span class="text-sultaf-muted">{{ $transaksi->created_at->diffForHumans() }}</span>
                            <span class="text-sultaf-border">•</span>
                            <span class="text-sultaf-muted">#SLT-{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>

                    <div class="flex lg:flex-col gap-2 shrink-0 w-full lg:w-44">
                        {{-- TOMBOL DIUBAH: Mengirim daftar menu ke Modal menggunakan addslashes agar aman dari tanda kutip --}}
                        <button type="button" 
                                onclick="openPaymentModal(
                                    {{ $transaksi->id_transaksi }}, 
                                    {{ $transaksi->total_bayar }}, 
                                    '{{ addslashes($transaksi->nama_pelanggan ?? $transaksi->user->username ?? 'Pelanggan') }}',
                                    '{{ addslashes($transaksi->detail_transaksi->map(fn ($i) => "{$i->jumlah}x {$i->menu->nama_menu}")->join(', ')) }}'
                                )"
                                class="w-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl py-2.5 flex items-center justify-center gap-1.5 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                            Verify & Send
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="staff-card p-16 text-center text-sultaf-muted">
                <p class="text-4xl mb-3">✓</p>
                <p>Tidak ada pembayaran yang menunggu verifikasi.</p>
            </div>
        @endforelse
    </div>

    {{-- ===================== MODAL PEMBAYARAN ===================== --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md mx-4 shadow-2xl transform scale-95 transition-transform duration-300" id="paymentModalContent">
            
            <div class="flex justify-between items-center mb-4 border-b border-sultaf-border pb-3">
                <h3 class="font-serif text-xl font-bold text-sultaf-ink">Konfirmasi Pembayaran</h3>
                <button onclick="closePaymentModal()" class="text-sultaf-muted hover:text-sultaf-danger">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="paymentForm" method="POST" action="">
                @csrf
                
                <div class="mb-3">
                    <p class="text-sm text-sultaf-muted">Pelanggan</p>
                    <p id="modalCustomerName" class="font-semibold text-sultaf-ink"></p>
                </div>

                {{-- TAMBAHAN: Rincian Pesanan --}}
                <div class="mb-4 bg-sultaf-cream/50 p-3 rounded-lg border border-sultaf-border">
                    <p class="text-xs text-sultaf-muted mb-1">Pesanan:</p>
                    <p id="modalOrderItems" class="text-sm font-medium text-sultaf-ink leading-relaxed"></p>
                </div>

                <div class="bg-sultaf-cream rounded-xl p-4 mb-4 flex justify-between items-center">
                    <span class="text-sm font-semibold text-sultaf-ink">Total Tagihan</span>
                    <span id="modalTotalBill" class="font-serif text-2xl font-bold text-sultaf-maroon"></span>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">Uang Diterima (Rp)</label>
                    <input type="number" name="uang_bayar" id="inputUangBayar" required min="0"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-3 text-lg font-bold text-sultaf-ink focus:outline-none focus:border-sultaf-maroon"
                           placeholder="Masukkan nominal uang...">
                </div>

                <div class="bg-sultaf-success-soft border border-sultaf-success/20 rounded-xl p-4 mb-6 flex justify-between items-center">
                    <span class="text-sm font-semibold text-sultaf-success">Kembalian</span>
                    <span id="modalKembalian" class="font-serif text-2xl font-bold text-sultaf-success">Rp 0</span>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="closePaymentModal()" class="flex-1 py-3 rounded-xl border border-sultaf-border text-sultaf-ink font-semibold hover:bg-sultaf-cream">Batal</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-sultaf-maroon text-white font-semibold hover:bg-sultaf-maroon-dark">Konfirmasi & Cetak</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== JAVASCRIPT MODAL & KEMBALIAN ===================== --}}
    <script>
        const modal = document.getElementById('paymentModal');
        const modalContent = document.getElementById('paymentModalContent');
        const form = document.getElementById('paymentForm');
        const inputUang = document.getElementById('inputUangBayar');
        const textTotal = document.getElementById('modalTotalBill');
        const textKembalian = document.getElementById('modalKembalian');
        
        let currentTotal = 0;

        // TAMBAHAN parameter itemList
        function openPaymentModal(idTransaksi, total, namaPelanggan, itemList) {
            currentTotal = total;
            
            // Set data ke dalam modal
            document.getElementById('modalCustomerName').innerText = namaPelanggan;
            document.getElementById('modalOrderItems').innerText = itemList; // Tampilkan list menu
            textTotal.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            
            // Reset inputan
            inputUang.value = '';
            textKembalian.innerText = 'Rp 0';
            
            // Set Action URL Form menuju route verify
            form.action = `/kasir/pembayaran/${idTransaksi}/verify`;

            // Tampilkan Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            // Fokus otomatis ke inputan uang
            setTimeout(() => inputUang.focus(), 300);
        }

        function closePaymentModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        // Hitung Kembalian secara otomatis saat kasir mengetik
        inputUang.addEventListener('input', function() {
            let uangBayar = parseFloat(this.value) || 0;
            let kembalian = uangBayar - currentTotal;

            if (kembalian >= 0) {
                textKembalian.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
                textKembalian.classList.remove('text-sultaf-danger');
                textKembalian.classList.add('text-sultaf-success');
            } else {
                textKembalian.innerText = 'Uang Kurang!';
                textKembalian.classList.remove('text-sultaf-success');
                textKembalian.classList.add('text-sultaf-danger');
            }
        });
    </script>
</x-layouts.kasir>