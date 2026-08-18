{{-- A ranked list with the bar drawn behind the label.
     Params: $rows (label => count), $empty (string), optional $format --}}
@php($max = collect($rows)->max() ?: 1)
@if (count($rows))
    <div class="gs-bars">
        @foreach ($rows as $label => $count)
            <div class="gs-bar-row">
                <div class="t" style="--w: {{ round($count / $max * 100) }}%">
                    <span>{{ isset($format) ? $format($label) : $label }}</span>
                </div>
                <div class="n">{{ $count }}</div>
            </div>
        @endforeach
    </div>
@else
    <p class="gs-empty">{{ $empty }}</p>
@endif
