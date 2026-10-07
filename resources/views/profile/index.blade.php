<x-layouts.app title="{{ __('My Profile') }} - Sultaf">

    <div class="px-5 lg:px-0 pt-6 pb-6 max-w-4xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('menu.index') }}"
               class="flex items-center gap-2 text-sm text-sultaf-muted hover:text-sultaf-maroon font-medium transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                {{ __('Back to Menu') }}
            </a>
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('My Profile') }}</h1>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="mb-6 bg-sultaf-success-soft border border-sultaf-success/20 text-sultaf-success text-sm rounded-xl px-4 py-3">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- CARD: INFO MEMBER --}}
        <div class="bg-sultaf-maroon-dark bg-sultaf-pattern-dark rounded-2xl p-6 mb-6 text-white">
            <div class="flex items-center gap-5">
                {{-- AVATAR --}}
                <div class="w-20 h-20 rounded-full bg-white/10 flex items-center justify-center shrink-0 overflow-hidden border-2 border-sultaf-gold-light/30">
                    @if ($user->foto_profil ?? false)
                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    @endif
                </div>

                {{-- INFO --}}
                <div class="flex-1 min-w-0">
                    <h2 class="font-serif text-2xl font-bold mb-1">{{ $user->username }}</h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-wide bg-white/15 text-white/90 px-2.5 py-1 rounded-full">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if ($user->member)
                            <a href="{{ route('rewards.index') }}"
                               class="flex items-center gap-1.5 text-xs font-semibold text-sultaf-gold-light hover:text-white transition-colors">
                                <span class="text-base">⭐</span>
                                {{ number_format($user->member->total_poin) }} {{ __('pts') }}
                                <span>→</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD: ACCOUNT INFORMATION --}}
        <div class="bg-white rounded-2xl border border-sultaf-border/70 overflow-hidden">

            <div class="px-6 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Account Information') }}</h3>
                <p class="text-xs text-sultaf-muted mt-1">{{ __('Update your profile details below.') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- FOTO PROFIL --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-2">{{ __('Profile Photo') }}</label>
                    <div class="flex items-center gap-4">
                        <label class="cursor-pointer">
                            <input type="file" name="foto_profil" accept="image/*" class="hidden">
                            <span class="inline-flex items-center gap-2 border border-sultaf-border bg-white hover:bg-sultaf-cream text-sultaf-ink text-sm font-medium rounded-xl px-4 py-2.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                {{ __('Choose Photo') }}
                            </span>
                        </label>
                        <span class="text-xs text-sultaf-muted">{{ __('JPG/PNG, max 2MB') }}</span>
                    </div>
                    @error('foto_profil') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- USERNAME --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Username') }}</label>
                    <input type="text" name="username"
                           value="{{ old('username', $user->username) }}"
                           required
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- NO HP --}}
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Phone Number') }}</label>
                    <input type="text" name="no_hp"
                           value="{{ old('no_hp', $user->no_hp) }}"
                           placeholder="+62 812 3456 789"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    @error('no_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- SUBMIT --}}
                <div class="pt-4 border-t border-sultaf-border">
                    <button type="submit"
                            class="bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- CARD: PASSWORD --}}
        <div class="bg-white rounded-2xl border border-sultaf-border/70 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-sultaf-border">
                <h3 class="font-semibold text-sultaf-ink">{{ __('Change Password') }}</h3>
                <p class="text-xs text-sultaf-muted mt-1">{{ __('Leave blank if you don\'t want to change.') }}</p>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('New Password') }}</label>
                    <input type="password" name="password"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-sultaf-border rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-sultaf-maroon">
                </div>

                <div class="pt-4 border-t border-sultaf-border">
                    <button type="submit"
                            class="border border-sultaf-border bg-white hover:bg-sultaf-cream text-sultaf-ink text-sm font-semibold rounded-xl px-6 py-2.5 transition-colors">
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                    class="w-full text-center text-sm text-sultaf-danger font-medium py-3 border border-sultaf-border bg-white rounded-xl hover:bg-red-50 transition-colors">
                {{ __('Sign Out') }}
            </button>
        </form>

    </div>

</x-layouts.app>