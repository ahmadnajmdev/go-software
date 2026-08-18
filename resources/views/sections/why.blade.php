<!-- ===== WHY CHOOSE US ===== -->
<section style="background: #fff; padding: 94px 0 56px;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;" class="gs-2col">
    <div>
      <div style="display: flex; align-items: center; gap: 11px; margin-bottom: 16px;"><span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span><span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="whyTag"/></span></div>
      <h2 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 3.6vw, 45px); line-height: 1.14; color: #0d1826; margin-bottom: 26px; letter-spacing: -.02em;"><x-t k="whyTitle"/></h2>
      <div style="display: flex; flex-direction: column; gap: 18px; margin-bottom: 30px;">
        <div style="display: flex; gap: 18px; align-items: flex-start; background: #f5f8f8; border-radius: var(--gs-r-tile, 14px); padding: 22px;">
          <div style="width: 50px; height: 50px; border-radius: var(--gs-r-tile, 12px); background: var(--gs-accent, #2CA69C); color: #fff; display: grid; place-items: center; flex-shrink: 0;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 4 14 11 14 10 22 20 9 13 9 13 2"/></svg></div>
          <div><h4 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 18px; color: #0d1826; margin-bottom: 4px;"><x-t k="why1T"/></h4><p style="font-size: 14px; color: #6a7a8a; line-height: 1.6;"><x-t k="why1D"/></p></div>
        </div>
        <div style="display: flex; gap: 18px; align-items: flex-start; background: #f5f8f8; border-radius: var(--gs-r-tile, 14px); padding: 22px;">
          <div style="width: 50px; height: 50px; border-radius: var(--gs-r-tile, 12px); background: #0d1826; color: #fff; display: grid; place-items: center; flex-shrink: 0;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7-4.7-7-10a4 4 0 017-2.6A4 4 0 0119 10c0 5.3-7 10-7 10z"/></svg></div>
          <div><h4 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 18px; color: #0d1826; margin-bottom: 4px;"><x-t k="why2T"/></h4><p style="font-size: 14px; color: #6a7a8a; line-height: 1.6;"><x-t k="why2D"/></p></div>
        </div>
      </div>
      <a href="{{ route('home') }}#about" class="hov-dark" style="background: var(--gs-accent, #2CA69C); color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 14px 28px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;"><x-t k="whyCta"/> <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
    </div>
    <div style="position: relative;">
      <div style="border-radius: var(--gs-r-card, 18px); overflow: hidden;">
        <x-photo setting="images.why" alt="GoSoftware engineers in Erbil" :height="460"/>
      </div>
      @if (\App\Support\Stats::hasAward())
        {{-- Rendered only when config/stats.php names who gave the award. An
             unsourced award claim costs more trust than it earns. --}}
<div style="position: absolute; bottom: -24px; left: -18px; background: #0d1826; color: #fff; border-radius: var(--gs-r-tile, 14px); padding: 22px 24px; box-shadow: 0 24px 50px rgba(13,24,38,.3); display: flex; align-items: center; gap: 14px;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="#f5b301"><path d="M12 2l2.9 6.3 6.6.7-4.9 4.5 1.3 6.5L12 17.8 6.1 20.5l1.3-6.5L2.5 9l6.6-.7L12 2z"/></svg>
        <div><div style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 20px;"><x-t k="topRated"/></div><div style="font-size: 12.5px; color: #a3b0bd;"><x-t k="agency2025"/> — {{ \App\Support\Stats::awardedBy() }}</div></div>
      </div>
      @endif
    </div>
  </div>
  <!-- marquee -->
  <div style="margin-top: 60px; border-top: 1px solid #eef1f2; border-bottom: 1px solid #eef1f2; padding: 22px 0; overflow: hidden;">
    <div class="gs-marquee-track" style="display: flex; width: max-content; gap: 0; animation: gsMarquee 26s linear infinite; font-family: 'Space Grotesk'; font-weight: 600; font-size: 24px; color: #0d1826;">
      <span style="display:flex; align-items:center; gap:44px; padding-right:44px;"><span><x-t k="mq1"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mobileApps"/></span><span style="color:#cfd8d8;">•</span><span><x-t k="mq3"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mgmtSystems"/></span><span style="color:#cfd8d8;">•</span><span><x-t k="mq5"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mq6"/></span><span style="color:#cfd8d8;">•</span></span>
      <span style="display:flex; align-items:center; gap:44px; padding-right:44px;" aria-hidden="true"><span><x-t k="mq1"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mobileApps"/></span><span style="color:#cfd8d8;">•</span><span><x-t k="mq3"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mgmtSystems"/></span><span style="color:#cfd8d8;">•</span><span><x-t k="mq5"/></span><span style="color:#cfd8d8;">•</span><span style="color:var(--gs-accent, #2CA69C);"><x-t k="mq6"/></span><span style="color:#cfd8d8;">•</span></span>
    </div>
  </div>
</section>
