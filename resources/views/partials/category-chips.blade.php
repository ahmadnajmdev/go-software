{{--
    Category filter chips. Alpine owns the selection; each project tile is
    shown or hidden by comparing its slug against `cat`, so filtering is
    instant and needs no request.

    Wrap both this and the grid in one x-data="{ cat: '…' }" scope.
    Params: $categories (Category collection), $align ('start'|'center')
--}}
@php($align = $align ?? 'start')
@if ($categories->isNotEmpty())
    <div class="gs-chips" style="justify-content: {{ $align === 'center' ? 'center' : 'flex-start' }};">
        <button type="button" class="gs-chip" :class="cat === 'all' && 'is-on'" @click="cat = 'all'">
            <x-t k="catAll"/>
        </button>
        @foreach ($categories as $category)
            <button type="button" class="gs-chip"
                    :class="cat === '{{ $category->slug }}' && 'is-on'"
                    @click="cat = '{{ $category->slug }}'">
                {{ $category->tr('name') }}
            </button>
        @endforeach
    </div>
@endif
