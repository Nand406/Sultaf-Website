<x-layouts.app title="{{ __('My Profile') }} - Sultaf">
    <div class="px-5 lg:px-0 pt-6 max-w-lg lg:max-w-2xl mx-auto lg:mx-0 pb-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="font-serif text-2xl font-bold text-sultaf-maroon">{{ __('My Profile') }}</h1>
            @include('components.lang-switch')
        </div>

        @if (session('success'))
            <div class="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-xl px-4 py-3">✓ {{ session('success') }}</div>
        @endif
        @if (session('successPassword'))
            <div class="mb-4 text-sm text-emerald-600 bg-emerald-50 rounded-xl px-4 py-3">✓ {{ session('successPassword') }}</div>
        @endif

        {{-- ===================== Kartu Ringkasan ===================== --}}
        <div class="bg-white rounded-2xl p-6 mb-6 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full overflow-hidden bg-sultaf-maroon/10 flex items-center justify-center shrink-0">
                @if ($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-bold text-sultaf-maroon">{{ substr($user->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-serif text-xl font-bold text-sultaf-ink truncate">{{ $user->name }}</p>
                <p class="text-sm text-sultaf-muted truncate">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="text-xs font-semibold bg-sultaf-maroon/10 text-sultaf-maroon px-2.5 py-1 rounded-full capitalize">
                        {{ $user->role }}
                    </span>
                    @if ($user->isMember())
                        <span class="text-xs font-semibold bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full">
                            {{ number_format($user->points) }} {{ __('pts') }}
                        </span>
                        <a href="{{ route('rewards.index') }}" class="text-xs font-medium text-sultaf-maroon hover:underline">
                            {{ __('View Rewards') }} →
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===================== Form Informasi Akun ===================== --}}
        <div class="bg-white rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-sultaf-ink mb-4">{{ __('Account Information') }}</h2>

            @if ($errors->hasAny(['name', 'phone', 'avatar']))
                <div class="mb-4 text-sm text-red-600 space-y-1">
                    @foreach ($errors->only(['name', 'phone', 'avatar']) as $fieldErrors)
                        @foreach ((array) $fieldErrors as $e)
                            <p>{{ is_array($e) ? $e[0] : $e }}</p>
                        @endforeach
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Profile Photo') }}</label>
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-sultaf-maroon border border-sultaf-border rounded-lg px-4 py-2 hover:bg-sultaf-cream">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4M7 9l5-5 5 5M5 20h14"/></svg>
                        <span id="avatarLabel">{{ __('Choose Photo') }}</span>
                        <input type="file" name="avatar" accept="image/*" class="hidden"
                               onchange="document.getElementById('avatarLabel').textContent = this.files[0]?.name ?? '{{ __('Choose Photo') }}'">
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Full Name') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="input-field">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Email Address') }}</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="input-field bg-sultaf-cream-dark/40 text-sultaf-muted cursor-not-allowed">
                    <p class="text-xs text-sultaf-muted mt-1">{{ __('Email cannot be changed. Contact staff if needed.') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+62 812 3456 789" class="input-field">
                </div>

                <button type="submit" class="btn-primary">{{ __('Save Changes') }}</button>
            </form>
        </div>

        {{-- ===================== Form Ganti Password ===================== --}}
        <div class="bg-white rounded-2xl p-6">
            <h2 class="font-semibold text-sultaf-ink mb-4">{{ __('Change Password') }}</h2>

            @if ($errors->hasAny(['current_password', 'password']))
                <div class="mb-4 text-sm text-red-600 space-y-1">
                    @foreach ($errors->only(['current_password', 'password']) as $fieldErrors)
                        @foreach ((array) $fieldErrors as $e)
                            <p>{{ is_array($e) ? $e[0] : $e }}</p>
                        @endforeach
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Current Password') }}</label>
                    <input type="password" name="current_password" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('New Password') }}</label>
                    <input type="password" name="password" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-sultaf-ink mb-1.5">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="password_confirmation" required class="input-field">
                </div>

                <button type="submit" class="btn-primary">{{ __('Update Password') }}</button>
            </form>
        </div>
    </div>
</x-layouts.app>
