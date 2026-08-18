<!-- ===== STATS ===== -->
<section style="background: var(--gs-accent, #2CA69C); color: #fff; padding: 54px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; text-align: center;" class="gs-4col">
    {{-- Figures come from config/stats.php so the site states each one in
         exactly one place. See BLOCKED.md — they are not yet verified. --}}
    @foreach (\App\Support\Stats::forCounters() as $i => $stat)
      <div><div class="gs-count" data-count="{{ $stat['count'] }}" data-suffix="{{ $stat['suffix'] }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 46px; line-height: 1;">0{{ $stat['suffix'] }}</div><div style="margin-top: 6px; color: rgba(255,255,255,.9); font-weight: 500;"><x-t :k="'st'.($i + 1)"/></div></div>
    @endforeach
  </div>
</section>
