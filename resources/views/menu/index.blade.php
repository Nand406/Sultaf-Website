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

    {{-- ===================== HEADER ===================== --}}
    <div class="sticky top-0 z-10 bg-sultaf-cream/95 backdrop-blur px-5 lg:px-0 pt-5 pb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 lg:gap-3">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-sultaf-maroon flex items-center justify-center">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 lg:w-5 lg:h-5 text-sultaf-gold-light" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/>
                        <path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/>
                    </svg>
                </div>
                <span class="font-serif text-xl lg:text-2xl font-semibold text-sultaf-maroon">Sultaf</span>
            </div>

            <div class="hidden lg:flex items-center gap-1">
                @include('components.nav-items')
            </div>

            <div class="flex items-center gap-2">
                @include('components.lang-switch')

                @auth
                    <a href="{{ route('profile.show') }}" title="{{ __('My Profile') }}"
                       class="w-8 h-8 rounded-full overflow-hidden bg-sultaf-maroon/10 border border-sultaf-border flex items-center justify-center text-sultaf-maroon text-xs font-bold shrink-0">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </a>

                    <a href="{{ route('notifications.index') }}" class="w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 5-6 8-6s6.5 2 8 6"/></svg>
                    </a>
                @endauth

                <a href="{{ route('cart.index') }}" class="relative w-8 h-8 rounded-full bg-white border border-sultaf-border flex items-center justify-center text-sultaf-muted hover:text-sultaf-maroon">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                        <path d="M1 1h4l2.4 12.4a2 2 0 0 0 2 1.6h8.6a2 2 0 0 0 2-1.6L23 6H6"/>
                    </svg>
                    @if (session('cart') && count(session('cart')))
                        <span class="absolute -top-1 -right-1 bg-sultaf-maroon text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                            {{ array_sum(session('cart')) }}
                        </span>
                    @endif
                </a>
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

    {{-- ===================== KATEGORI ===================== --}}
    <div class="px-5 lg:px-0 mb-5">
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
            @foreach ($categories as $cat)
                <a href="{{ route('menu.index', ['category' => $cat->slug]) }}"
                   class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-colors
                          {{ $activeCategory === $cat->slug
                                ? 'bg-sultaf-maroon text-white'
                                : 'bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon/40' }}">
                    {{ __($cat->name) }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===================== DAFTAR MENU ===================== --}}
    <div class="px-5 lg:px-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-6">
        @forelse ($menus as $menu)
            <div class="bg-white rounded-2xl overflow-hidden border border-sultaf-border/70 shadow-sm flex flex-col {{ $menu->habis ? 'grayscale opacity-75' : 'hover:shadow-md transition-shadow' }}">
                <div class="relative h-44">
                    @if ($menu->foto_makanan)
                        <img src="{{ asset('storage/'.$menu->foto_makanan) }}" alt="{{ __($menu->nama_makanan) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted text-sm">
                            {{ __('No photo') }}
                        </div>
                    @endif

                    @if ($menu->habis)
                        <div class="absolute inset-0 bg-black/55 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm bg-black/50 border border-white/30 px-4 py-2 rounded-full text-center">
                                {{ __('Menu Unavailable') }}
                            </span>
                        </div>
                    @else
                        <div class="absolute top-3 left-3 flex items-center gap-1 bg-white/95 rounded-full px-2 py-1 text-xs font-semibold text-sultaf-ink">
                            <svg class="w-3.5 h-3.5 text-sultaf-gold" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.6-5.2 4.6 1.6 6.8L12 16.9l-6.2 3.4 1.6-6.8L2.2 8.9l6.9-.6L12 2z"/></svg>
                            {{ number_format($menu->rating, 1) }}
                        </div>

                        @if ($menu->is_halal)
                            <span class="absolute top-3 right-3 bg-sultaf-success-soft text-sultaf-success text-[11px] font-bold tracking-wide px-2.5 py-1 rounded-full">{{ __('HALAL') }}</span>
                        @endif
                    @endif
                </div>

                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-serif text-lg font-semibold text-sultaf-ink leading-snug">{{ __($menu->nama_makanan) }}</h3>
                    <p class="text-sm text-sultaf-muted mt-1 leading-snug line-clamp-2">{{ __($menu->deskripsi ?? '') }}</p>

                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <div class="flex items-center gap-0.5 text-xs text-sultaf-muted mb-1">
                                <span>{{ __('Spice') }}:</span>
                                @for ($i = 0; $i < max($menu->spice_level, 1); $i++)
                                    <span>🌶️</span>
                                @endfor
                            </div>
                            <p class="font-serif text-lg font-bold text-sultaf-maroon">{{ $menu->harga_format }}</p>
                        </div>

                        @if (!$menu->habis)
                            <button
                                onclick="openModal(
                                    {{ $menu->id }},
                                    '{{ addslashes(__($menu->nama_makanan)) }}',
                                    '{{ addslashes(__($menu->deskripsi ?? '')) }}',
                                    {{ $menu->harga_makanan }},
                                    '{{ route('cart.add', $menu) }}'
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
            <div class="col-span-full text-center text-sultaf-muted py-16">{{ __('No menu items in this category yet.') }}</div>
        @endforelse
    </div>

    <script>
        let currentQty = 1;
        let currentPrice = 0;

        function openModal(menuId, menuName, menuDesc, harga, addRoute) {
            currentQty = 1;
            currentPrice = harga;

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
