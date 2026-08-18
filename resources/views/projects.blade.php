@extends('layouts.app')

@section('title', t('metaProjectsTitle'))
@section('meta_description', t('metaProjectsDesc'))

@section('content')
    <section style="background: #f5f8f8; padding: 78px 0 94px;" x-data="{ cat: '{{ $selected }}' }">
        <div style="max-width: 1240px; margin: 0 auto; padding: 0 24px;">

            <div style="text-align: center; max-width: 640px; margin: 0 auto 30px;">
                <div style="display: inline-flex; align-items: center; gap: 11px; margin-bottom: 14px;">
                    <span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span>
                    <span style="color: var(--gs-accent, #2CA69C); font-family: 'Space Grotesk'; font-weight: 600; letter-spacing: .16em; font-size: 13px;"><x-t k="projTag"/></span>
                    <span style="width: 30px; height: 2px; background: var(--gs-accent, #2CA69C);"></span>
                </div>
                <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(32px, 4vw, 48px); line-height: 1.12; color: #0d1826; letter-spacing: -.02em;"><x-t k="projTitle"/></h1>
            </div>

            @include('partials.category-chips', ['categories' => $categories, 'align' => 'center'])

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;" class="gs-4col">
                @foreach ($projects as $project)
                    @include('partials.project-tile', ['project' => $project])
                @endforeach
            </div>

            {{-- Shown only when a filter leaves nothing behind. --}}
            <p x-show="!$el.parentElement.querySelector('.gs-proj:not([style*=\'display: none\'])')" x-cloak
               style="text-align: center; color: #6a7a8a; padding: 40px 0 0; font-size: 16px;">
                <x-t k="projNone"/>
            </p>

            <div style="text-align: center; margin-top: 46px;">
                <a href="{{ gs_route('') }}#contact" class="hov-accent-solid" style="background: #0d1826; color: #fff; font-family: 'Space Grotesk'; font-weight: 600; padding: 15px 30px; border-radius: var(--gs-r-btn, 10px); display: inline-flex; align-items: center; gap: 9px; transition: .25s;">
                    <x-t k="ctTag"/>
                    <svg class="gs-flip" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
