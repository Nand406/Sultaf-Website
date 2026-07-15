<x-layouts.app title="{{ __('Rewards') }} - Sultaf">
    <div class="px-5 lg:px-0 pt-6 max-w-lg lg:max-w-2xl mx-auto lg:mx-0">

        <div class="flex items-center justify-between mb-6">
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('Rewards') }}</h1>
            @include('components.lang-switch')
        </div>

        @if (! $user->isMember())
            {{-- Bukan member — tampilkan penjelasan singkat --}}
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="w-14 h-14 rounded-2xl bg-sultaf-maroon/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-sultaf-maroon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M8.5 13 7 21l5-2.5L17 21l-1.5-8"/></svg>
                </div>
                <h2 class="font-serif text-xl font-bold text-sultaf-ink mb-2">{{ __('Not a Member Yet') }}</h2>
                <p class="text-sultaf-muted text-sm max-w-sm mx-auto">
                    {{ __('Ask our staff to register you as a member to start earning points and unlock exclusive discounts on every order.') }}
                </p>
            </div>
        @else
            {{-- ===================== Saldo Poin ===================== --}}
            <div class="bg-sultaf-maroon rounded-2xl p-6 text-white mb-6">
                <p class="text-xs uppercase tracking-wide text-white/70 mb-1">{{ __('Current Balance') }}</p>
                <p class="font-serif text-4xl font-bold mb-4">
                    {{ number_format($user->points) }} <span class="text-lg font-normal">{{ __('Points') }}</span>
                </p>

                @if ($nextBenefit)
                    @php
                        $progress = $nextBenefit->poin_dibutuhkan > 0
                            ? min(100, round($user->points / $nextBenefit->poin_dibutuhkan * 100))
                            : 100;
                        $pointsNeeded = max(0, $nextBenefit->poin_dibutuhkan - $user->points);
                    @endphp
                    <div class="h-2 bg-white/20 rounded-full overflow-hidden mb-2">
                        <div class="h-full bg-white rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-white/70">
                        {{ number_format($pointsNeeded) }} {{ __('more points to unlock') }} "{{ __($nextBenefit->nama_benefit) }}"
                    </p>
                @elseif ($activeDiscountBenefit)
                    <p class="text-xs text-white/80">
                        ✓ {{ __('You currently enjoy') }}: {{ __($activeDiscountBenefit->nama_benefit) }}
                    </p>
                @endif
            </div>

            @if ($activeDiscountBenefit)
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6 flex items-center gap-3">
                    <span class="text-2xl">🎉</span>
                    <p class="text-sm text-emerald-700">
                        {{ __('Your discount is applied automatically at checkout — no code needed.') }}
                    </p>
                </div>
            @endif

            {{-- ===================== Daftar Benefit ===================== --}}
            <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Available Rewards') }}</h2>
            <div class="space-y-3 mb-8">
                @forelse ($benefits as $benefit)
                    @php $unlocked = $user->points >= $benefit->poin_dibutuhkan; @endphp
                    <div class="bg-white rounded-2xl p-4 flex items-center gap-4 {{ $unlocked ? '' : 'opacity-60' }}">
                        <div class="w-11 h-11 rounded-xl {{ $unlocked ? 'bg-sultaf-maroon/10' : 'bg-gray-100' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 {{ $unlocked ? 'text-sultaf-maroon' : 'text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                @if ($benefit->tipe === 'diskon')
                                    <path d="M12 2l2.9 6.3 6.9.6-5.2 4.6 1.6 6.8L12 16.9l-6.2 3.4 1.6-6.8L2.2 8.9l6.9-.6L12 2z"/>
                                @else
                                    <circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 3"/>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sultaf-ink">{{ __($benefit->nama_benefit) }}</p>
                            @if ($benefit->deskripsi)
                                <p class="text-xs text-sultaf-muted">{{ __($benefit->deskripsi) }}</p>
                            @endif
                            <p class="text-xs text-sultaf-muted mt-1">
                                {{ number_format($benefit->poin_dibutuhkan) }} {{ __('points required') }}
                                @if ($benefit->tipe === 'gratis_menu')
                                    · {{ __('Show this to cashier to redeem') }}
                                @endif
                            </p>
                        </div>
                        <div class="shrink-0">
                            @if ($unlocked)
                                <span class="text-xs font-semibold bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full">{{ __('Unlocked') }}</span>
                            @else
                                <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-3 py-1.5 rounded-full">{{ __('Locked') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sultaf-muted py-10">{{ __('No rewards available at the moment.') }}</p>
                @endforelse
            </div>

            {{-- ===================== Riwayat Poin ===================== --}}
            <h2 class="font-semibold text-sultaf-ink mb-3">{{ __('Points Activity') }}</h2>
            <div class="bg-white rounded-2xl divide-y divide-sultaf-border overflow-hidden mb-6">
                @forelse ($pointsHistory as $row)
                    <div class="flex items-center justify-between p-4">
                        <div>
                            <p class="text-sm font-medium text-sultaf-ink">#{{ $row['transaksi']->kode_transaksi }}</p>
                            <p class="text-xs text-sultaf-muted">{{ $row['transaksi']->tgl_transaksi->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <span class="text-sm font-semibold text-emerald-600">+{{ $row['poin'] }} {{ __('pts') }}</span>
                    </div>
                @empty
                    <p class="text-center text-sultaf-muted py-10">{{ __('No points activity yet.') }}</p>
                @endforelse
            </div>
        @endif
    </div>
</x-layouts.app>
