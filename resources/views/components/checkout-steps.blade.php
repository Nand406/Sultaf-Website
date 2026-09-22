@php
    $steps = [__('Review'), __('Details'), __('Payment'), __('Finish')];
@endphp

<div class="flex items-center justify-between mb-8 px-2">
    @foreach ($steps as $i => $label)
        @php $num = $i + 1; @endphp
        <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-semibold
                            {{ $num < $current ? 'bg-sultaf-maroon text-white'
                               : ($num === $current ? 'bg-sultaf-maroon text-white ring-4 ring-sultaf-maroon/15' : 'bg-white border border-sultaf-border text-sultaf-muted') }}">
                    @if ($num < $current)
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span class="text-[11px] font-semibold uppercase tracking-wide {{ $num === $current ? 'text-sultaf-maroon' : 'text-sultaf-muted' }}">
                    {{ $label }}
                </span>
            </div>
            @if (!$loop->last)
                <div class="flex-1 h-px mx-2 {{ $num < $current ? 'bg-sultaf-maroon' : 'bg-sultaf-border' }}"></div>
            @endif
        </div>
    @endforeach
</div>
