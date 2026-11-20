<x-layouts.app title="Sultaf Restaurant — Authentic Pakistani & Yemeni Flavors">

    <style>
        .hero-video-container {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .hero-video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(74, 14, 20, 0.85) 0%, rgba(122, 22, 32, 0.75) 50%, rgba(43, 27, 27, 0.85) 100%);
            z-index: 1;
        }
        .text-glow {
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
        .custom-shape-divider-bottom {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
            z-index: 10;
        }
        .custom-shape-divider-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }
        .custom-shape-divider-bottom .shape-fill {
            fill: #fcf9f2; 
        }
    </style>

    {{-- ===================== HERO SECTION ===================== --}}
    <section id="home" class="relative min-h-[85vh] flex items-center justify-center pt-10 pb-32 px-4 overflow-hidden rounded-b-3xl shadow-lg -mx-5 lg:-mx-10 xl:-mx-16 mb-10">
        
        {{-- Background Video --}}
        <div class="hero-video-container">
            <video id="heroBgVideo" autoplay loop muted playsinline preload="none" poster="{{ asset('assets/hero_feast.jpg') }}" class="w-full h-full object-cover">
                <source data-src="{{ asset('assets/sultaf_hero_video.mp4') }}" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-4xl mx-auto text-center text-white pt-6">
            
            <div class="inline-flex items-center gap-3 bg-black/40 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-6">
                <span class="flex items-center gap-1.5 text-xs font-bold text-white">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Google 4.9 (150+ Reviews)
                </span>
                <span class="text-white/40">|</span>
                <span class="flex items-center gap-1.5 text-xs font-bold text-white">
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    GoFood 4.5
                </span>
            </div>

            <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-glow leading-tight mb-6">
                {{ __('Authentic Pakistani & Yemeni Flavors') }}
            </h1>

            <p class="text-base sm:text-xl text-sultaf-cream-soft max-w-2xl mx-auto leading-relaxed mb-10 text-glow font-medium">
                {{ __('Experience authentic wok-cooked Chicken Karahi, savory Yemeni Fasolya Hamra, fragrant Mandi rice, and freshly baked Kunafa in Condongcatur, Yogyakarta.') }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <button onclick="openWhatsAppModal()" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-sultaf-gold hover:bg-sultaf-gold-dark text-sultaf-maroon-dark font-extrabold text-base shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/>
                    </svg>
                    <span>{{ __('Order via WhatsApp') }}</span>
                </button>

                <a href="{{ route('menu.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/30 font-extrabold text-base shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>{{ __('Order Online (Cart & Delivery)') }}</span>
                </a>
            </div>

            <div class="inline-flex flex-wrap items-center justify-center gap-4 sm:gap-8 bg-black/40 backdrop-blur-md border border-white/20 px-6 py-3 rounded-2xl text-xs sm:text-sm text-sultaf-cream-soft">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0 z"/></svg>
                    <span>{{ __('Outdoor Seating') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                    <span>{{ __('High-Speed Wi-Fi') }}</span>
                </div>
            </div>
        </div>

        {{-- THE SMOOTH WAVE SEPARATOR --}}
        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    {{-- ===================== ABOUT US SECTION ===================== --}}
    <section class="bg-[#fcf9f2] py-16 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center gap-12">
            
            {{-- Text Content --}}
            <div class="flex-1 min-w-[300px]">
                <div class="flex items-center gap-2 mb-4">
                    <span class="h-0.5 w-10 bg-sultaf-gold"></span>
                    <h2 class="text-sm font-bold tracking-widest text-sultaf-gold uppercase">Our Story</h2>
                </div>
                
                <h3 class="text-3xl md:text-4xl font-serif font-bold text-sultaf-maroon mb-6 leading-tight">
                    A Culinary Journey from Pakistan to Yogyakarta
                </h3>
                
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    At Sultaf, we bring the rich, aromatic traditions of Pakistani and Yemeni cuisine right to the heart of Condongcatur. Our dishes are prepared with authentic spices, slow-cooked to perfection, and served with genuine hospitality.
                </p>
                
                <p class="text-gray-600 text-lg leading-relaxed mb-10">
                    Whether you are craving our signature wok-cooked Chicken Karahi, the comforting Yemeni Fasolya Hamra, or our freshly baked Kunafa, every bite is crafted to remind you of home.
                </p>
                
                {{-- Highlight Box --}}
                <div class="flex items-center gap-5 border-l-4 border-sultaf-gold pl-6 py-2">
                    <div>
                        <p class="font-bold text-sultaf-maroon text-lg mb-1">Authentic Recipes</p>
                        <p class="text-sm text-gray-500">Passed down through generations.</p>
                    </div>
                </div>
            </div>

            {{-- Image Content --}}
            <div class="flex-1 min-w-[300px] relative">
                <img src="{{ asset('assets/ambiance.jpg') }}" alt="Sultaf Restaurant Ambiance" class="w-full h-[500px] object-cover rounded-3xl shadow-2xl relative z-10">
            </div>
            
        </div>
    </section>

    {{-- ===================== GALLERY SECTION ===================== --}}
    <section class="bg-[#fcf9f2] py-16 border-t border-gray-200 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header --}}
            <div class="text-center mb-12">
                <h2 class="text-sm font-bold tracking-widest text-sultaf-gold uppercase mb-2">Our Gallery</h2>
                <h3 class="text-3xl md:text-4xl font-serif font-bold text-sultaf-maroon">A Feast for the Eyes</h3>
            </div>

            {{-- Gallery Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                
                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/butter_chicken.jpg') }}" alt="Butter Chicken" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>
                
                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/ambiance.jpg') }}" alt="Restaurant Ambiance" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>

                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/chicken_karahi.jpg') }}" alt="Chicken Karahi" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>

                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/kunafa.jpg') }}" alt="Kunafa" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>

                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/hero_feast.jpg') }}" alt="Feast" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>

                <div class="relative rounded-2xl overflow-hidden h-[300px] shadow-lg group">
                    <img src="{{ asset('assets/tandoori_chicken.jpg') }}" alt="Tandoori Chicken" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="bg-sultaf-maroon border-t-4 border-sultaf-gold py-12 relative z-10 w-full -mx-5 lg:-mx-10 xl:-mx-16 px-5 lg:px-10">
        {{-- DIUBAH: flex-col items-center justify-center agar semuanya ke tengah --}}
        <div class="max-w-7xl mx-auto flex flex-col items-center justify-center text-center gap-4">
            
            <div>
                <p class="font-serif text-3xl text-white mb-1 font-bold tracking-wide">SULTAF</p>
                <p class="text-xs text-sultaf-gold-light uppercase tracking-widest">Pakistani & Yemeni Cuisine</p>
            </div>
            
            <p class="text-sm text-white/70">
                © {{ date('Y') }} Sultaf Restaurant. All rights reserved.
            </p>
            
            {{-- DIHAPUS: Link Staff & Admin Login sudah dihilangkan --}}
            
        </div>
    </footer>

    {{-- ===================== WHATSAPP MODAL ===================== --}}
    <div id="whatsAppModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-sultaf-border relative animate-in fade-in zoom-in duration-200">
            <button onclick="closeWhatsAppModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted hover:text-sultaf-ink">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2.5 rounded-xl bg-sultaf-maroon/10 text-sultaf-maroon">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-sultaf-ink">{{ __('Quick WhatsApp Order') }}</h3>
            </div>

            <form onsubmit="submitWhatsAppOrder(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-sultaf-muted mb-1">{{ __('Select Dish') }}</label>
                    <select id="waItemSelect" class="w-full border border-sultaf-border bg-sultaf-cream/40 rounded-xl px-4 py-3 text-sm font-medium text-sultaf-ink focus:ring-2 focus:ring-sultaf-maroon/30 focus:outline-none">
                        <option value="Chicken Karahi">Chicken Karahi</option>
                        <option value="Zurbiyan Rice">Zurbiyan Rice</option>
                        <option value="Kunafa">Kunafa</option>
                    </select>
                </div>
                <div class="pt-3 flex gap-3">
                    <button type="button" onclick="closeWhatsAppModal()" class="flex-1 py-3.5 rounded-xl border border-sultaf-border text-sultaf-ink font-bold text-sm hover:bg-sultaf-cream transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="flex-1 py-3.5 px-6 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-bold text-sm shadow-md transition-colors flex items-center justify-center gap-2 border border-sultaf-gold/40">
                        <span>{{ __('Send') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== CLIENT SCRIPTS ===================== --}}
    <script>
        window.addEventListener('load', function () {
            const video = document.getElementById('heroBgVideo');
            if (video) {
                const source = video.querySelector('source[data-src]');
                if (source && source.dataset.src) {
                    source.src = source.dataset.src;
                    video.load();
                    video.play().catch(function () {});
                }
            }
        });

        const WA_PHONE = '628131699146';
        let waQty = 1;

        function openWhatsAppModal() {
            document.getElementById('whatsAppModal').classList.remove('hidden');
            document.getElementById('whatsAppModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeWhatsAppModal() {
            document.getElementById('whatsAppModal').classList.add('hidden');
            document.getElementById('whatsAppModal').classList.remove('flex');
            document.body.style.overflow = '';
        }
        function submitWhatsAppOrder(e) {
            e.preventDefault();
            const dish = document.getElementById('waItemSelect').value;
            const message = `Halo Sultaf Restaurant!\n\nSaya ingin memesan:\n* ${waQty}x ${dish}\n\nMohon konfirmasi pesanan. Terima kasih!`;
            const waUrl = `https://wa.me/${WA_PHONE}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
            closeWhatsAppModal();
        }
    </script>

</x-layouts.app>