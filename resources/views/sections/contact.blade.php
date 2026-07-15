<!-- ===== CONTACT ===== -->
<section id="contact" style="background: var(--gs-deep-bg, #0D1826); color: var(--gs-deep-fg, #FFFFFF); padding: 94px 0; position: relative; overflow: hidden;">
  <div style="position: absolute; inset: 0; background-image: linear-gradient(var(--gs-deep-grid, rgba(255,255,255,.025)) 1px, transparent 1px), linear-gradient(90deg, var(--gs-deep-grid, rgba(255,255,255,.025)) 1px, transparent 1px); background-size: 54px 54px; mask-image: radial-gradient(ellipse 60% 80% at 80% 50%, #000 30%, transparent 100%);"></div>
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; position: relative;" class="gs-2col">
    <div>
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 16px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent-lite, #6FDED3); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="ctTag"/></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; margin-bottom: 20px; letter-spacing: -.02em;"><x-t k="ctTitle"/></h2>
      <p style="font-size: 16px; line-height: 1.7; color: var(--gs-deep-muted, #A9B6C3); margin-bottom: 34px; max-width: 460px;"><x-t k="ctBody"/></p>
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="width: 52px; height: 52px; border-radius: var(--gs-r-tile, 12px); background: color-mix(in srgb, var(--gs-accent, #2CA69C) 16%, transparent); color: var(--gs-accent-lite, #6FDED3); display: grid; place-items: center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h3l2 5-2 1.5a11 11 0 005 5L15 13l5 2v3a2 2 0 01-2 2A15 15 0 013 6a2 2 0 012-2z"/></svg></div>
          <div><div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);"><x-t k="callUs"/></div><a href="tel:{{ gs_setting('contact.phone') }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; color: var(--gs-deep-fg, #FFFFFF);">{{ gs_setting('contact.phone') }}</a></div>
        </div>
        <div style="display: flex; align-items: center; gap: 16px;">
          <div style="width: 52px; height: 52px; border-radius: var(--gs-r-tile, 12px); background: color-mix(in srgb, var(--gs-accent, #2CA69C) 16%, transparent); color: var(--gs-accent-lite, #6FDED3); display: grid; place-items: center;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/></svg></div>
          <div><div style="font-size: 13px; color: var(--gs-deep-muted, #A3B0BD);"><x-t k="emailUs"/></div><a href="mailto:{{ gs_setting('contact.email') }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; color: var(--gs-deep-fg, #FFFFFF);">{{ gs_setting('contact.email') }}</a></div>
        </div>

      </div>
    </div>
    <div x-data="contactForm()" style="background: #fff; border-radius: var(--gs-r-card, 20px); padding: 38px; color: #0d1826;">
      <h3 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 24px; margin-bottom: 6px;"><x-t k="formTitle"/></h3>
      <p style="font-size: 14px; color: #6a7a8a; margin-bottom: 24px;"><x-t k="formSub"/></p>
      <div x-show="submitted" x-cloak>
        <div style="background: #eefaf8; border: 1px solid #b8e6e0; border-radius: var(--gs-r-tile, 14px); padding: 30px; text-align: center;">
          <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--gs-accent, #2CA69C); display: grid; place-items: center; margin: 0 auto;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></div>
          <h4 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px; margin: 12px 0 6px; color: #0d1826;"><x-t k="thanksT"/></h4>
          <p style="font-size: 14px; color: #4a5a6a;"><x-t k="thanksB"/></p>
        </div>
      </div>
      <div x-show="!submitted">
        <form action="{{ route('contact.store') }}" method="POST" @submit.prevent="submit($event)" style="display: flex; flex-direction: column; gap: 14px;">
          @csrf
          <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">
          <input required name="name" placeholder="{{ t('phName') }}" class="foc-accent" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
          <input required type="email" name="email" placeholder="{{ t('phEmail') }}" class="foc-accent" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
          <input name="phone" placeholder="{{ t('phPhone') }}" class="foc-accent" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none;">
          <select required name="service" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; color: #4a5a6a; background: #fff;">
            <option value="">{{ t('optSelect') }}</option>
            <option value="web">{{ t('webDev') }}</option>
            <option value="webapp">{{ t('optWebApp') }}</option>
            <option value="mobile">{{ t('svc3T') }}</option>
            <option value="system">{{ t('optSystem') }}</option>
            <option value="other">{{ t('optOther') }}</option>
          </select>
          <textarea name="message" placeholder="{{ t('phMsg') }}" rows="4" class="foc-accent" style="width: 100%; padding: 14px 16px; border: 1px solid #e2e8e8; border-radius: var(--gs-r-btn, 10px); font-family: 'DM Sans'; font-size: 15px; outline: none; resize: vertical;"></textarea>
          <button type="submit" class="hov-dark" style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; font-size: 16px; padding: 16px; border: none; border-radius: var(--gs-r-btn, 10px); cursor: pointer; transition: .2s; display: inline-flex; align-items: center; justify-content: center; gap: 9px;"><x-t k="sendMsg"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
        </form>
      </div>
    </div>
  </div>
</section>
