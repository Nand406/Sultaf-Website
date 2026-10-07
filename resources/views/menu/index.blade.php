<x-layouts.app title="{{ __('Menu') }} - Sultaf">

    {{-- ===================== MODAL ORDER ===================== --}}
    <div id="orderModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>

        <div class="absolute bottom-0 left-0 right-0 lg:relative lg:flex lg:items-center lg:justify-center lg:min-h-screen">
            <div class="bg-white rounded-t-3xl lg:rounded-2xl w-full lg:max-w-md p-6 shadow-xl">

                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 pr-4">
                        <p class="text-xs text-sultaf-muted uppercase tracking-wide mb-1">{{ __('Add to Order') }}</p>
                        <h3 id="modalMenuName" class="font-serif text-xl font-bold text-sultaf-ink"></h3>
                        <p id="modalMenuDesc" class="text-sm text-sultaf-muted mt-1"></p>
                    </div>
                    <button onclick="closeModal()"
                            class="w-9 h-9 rounded-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p id="modalMenuPrice" class="font-serif text-2xl font-bold text-sultaf-maroon mb-5"></p>

                <div class="flex items-center justify-center gap-6 mb-6">
                    <button onclick="decreaseQty()"
                            class="w-12 h-12 rounded-full border-2 border-sultaf-border flex items-center justify-center text-sultaf-ink text-2xl font-light hover:border-sultaf-maroon hover:text-sultaf-maroon transition-colors">
                        −
                    </button>
                    <span id="qtyDisplay" class="font-serif text-3xl font-bold text-sultaf-ink w-10 text-center">1</span>
                    <button onclick="increaseQty()"
                            class="w-12 h-12 rounded-full border-2 border-sultaf-maroon bg-sultaf-maroon flex items-center justify-center text-white text-2xl font-light hover:bg-sultaf-maroon-dark transition-colors">
                        +
                    </button>
                </div>

                <div class="flex justify-between items-center bg-sultaf-cream rounded-xl px-4 py-3 mb-6">
                    <span class="text-sm text-sultaf-muted">{{ __('Subtotal') }}</span>
                    <span id="modalSubtotal" class="font-semibold text-sultaf-ink"></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeModal()"
                            class="py-3.5 rounded-xl border border-sultaf-border font-semibold text-sultaf-ink hover:bg-sultaf-cream transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <form id="modalForm" method="POST">
                        @csrf
                        {{-- Input tersembunyi untuk mengirimkan id_menu yang dipilih --}}
                        <input type="hidden" name="id_menu" id="modalMenuId" value="">
                        
                        <input type="hidden" name="qty" id="modalQtyInput" value="1">
                        <button type="submit"
                                class="w-full py-3.5 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-semibold transition-colors">
                            {{ __('Add to Cart') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== HERO BANNER ===================== --}}
    <div class="px-5 lg:px-0 mb-6">
        <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl px-6 py-8 lg:px-10 lg:py-12 text-white relative overflow-hidden">
            <div class="relative z-10 max-w-xl">
                <p class="text-[11px] uppercase tracking-[0.2em] text-sultaf-gold-light mb-3">
                    {{ __('Indonesian Gastronomy') }} <em class="italic">{{ __('Refined') }}</em>
                </p>
                <h1 class="font-serif text-3xl lg:text-4xl font-bold leading-tight mb-3">
                    {{ __('Prepared with heritage, served with heart.') }}
                </h1>
                <p class="text-sm text-white/60">Sultaf Yogyakarta Main Branch</p>
            </div>
        </div>
    </div>

    {{-- ===================== DAFTAR MENU ===================== --}}
    <div class="px-5 lg:px-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-6">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-2xl overflow-hidden border border-sultaf-border/70 shadow-sm flex flex-col {{ $menu->status_ketersediaan == 'Habis' ? 'grayscale opacity-75' : 'hover:shadow-md transition-shadow' }}">
                <div class="relative h-44">
                    @if ($menu->foto_menu)
                        <img src="{{ asset('storage/'.$menu->foto_menu) }}" alt="{{ __($menu->nama_menu) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted text-sm">
                            {{ __('No photo') }}
                        </div>
                    @endif

                    @if ($menu->status_ketersediaan == 'Habis')
                        <div class="absolute inset-0 bg-black/55 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm bg-black/50 border border-white/30 px-4 py-2 rounded-full text-center">
                                {{ __('Menu Unavailable') }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-serif text-lg font-semibold text-sultaf-ink leading-snug">{{ __($menu->nama_menu) }}</h3>

                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <p class="font-serif text-lg font-bold text-sultaf-maroon">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>

                        @if ($menu->status_ketersediaan != 'Habis')
                            <button
                                onclick="openModal(
                                    {{ $menu->id_menu }},
                                    '{{ addslashes(__($menu->nama_menu)) }}',
                                    '',
                                    {{ $menu->harga }},
                                    '{{ route('cart.add', $menu->id_menu) }}'
                                )"
                                class="w-10 h-10 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white flex items-center justify-center text-xl shadow-sm transition-colors">
                                +
                            </button>
                        @else
                            <span class="text-xs font-semibold text-sultaf-muted border border-sultaf-border rounded-lg px-3 py-2">
                                {{ __('Unavailable') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-sultaf-muted py-16">{{ __('No menu items available yet.') }}</div>
        @endforelse
    </div>

    <script>
        let currentQty = 1;
        let currentPrice = 0;

        function openModal(menuId, menuName, menuDesc, harga, addRoute) {
            currentQty = 1;
            currentPrice = harga;

            // Suntikkan id_menu ke dalam modal form
            document.getElementById('modalMenuId').value = menuId;
            
            document.getElementById('modalMenuName').textContent = menuName;
            document.getElementById('modalMenuDesc').textContent = menuDesc;
            document.getElementById('modalMenuPrice').textContent = formatRp(harga);
            document.getElementById('qtyDisplay').textContent = 1;
            document.getElementById('modalQtyInput').value = 1;
            document.getElementById('modalSubtotal').textContent = formatRp(harga);
            document.getElementById('modalForm').action = addRoute;

            document.getElementById('orderModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function increaseQty() { currentQty++; updateQty(); }
        function decreaseQty() { if (currentQty > 1) { currentQty--; updateQty(); } }

        function updateQty() {
            document.getElementById('qtyDisplay').textContent = currentQty;
            document.getElementById('modalQtyInput').value = currentQty;
            document.getElementById('modalSubtotal').textContent = formatRp(currentPrice * currentQty);
        }

        function formatRp(amount) {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</x-layouts.app>