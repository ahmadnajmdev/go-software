{{--
    One pass of the client marquee. Rendered twice inside the track so the
    scroll loops seamlessly — the second copy is aria-hidden and unfocusable.

    Params: $clients (Client collection), $duplicate (bool)
--}}
@php($duplicate = $duplicate ?? false)
<span style="display:flex; gap:60px; align-items:center;" @if($duplicate) aria-hidden="true" @endif>
    @foreach ($clients as $client)
        @php($tag = $client->url && ! $duplicate ? 'a' : 'span')
        <{{ $tag }} class="gs-client"
            @if($tag === 'a') href="{{ $client->url }}" target="_blank" rel="noopener" @endif
            style="display:flex; align-items:center; flex-shrink:0;">
            @if ($client->logo)
                <img src="{{ media_url($client->logo) }}" alt="{{ $duplicate ? '' : $client->name }}" loading="lazy"
                     style="height:44px; width:auto; max-width:170px; object-fit:contain; display:block;">
            @else
                <span style="font-family:'Space Grotesk'; font-weight:700; font-size:25px; color:#0d1826; white-space:nowrap;">{{ $client->name }}</span>
            @endif
        </{{ $tag }}>
    @endforeach
</span>
