<x-layouts.app title="{{ __('Rewards') }} - Sultaf">
    <div class="px-5 lg:px-0 pt-6 max-w-lg lg:max-w-3xl mx-auto lg:mx-0 pb-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Rewards') }}</h1>
            @include('components.lang-switch')
        </div>

        @if (! $user->isMember())
            <div class="bg-white rounded-2xl border border-sultaf-border/70 p-10 text-center">
                <div class="w-14 h-14 rounded-2xl bg-sultaf-maroon/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                </div>
                <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-2">{{ __('Not a Member Yet') }}</h2>
                <p class="text-sultaf-muted text-sm max-w-sm mx-auto">
                    {{ __('Ask our staff to register you as a member to start earning points and unlock exclusive discounts on every order.') }}
                </p>
            </div>
        @else
            {{-- ===================== KARTU POIN ===================== --}}
            <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 lg:p-8 text-white mb-6 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-sultaf-gold-light mb-2">{{ __('Current Balance') }}</p>
                    <p class="font-serif text-4xl lg:text-5xl font-bold mb-5">
                        {{ number_format($user->points) }} <span class="text-lg font-normal text-white/60">{{ __('Points') }}</span>
                    </p>

                    @if ($nextBenefit)
                        @php
                            $progress = $nextBenefit->poin_dibutuhkan > 0
                                ? min(100, round($user->points / $nextBenefit->poin_dibutuhkan * 100))
                                : 100;
                            $pointsNeeded = max(0, $nextBenefit->poin_dibutuhkan - $user->points);
                        @endphp
                        <div class="h-2 bg-white/15 rounded-full overflow-hidden mb-2 max-w-md">
                            <div class="h-full bg-sultaf-gold rounded-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-xs text-white/60">
                            {{ number_format($pointsNeeded) }} {{ __('more points to unlock') }}
                            <strong class="text-sultaf-gold-light">{{ __($nextBenefit->nama_benefit) }}</strong>
                        </p>
                    @elseif ($activeDiscountBenefit)
                        <p class="text-xs text-white/70">
                            ✓ {{ __('You currently enjoy') }}: <strong class="text-sultaf-gold-light">{{ __($activeDiscountBenefit->nama_benefit) }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            @if ($activeDiscountBenefit)
                <div class="bg-sultaf-success-soft border border-sultaf-success/20 rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <p class="text-sm text-sultaf-success">
                        {{ __('Your discount is applied automatically at checkout — no code needed.') }}
                    </p>
                </div>
            @endif

            {{-- ===================== DAFTAR BENEFIT ===================== --}}
            <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Available Rewards') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                @forelse ($benefits as $benefit)
                    @php $unlocked = $user->points >= $benefit->poin_dibutuhkan; @endphp
                    <div class="bg-white rounded-2xl border p-4 flex items-start gap-4
                                {{ $unlocked ? 'border-sultaf-gold/40' : 'border-sultaf-border/70 opacity-60' }}">
                        <div class="w-11 h-11 rounded-xl {{ $unlocked ? 'bg-sultaf-gold/15' : 'bg-sultaf-cream-dark' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 {{ $unlocked ? 'text-sultaf-gold' : 'text-sultaf-muted' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                @if ($benefit->tipe === 'diskon')
                                    <path d="M12 2l2.9 6.3 6.9.6-5.2 4.6 1.6 6.8L12 16.9l-6.2 3.4 1.6-6.8L2.2 8.9l6.9-.6L12 2z"/>
                                @else
                                    <circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 3"/>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <p class="font-semibold text-sultaf-ink">{{ __($benefit->nama_benefit) }}</p>
                                @if ($unlocked)
                                    <span class="text-[10px] font-semibold bg-sultaf-success-soft text-sultaf-success px-2 py-0.5 rounded-full shrink-0">{{ __('Unlocked') }}</span>
                                @else
                                    <span class="text-[10px] font-semibold bg-sultaf-cream-dark text-sultaf-muted px-2 py-0.5 rounded-full shrink-0">{{ __('Locked') }}</span>
                                @endif
                            </div>
                            @if ($benefit->deskripsi)
                                <p class="text-xs text-sultaf-muted mb-1">{{ __($benefit->deskripsi) }}</p>
                            @endif
                            <p class="text-xs text-sultaf-muted">
                                {{ number_format($benefit->poin_dibutuhkan) }} {{ __('points required') }}
                                @if ($benefit->tipe === 'gratis_menu')
                                    · {{ __('Show this to cashier to redeem') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="sm:col-span-2 text-center text-sultaf-muted py-10">{{ __('No rewards available at the moment.') }}</p>
                @endforelse
            </div>

            {{-- ===================== RIWAYAT POIN ===================== --}}
            <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Points Activity') }}</h2>
            <div class="bg-white rounded-2xl border border-sultaf-border/70 divide-y divide-sultaf-border overflow-hidden">
                @forelse ($pointsHistory as $row)
                    <div class="flex items-center justify-between p-4">
                        <div>
                            <p class="text-sm font-medium text-sultaf-ink">#{{ $row['transaksi']->kode_transaksi }}</p>
                            <p class="text-xs text-sultaf-muted">{{ $row['transaksi']->tgl_transaksi->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-sultaf-success">+{{ $row['poin'] }} {{ __('pts') }}</span>
                    </div>
                @empty
                    <p class="text-center text-sultaf-muted py-10">{{ __('No points activity yet.') }}</p>
                @endforelse
            </div>
        @endif
    </div>
</x-layouts.app>
