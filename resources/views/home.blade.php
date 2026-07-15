@extends('layouts.app')

@section('content')
    @foreach ($sections as $section)
        @if ($section->visible || auth()->check())
            <div data-section="{{ $section->key }}" data-section-visible="{{ $section->visible ? '1' : '0' }}"
                 style="position: relative; transition: opacity .2s;{{ $section->visible ? '' : ' display: none;' }}">
                @auth
                    <div class="gs-section-chip" data-chip style="display: none; position: absolute; top: 14px; inset-inline-start: 14px; z-index: 45; align-items: center; gap: 7px; background: #0d1826; border: 1px solid rgba(255,255,255,.18); color: #fff; border-radius: 10px; padding: 6px 8px 6px 13px; box-shadow: 0 12px 30px rgba(13,24,38,.4); font-family: 'Space Grotesk';">
                        <span style="font-weight: 600; font-size: 12.5px;">{{ ucfirst($section->key) }}</span>
                        <button data-chip-up title="Move section up" style="border: none; background: rgba(255,255,255,.12); color: #fff; width: 26px; height: 26px; border-radius: 7px; cursor: pointer; font-size: 13px; line-height: 1;">↑</button>
                        <button data-chip-down title="Move section down" style="border: none; background: rgba(255,255,255,.12); color: #fff; width: 26px; height: 26px; border-radius: 7px; cursor: pointer; font-size: 13px; line-height: 1;">↓</button>
                        <button data-chip-toggle style="border: none; background: rgba(255,255,255,.12); color: #6FDED3; height: 26px; padding: 0 10px; border-radius: 7px; cursor: pointer; font-family: 'Space Grotesk'; font-weight: 600; font-size: 12px;">{{ $section->visible ? 'Hide' : 'Show' }}</button>
                    </div>
                @endauth
                @include('sections.'.$section->key)
            </div>
        @endif
    @endforeach
@endsection
