@extends('admin.layout')

@section('title', 'Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card">
            <h2>Theme</h2>
            <div class="field">
                <label>Accent color</label>
                <div class="swatches">
                    @foreach($accents as $accent)
                        <label class="swatch" title="{{ $accent }}">
                            <input type="radio" name="theme_accent" value="{{ $accent }}"
                                   @checked(strtolower(old('theme_accent', gs_setting('theme.accent', '#2ca69c'))) === strtolower($accent))>
                            <span style="background: {{ $accent }}"></span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="field">
                <label>Mood</label>
                <div class="radio-row">
                    @foreach(['midnight', 'bright'] as $mood)
                        <label>
                            <input type="radio" name="theme_mood" value="{{ $mood }}"
                                   @checked(old('theme_mood', gs_setting('theme.mood', 'midnight')) === $mood)>
                            {{ ucfirst($mood) }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="field">
                <label>Shape</label>
                <div class="radio-row">
                    @foreach(['soft', 'sharp', 'round'] as $shape)
                        <label>
                            <input type="radio" name="theme_shape" value="{{ $shape }}"
                                   @checked(old('theme_shape', gs_setting('theme.shape', 'soft')) === $shape)>
                            {{ ucfirst($shape) }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Contact</h2>
            <div class="field">
                <label for="contact_phone">Phone</label>
                <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', gs_setting('contact.phone')) }}" required>
            </div>
            <div class="field">
                <label for="contact_email">Email</label>
                <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', gs_setting('contact.email')) }}" required>
            </div>
        </div>

        <div class="card">
            <h2>Social links</h2>
            @foreach(['facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'x' => 'X (Twitter)', 'youtube' => 'YouTube'] as $network => $label)
                <div class="field">
                    <label for="social_{{ $network }}">{{ $label }}</label>
                    <input type="text" id="social_{{ $network }}" name="social_{{ $network }}" value="{{ old('social_'.$network, gs_setting('social.'.$network)) }}">
                </div>
            @endforeach
        </div>

        <div class="card">
            <h2>Home page sections</h2>
            <p class="hint" style="margin:-6px 0 14px">Lower position renders first. Untick to hide a section.</p>
            @foreach($sections as $section)
                <div class="field" style="display:flex;gap:14px;align-items:center">
                    <input type="number" name="sections[{{ $section->key }}][position]" min="1" style="width:70px"
                           value="{{ old('sections.'.$section->key.'.position', $section->position) }}">
                    <label class="check" style="margin:0">
                        <input type="checkbox" name="sections[{{ $section->key }}][visible]" value="1"
                               @checked(old('sections.'.$section->key.'.visible', $section->visible))>
                        {{ $section->key }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn">Save settings</button>
    </form>
@endsection
