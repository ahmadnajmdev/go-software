{{--
    Project filters. Alpine owns the selection; each tile shows or hides by
    comparing its own slugs against `cat`, so filtering is instant.

    Two rows: industry first, because a buyer recognises "restaurants & cafés"
    long before they recognise "web app", and type second as a secondary cut.

    Wrap both this and the grid in one x-data="{ cat: '…' }" scope.
    Params: $industries, $types (Category collections), $align
--}}
@php($align = $align ?? 'start')
@php($justify = $align === 'center' ? 'center' : 'flex-start')
@if ($industries->isNotEmpty() || $types->isNotEmpty())
    <div class="gs-filters">
        <div class="gs-chips" style="justify-content: {{ $justify }};">
            <button type="button" class="gs-chip" :class="cat === 'all' && 'is-on'" @click="cat = 'all'">
                <x-t k="catAll"/>
            </button>
            @foreach ($industries as $industry)
                <button type="button" class="gs-chip"
                        :class="cat === '{{ $industry->slug }}' && 'is-on'"
                        @click="cat = '{{ $industry->slug }}'">
                    {{ $industry->tr('name') }}
                </button>
            @endforeach
        </div>

        @if ($types->isNotEmpty())
            <div class="gs-chips gs-chips-secondary" style="justify-content: {{ $justify }};">
                <span class="gs-chips-label"><x-t k="prjFilterType"/></span>
                @foreach ($types as $type)
                    <button type="button" class="gs-chip gs-chip-sm"
                            :class="cat === '{{ $type->slug }}' && 'is-on'"
                            @click="cat = '{{ $type->slug }}'">
                        {{ $type->tr('name') }}
                    </button>
                @endforeach
            </div>
        @endif
    </div>
@endif
