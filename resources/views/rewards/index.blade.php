<x-layouts.app title="{{ __('Rewards') }} - Sultaf">

    <div class="px-5 lg:px-0 pt-6 pb-6 max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 mb-6 text-white">
            <p class="text-[11px] uppercase tracking-widest text-sultaf-gold-light mb-2">{{ __('Current Balance') }}</p>
            <p class="font-serif text-4xl lg:text-5xl font-bold mb-5">
                {{ number_format($totalPoin) }} <span class="text-lg font-normal text-white/60">{{ __('Points') }}</span>
            </p>

            @if ($nextBenefit)
                @php
                    $progress = $nextBenefit->minimal_poin > 0
                        ? min(100, round($totalPoin / $nextBenefit->minimal_poin * 100))
                        : 100;
                    $pointsNeeded = max(0, $nextBenefit->minimal_poin - $totalPoin);
                @endphp
                <div class="max-w-md">
                    <div class="flex justify-between text-xs text-white/70 mb-1.5">
                        <span>{{ __('Next reward') }}: {{ $nextBenefit->nama_promo }}</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="h-2 bg-white/15 rounded-full overflow-hidden">
                        <div class="h-full bg-sultaf-gold rounded-full transition-all" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-white/50 mt-2">
                        {{ $pointsNeeded }} {{ __('more points needed') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- ACTIVE DISCOUNT --}}
        @if ($activeDiscountBenefit)
            <div class="bg-sultaf-success-soft border border-sultaf-success/20 rounded-2xl p-4 mb-6 flex items-center gap-3">
                <span class="text-2xl">🎉</span>
                <p class="text-sm text-sultaf-success">
                    {{ __('Your discount is applied automatically at checkout – no code needed.') }}
                </p>
            </div>
        @endif

        {{-- BENEFITS LIST --}}
        <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Available Rewards') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            @forelse ($benefits as $benefit)
                @php $unlocked = $totalPoin >= $benefit->minimal_poin; @endphp
                <div class="bg-white rounded-2xl border {{ $unlocked ? 'border-sultaf-success/40' : 'border-sultaf-border' }} p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-sultaf-ink">{{ $benefit->nama_promo }}</h3>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $unlocked ? 'bg-sultaf-success-soft text-sultaf-success' : 'bg-sultaf-cream text-sultaf-muted' }}">
                            {{ $unlocked ? __('Unlocked') : __('Locked') }}
                        </span>
                    </div>
                    <p class="text-sm text-sultaf-muted mb-3">
                        {{ __('Min.') }} {{ number_format($benefit->minimal_poin) }} {{ __('points') }}
                    </p>
                    <p class="font-serif text-lg font-bold text-sultaf-maroon">
                        Rp {{ number_format($benefit->potongan_harga, 0, ',', '.') }}
                    </p>
                </div>
            @empty
                <div class="col-span-full text-center text-sultaf-muted py-10">
                    {{ __('No rewards available yet.') }}
                </div>
            @endforelse
        </div>

        {{-- RIWAYAT POIN --}}
        <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Points Activity') }}</h2>
        <div class="bg-white rounded-2xl border border-sultaf-border/70 divide-y divide-sultaf-border overflow-hidden">
            @forelse ($pointsHistory as $row)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <p class="text-sm font-medium text-sultaf-ink">
                            #SLT-{{ str_pad($row['transaksi']->id_transaksi, 5, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-xs text-sultaf-muted">
                            {{ $row['transaksi']->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-sultaf-success">
                        +{{ $row['poin'] }} {{ __('pts') }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center text-sultaf-muted text-sm">
                    {{ __('No points activity yet.') }}
                </div>
            @endforelse
        </div>

    </div>
</x-layouts.app>