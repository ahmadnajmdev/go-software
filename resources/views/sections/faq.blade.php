{{--
    Objection handling. None of the questions a real buyer holds — price,
    timescale, language, ownership, what happens when it breaks — was answered
    anywhere on the site, so every one of them had to be asked by email before
    anyone could decide.

    Native <details>/<summary>: keyboard-accessible and screen-reader-correct
    with no JavaScript, and it still works if the JS fails to load. analytics.js
    picks up the toggle and fires faq_open with the question.
--}}
@php($faqs = (require resource_path('faq.php'))[app()->getLocale()] ?? (require resource_path('faq.php'))['en'])
<!-- ===== FAQ ===== -->
<section id="faq" style="background: #fff; padding: 80px 0;">
  <div style="max-width: 820px; margin: 0 auto; padding: 0 24px;">
    <div style="text-align: center; margin-bottom: 34px;">
      <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="faqTag"/></span><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(28px, 3.4vw, 42px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em;"><x-t k="faqTitle"/></h2>
    </div>

    @foreach ($faqs as $faq)
      <details class="gs-faq" data-gs-question="{{ $faq['q'] }}">
        <summary>{{ $faq['q'] }}<span class="gs-faq-mark" aria-hidden="true"></span></summary>
        <div class="gs-faq-body">{{ $faq['a'] }}</div>
      </details>
    @endforeach

    <div style="margin-top: 32px; display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap;">
      <span style="font-size: 15.5px; color: #5a6a79;"><x-t k="faqStill"/></span>
      <x-whatsapp-cta source="contact" variant="link" :label="t('waAsk')"/>
    </div>
  </div>
</section>

@include('partials.faq-schema', ['faqEntries' => $faqs])
