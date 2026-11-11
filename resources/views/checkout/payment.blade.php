<x-layouts.app title="{{ __('Payment') }} - Sultaf">

    {{-- SCRIPT MIDTRANS SNAP (Wajib diletakkan sebelum form) --}}
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <div class="px-5 lg:px-0 pt-6 pb-6">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('checkout.details') }}" class="text-sultaf-ink hover:text-sultaf-maroon">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
                </a>
                <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Checkout') }}</h1>
            </div>
            @include('components.lang-switch')
        </div>

        <x-checkout-steps :current="3" />

        @if ($errors->any())
            <div class="mb-4 bg-sultaf-danger-soft rounded-xl px-4 py-3 text-sm text-sultaf-danger space-y-1">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- ================= KOLOM KIRI: FORM PEMBAYARAN ================= --}}
            <div class="flex-1 min-w-0">
                {{-- DIUBAH: Menghapus enctype karena tidak ada upload file --}}
                <form id="payment-form">
                    @csrf

                    <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-4">{{ __('Payment Method') }}</h2>

                    {{-- Opsi Cash & QRIS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="cash" class="peer hidden" {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }} required>
                            <div class="flex items-center gap-3 p-4 rounded-xl border border-sultaf-border bg-white font-semibold text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                <span class="text-xl">💵</span> {{ __('Cash') }}
                            </div>
                        </label>
                        
                        <label class="cursor-pointer">
                            <input type="radio" name="metode_pembayaran" value="qris" class="peer hidden" {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }} required>
                            <div class="flex items-center gap-3 p-4 rounded-xl border border-sultaf-border bg-white font-semibold text-sultaf-ink
                                        peer-checked:bg-sultaf-maroon peer-checked:text-white peer-checked:border-sultaf-maroon transition-colors">
                                <span class="text-xl">📱</span> {{ __('Non Tunai') }}
                            </div>
                        </label>
                    </div>

                    {{-- DIHAPUS: Bagian Upload Bukti Pembayaran --}}

                    {{-- Informasi Pelanggan --}}
                    <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-4">{{ __('Customer Information') }}</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Full Name') }}</label>
                            <input type="text" name="nama_pelanggan" value="{{ old('nama_pelanggan', auth()->user()->username ?? '') }}" required class="input-field">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Phone Number') }}</label>
                            <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_hp ?? '') }}" placeholder="+62 812 3456 789" required class="input-field">
                        </div>
                    </div>
                </form>
            </div>

            {{-- ================= KOLOM KANAN: RINGKASAN & TOMBOL ================= --}}
            <div class="w-full lg:w-80 xl:w-96 shrink-0 lg:sticky lg:top-24 space-y-4">
                
                {{-- Panel Ringkasan Harga --}}
                <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 text-white space-y-3">
                    <div class="flex justify-between items-center mb-2">
                        <p class="font-serif text-lg font-bold">{{ __('Order Summary') }}</p>
                        <span class="text-[10px] font-bold bg-white/20 px-2 py-1 rounded-full uppercase">{{ count(session('cart', [])) }} items</span>
                    </div>
                    
                    <div class="flex justify-between text-sm text-white/70">
                        <span>{{ __('Subtotal') }}</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-white/70">
                        <span>{{ __('Tax (10%)') }}</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-sm {{ $diskon > 0 ? 'text-emerald-300 font-semibold' : 'text-white/70' }}">
                        <span>{{ __('Potongan Harga') }}</span>
                        <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between text-sm text-sultaf-gold-light">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            {{ __('Poin Didapat') }}
                        </span>
                        <span>+{{ number_format($pointsEarned) }} Poin</span>
                    </div>

                    <div class="border-t border-white/20 pt-4 mt-4 flex justify-between items-center">
                        <span class="font-serif text-lg text-white/80">{{ __('Total Amount') }}</span>
                        <span class="font-serif text-2xl font-bold text-sultaf-gold-light">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- TOMBOL COMPLETE PAYMENT --}}
                <button type="button" id="pay-button" class="btn-primary w-full flex items-center justify-center gap-2">
                    {{ __('Complete Payment') }}
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </div>

        </div>
    </div>

    {{-- ===================== MODAL NOTIFIKASI CASH ===================== --}}
    <div id="cashModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl p-8 w-full max-w-sm mx-4 shadow-2xl text-center transform scale-95 transition-transform duration-300" id="cashModalContent">
            
            {{-- Ikon Uang --}}
            <div class="w-20 h-20 rounded-full bg-sultaf-cream border-4 border-white shadow-sm flex items-center justify-center mx-auto mb-5 relative z-10 -mt-14">
                <span class="text-4xl">💵</span>
            </div>

            <h3 class="font-serif text-2xl font-bold text-sultaf-ink mb-2">Pembayaran Tunai</h3>
            <p class="text-sm text-sultaf-muted mb-6 leading-relaxed">
                Pesanan Anda akan diproses. Silakan menuju ke meja kasir untuk melakukan pembayaran tunai.
            </p>

            <button type="button" id="btnConfirmCash" class="btn-primary w-full">
                Saya Mengerti & Lanjutkan
            </button>
        </div>
    </div>

    {{-- ===================== JAVASCRIPT LOGIC (AJAX & MIDTRANS) ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('payment-form');
            const payButton = document.getElementById('pay-button');
            const cashModal = document.getElementById('cashModal');
            const cashModalContent = document.getElementById('cashModalContent');
            const btnConfirmCash = document.getElementById('btnConfirmCash');

            function setLoading(isLoading) {
                if (isLoading) {
                    payButton.innerHTML = 'Memproses...';
                    payButton.disabled = true;
                } else {
                    payButton.innerHTML = '{{ __("Complete Payment") }} <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
                    payButton.disabled = false;
                }
            }

            function submitFormData() {
                const formData = new FormData(form);
                const csrfToken = document.querySelector('input[name="_token"]').value;

                setLoading(true);

                fetch("{{ route('checkout.payment.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function(result){
                                window.location.href = data.redirect_url;
                            },
                            onPending: function(result){
                                alert("Menunggu pembayaran Anda.");
                                window.location.href = "{{ route('menu.index') }}";
                            },
                            onError: function(result){
                                alert("Pembayaran gagal!");
                                setLoading(false);
                            },
                            onClose: function(){
                                setLoading(false);
                            }
                        });
                    } else if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Terjadi kesalahan sistem. Silakan cek F12.';
                    
                    if (error.errors) {
                        errorMessage = Object.values(error.errors).flat().join('\n');
                    } else if (error.message) {
                        errorMessage = error.message;
                    } else if (error.error) {
                        errorMessage = error.error;
                    }
                    
                    alert('Gagal: \n' + errorMessage);
                    setLoading(false);
                });
            }

            payButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity(); 
                    return;
                }

                const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;

                if (selectedMethod === 'cash') {
                    cashModal.classList.remove('hidden');
                    cashModal.classList.add('flex');
                    setTimeout(() => {
                        cashModalContent.classList.remove('scale-95');
                        cashModalContent.classList.add('scale-100');
                    }, 10);
                } else {
                    submitFormData();
                }
            });

            btnConfirmCash.addEventListener('click', function() {
                cashModalContent.classList.remove('scale-100');
                cashModalContent.classList.add('scale-95');
                cashModal.classList.remove('flex');
                cashModal.classList.add('hidden');
                
                submitFormData();
            });
        });
    </script>
</x-layouts.app>