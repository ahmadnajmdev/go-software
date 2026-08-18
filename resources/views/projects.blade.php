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

            @include('partials.category-chips', ['industries' => $industries, 'types' => $types, 'align' => 'center'])

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

            <div style="display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; margin-top: 46px;">
<x-cta-primary location="projects_footer"/>
                <x-whatsapp-cta source="hero" variant="light"/>
            </div>
        </div>
    </section>
@endsection
