{{--
    Sticky mobile action bar. Phones only (<768px), revealed after 20% scroll
    so it never covers the hero on arrival.

    WhatsApp · Call · Get estimate — the three things a phone visitor from
    Instagram actually wants, none of which were reachable without scrolling
    to the bottom of the page.
--}}
@php
    $phone = gs_setting('contact.phone');
    $hasWhatsapp = \App\Support\WhatsApp::isConfigured();
@endphp
@if ($phone || $hasWhatsapp)
<div class="gs-sticky" id="gs-sticky">
  @if ($hasWhatsapp)
    <a href="{{ \App\Support\WhatsApp::url(t('waMsgHero')) }}" target="_blank" rel="noopener"
       class="gs-sticky-item is-wa"
       data-gs-track="whatsapp_click" data-gs-source="sticky_bar">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 004.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.15h-.01a8.23 8.23 0 01-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.23 8.25-8.23 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.82c0 4.54-3.7 8.23-8.24 8.23z"/></svg>
      <span>WhatsApp</span>
    </a>
  @endif
  @if ($phone)
    <a href="tel:{{ $phone }}" class="gs-sticky-item">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h3l2 5-2 1.5a11 11 0 005 5L15 13l5 2v3a2 2 0 01-2 2A15 15 0 013 6a2 2 0 012-2z"/></svg>
      <span><x-t k="barCall"/></span>
    </a>
  @endif
  <a href="{{ route('home') }}#contact" class="gs-sticky-item is-cta"
     data-gs-track="cta_click" data-gs-label="{{ t('barEstimate', 'en') }}" data-gs-location="sticky_bar">
    <svg class="gs-flip" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    <span><x-t k="barEstimate"/></span>
  </a>
</div>
@endif
