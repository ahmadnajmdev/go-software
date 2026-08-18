{{--
    WhatsApp call-to-action.

    <x-whatsapp-cta source="hero"/>
    <x-whatsapp-cta source="service_pos" variant="secondary"/>
    <x-whatsapp-cta source="contact" variant="link" :label="t('waAsk')"/>

    The message is looked up from the source, so Kurdish and Arabic visitors
    get one pre-written in their own language. Renders nothing at all if no
    WhatsApp number is configured.
--}}
@props([
    'source',
    'message' => null,
    'variant' => 'primary',
    'label' => null,
    'service' => null,
    'full' => false,
])
@php
    $text = $message ?? \App\Support\WhatsApp::messageFor($source);
    $href = \App\Support\WhatsApp::url($text);
    $caption = $label ?? t('waTalk');

    $glyph = 'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.15h-.01a8.23 8.23 0 01-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.82c0 4.54-3.7 8.23-8.24 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.24-.64.8-.79.97-.14.16-.29.18-.54.06-.25-.13-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.16 0-.43.06-.65.31-.22.25-.85.84-.85 2.03s.87 2.35 1 2.51c.12.17 1.72 2.62 4.16 3.68.58.25 1.03.4 1.39.51.58.19 1.11.16 1.53.1.47-.07 1.47-.6 1.68-1.18.2-.58.2-1.07.14-1.18-.06-.1-.22-.16-.47-.28z';

    $base = 'display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-family: \'Space Grotesk\'; font-weight: 600; transition: .25s;'
        .($full ? ' width: 100%;' : '');

    $style = match ($variant) {
        // Sits next to the primary CTA without competing with it.
        'secondary' => $base.' background: transparent; color: var(--gs-deep-fg, #fff); border: 1px solid var(--gs-deep-line, rgba(255,255,255,.28)); padding: 14px 26px; border-radius: var(--gs-r-btn, 10px); font-size: 16px;',
        'light' => $base.' background: #fff; color: #0d1826; border: 1px solid #dde5e5; padding: 13px 22px; border-radius: var(--gs-r-btn, 10px); font-size: 15px;',
        'link' => $base.' gap: 8px; color: #25D366; font-size: 15px;',
        'icon' => $base.' width: 48px; height: 48px; border-radius: 50%; background: #25D366; color: #fff; gap: 0;',
        default => $base.' background: #25D366; color: #fff; padding: 15px 28px; border-radius: var(--gs-r-btn, 10px); font-size: 16px;',
    };
@endphp
@if ($href)
    <a href="{{ $href }}" target="_blank" rel="noopener"
       data-gs-track="whatsapp_click"
       data-gs-source="{{ $source }}"
       @if ($service) data-gs-service="{{ $service }}" @endif
       @if ($variant === 'icon') aria-label="{{ $caption }}" @endif
       {{ $attributes->merge(['style' => $style]) }}>
        <svg width="{{ $variant === 'icon' ? 24 : 19 }}" height="{{ $variant === 'icon' ? 24 : 19 }}"
             viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="flex-shrink: 0;"><path d="{{ $glyph }}"/></svg>
        @unless ($variant === 'icon'){{ $caption }}@endunless
    </a>
@endif
