<x-layouts.guest :title="$tab === 'login' ? __('Login') . ' - Sultaf' : __('Register') . ' - Sultaf'">
    <div class="w-full max-w-sm">

        <div class="flex justify-end mb-3">
            @include('components.lang-switch')
        </div>

        {{-- Brand --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-sultaf-maroon flex items-center justify-center shadow-sm">
                <svg viewBox="0 0 24 24" class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M5 3v6a2 2 0 0 0 2 2v10M5 3v6M9 3v6M7 11V3"/>
                    <path d="M19 3c-2.5 1-3.5 4-2 7l1 2-3 9M19 3l-3 9"/>
                </svg>
            </div>
            <h1 class="mt-4 text-3xl font-serif font-semibold text-sultaf-maroon">Sultaf</h1>
            <p class="mt-1 text-sm text-sultaf-muted">{{ __('Indonesian Gastronomy') }} <em class="italic">{{ __('Refined') }}</em></p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

            {{-- Tabs --}}
            <div class="flex border-b border-sultaf-border">
                <a href="{{ route('login') }}"
                   class="flex-1 text-center py-4 text-sm font-semibold
                          {{ $tab === 'login' ? 'text-sultaf-maroon border-b-2 border-sultaf-maroon' : 'text-sultaf-muted' }}">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}"
                   class="flex-1 text-center py-4 text-sm font-semibold
                          {{ $tab === 'register' ? 'text-sultaf-maroon border-b-2 border-sultaf-maroon' : 'text-sultaf-muted' }}">
                    {{ __('Register') }}
                </a>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if ($tab === 'login')
                    {{-- ===== LOGIN FORM ===== --}}
                    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="input-field">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-sm font-semibold text-sultaf-ink">{{ __('Password') }}</label>
                                <a href="#" class="text-xs font-medium text-sultaf-maroon hover:underline">{{ __('Forgot?') }}</a>
                            </div>
                            <input type="password" name="password" required class="input-field">
                        </div>

                        <button type="submit" class="btn-primary">{{ __('Sign In') }}</button>
                    </form>
                @else
                    {{-- ===== REGISTER FORM ===== --}}
                    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Full Name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Email Address') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812 3456 789" class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Password') }}</label>
                            <input type="password" name="password" required class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" required class="input-field">
                        </div>

                        <button type="submit" class="btn-primary">{{ __('Create Account') }}</button>
                    </form>
                @endif

                {{-- Divider --}}
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-sultaf-border"></div>
                    <span class="text-[11px] tracking-wide text-sultaf-muted uppercase">{{ __('Secure Authentication') }}</span>
                    <div class="flex-1 h-px bg-sultaf-border"></div>
                </div>

                {{-- Social buttons --}}
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="flex items-center justify-center gap-2 border border-sultaf-border rounded-lg py-3 text-sm font-medium text-sultaf-ink hover:bg-sultaf-cream">
                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.07 5.07 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.99.67-2.26 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23z"/><path fill="#FBBC05" d="M5.84 14.1A6.6 6.6 0 0 1 5.5 12c0-.73.12-1.44.34-2.1V7.06H2.18A11 11 0 0 0 1 12c0 1.77.43 3.45 1.18 4.94z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 0 0 2.18 7.06L5.84 9.9C6.71 7.3 9.14 5.38 12 5.38z"/></svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 border border-sultaf-border rounded-lg py-3 text-sm font-medium text-sultaf-ink hover:bg-sultaf-cream">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M16.36 1c.1 1.2-.34 2.4-1.08 3.27-.75.87-1.94 1.55-3.12 1.46-.12-1.17.4-2.4 1.13-3.18C14.05 1.7 15.27 1.04 16.36 1zM20.5 17.4c-.6 1.36-.9 1.96-1.68 3.16-1.08 1.64-2.6 3.7-4.5 3.72-1.68.02-2.12-1.1-4.4-1.08-2.28.02-2.76 1.1-4.44 1.08-1.9-.02-3.34-1.86-4.42-3.5C-1.4 16.1-.5 9.9 3.5 7.5c1.06-.63 2.4-1.02 3.66-1.04 1.58-.02 2.6 1.08 4.4 1.08 1.8 0 2.34-1.1 4.4-1.08.98.02 2.7.32 3.96 1.7-3.5 2-2.9 7.3.58 9.24z"/></svg>
                        Apple
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-sultaf-muted">
            {{ __('By entering Sultaf, you agree to our') }}
            <a href="#" class="underline">{{ __('Terms of Service') }}</a> & <a href="#" class="underline">{{ __('Privacy Policy') }}</a>
        </p>
    </div>
</x-layouts.guest>
