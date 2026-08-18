@extends('layouts.app')

@section('title', $doc['title'].' — GoSoftware')
@section('meta_description', $doc['intro'])

@section('content')
<section style="background: #f5f8f8; padding: 70px 0 90px;">
  <div style="max-width: 780px; margin: 0 auto; padding: 0 24px;">

    <h1 style="font-family: 'Space Grotesk'; font-weight: 700; font-size: clamp(30px, 4.4vw, 46px); line-height: 1.14; color: #0d1826; letter-spacing: -.02em; margin-bottom: 14px;">{{ $doc['title'] }}</h1>

    <p style="font-size: 13px; color: #7d8b99; margin-bottom: 26px;">
      <x-t k="lastUpdated"/> {{ gs_date(\Carbon\Carbon::parse($updated)) }}
    </p>

    <p style="font-size: 17px; line-height: 1.75; color: #48586a; margin-bottom: 40px;">{{ $doc['intro'] }}</p>

    @foreach ($doc['sections'] as $i => $section)
      <section style="margin-bottom: 34px;">
        <h2 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 21px; color: #0d1826; margin-bottom: 12px;">
          <span style="color: var(--gs-accent, #2CA69C); margin-inline-end: 8px;">{{ sprintf('%02d', $i + 1) }}</span>{{ $section['heading'] }}
        </h2>
        @foreach ($section['body'] as $paragraph)
          <p style="font-size: 16px; line-height: 1.78; color: #52616f; margin-bottom: 12px;">{{ $paragraph }}</p>
        @endforeach
      </section>
    @endforeach

    <div style="margin-top: 46px; padding: 26px 28px; background: #fff; border: 1px solid #e4ebeb; border-radius: var(--gs-r-card, 18px);">
      <h2 style="font-family: 'Space Grotesk'; font-weight: 600; font-size: 19px; color: #0d1826; margin-bottom: 12px;"><x-t k="legalContact"/></h2>
      <p style="font-size: 16px; line-height: 1.8; color: #52616f;">
        <a href="mailto:{{ gs_setting('contact.email') }}" class="hov-accent-text" style="color: var(--gs-accent, #2CA69C); font-weight: 600;">{{ gs_setting('contact.email') }}</a><br>
        <a href="tel:{{ gs_setting('contact.phone') }}" class="hov-accent-text" style="color: #52616f;">{{ gs_setting('contact.phone') }}</a><br>
        {{ gs_setting_tr('contact.address') }}
      </p>
    </div>

  </div>
</section>
@endsection
