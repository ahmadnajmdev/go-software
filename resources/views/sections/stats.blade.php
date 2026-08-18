{{--
    The figures band.

    Renders nothing at all unless config/stats.php holds at least one real
    number. The site used to claim "300+ projects delivered", "180+ happy
    clients", "15+ years in software" and a "98% satisfaction rate", none of
    which could be sourced — and 300 projects sat two screens from 15 years,
    which reads as either 20 a year for fifteen years or as numbers nobody
    counted. Set a defensible figure and it comes back on its own.
--}}
@php($gsCounters = \App\Support\Stats::forCounters())
@if ($gsCounters)
<!-- ===== STATS ===== -->
<section style="background: var(--gs-accent, #2CA69C); color: #fff; padding: 54px 0;">
  <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: repeat({{ count($gsCounters) }}, 1fr); gap: 24px; text-align: center;" class="gs-4col">
    @foreach ($gsCounters as $stat)
      <div>
        <div class="gs-count" data-count="{{ $stat['count'] }}" data-suffix="{{ $stat['suffix'] }}" style="font-family: 'Space Grotesk'; font-weight: 700; font-size: 46px; line-height: 1;">0{{ $stat['suffix'] }}</div>
        <div style="margin-top: 6px; color: rgba(255,255,255,.9); font-weight: 500;"><x-t :k="$stat['label']"/></div>
      </div>
    @endforeach
  </div>
</section>
@endif
