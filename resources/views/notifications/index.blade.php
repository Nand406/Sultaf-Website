<x-layouts.app title="{{ __('Notifications') }} - Sultaf">

    <div class="px-5 lg:px-0 pt-6 pb-6 max-w-3xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('menu.index') }}"
               class="flex items-center gap-2 text-sm text-sultaf-muted hover:text-sultaf-maroon font-medium transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Menu') }}
            </a>
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Notifications') }}</h1>
        </div>

        {{-- LIST NOTIFIKASI --}}
        <div class="space-y-3">
            @forelse ($notifikasis as $notif)
                <div class="bg-white rounded-2xl border border-sultaf-border/70 p-5 hover:border-sultaf-maroon/30 transition-colors">
                    <div class="flex items-start gap-4">
                        {{-- ICON --}}
                        <div class="w-10 h-10 rounded-xl bg-sultaf-cream flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-sultaf-maroon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>

                        {{-- CONTENT --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <h3 class="font-semibold text-sultaf-ink">{{ $notif->judul }}</h3>
                                <span class="text-xs text-sultaf-muted whitespace-nowrap">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-sultaf-muted leading-relaxed">
                                {{ $notif->id_pesan }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-sultaf-border/70 p-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-sultaf-cream flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-sultaf-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <p class="text-sultaf-muted">{{ __('No notifications yet.') }}</p>
                    <p class="text-xs text-sultaf-muted/70 mt-1">{{ __('We will notify you about promos and order updates.') }}</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if ($notifikasis->hasPages())
            <div class="mt-6">{{ $notifikasis->links() }}</div>
        @endif

    </div>

</x-layouts.app>