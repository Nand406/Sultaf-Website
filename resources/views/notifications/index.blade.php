<x-layouts.app title="{{ __('Promotions & Notifications') }} - Sultaf">
    <div class="px-5 lg:px-10 xl:px-16 pt-6 max-w-2xl mx-auto lg:mx-0">
        <div class="flex items-center justify-between mb-5">
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Promotions & Notifications') }}</h1>
            @include('components.lang-switch')
        </div>

        <div class="space-y-4">
            @forelse ($pesanPromo as $pesan)
                <div class="bg-white rounded-2xl p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sultaf-maroon/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-sultaf-ink">{{ $pesan->judul }}</h3>
                            <p class="text-sm text-sultaf-muted mt-1">{{ $pesan->pesan }}</p>
                            <p class="text-xs text-sultaf-muted mt-2">{{ $pesan->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-16 text-center text-sultaf-muted">
                    {{ __('No promotions for you at the moment.') }}
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
