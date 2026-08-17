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

            @php($address = is_array(gs_setting('contact.address')) ? gs_setting('contact.address') : [])
            <div class="field">
                <label>Address</label>
                <div class="lang-tabs">
                    <div>
                        <span class="lang-label">EN</span>
                        <input type="text" name="contact_address[en]" value="{{ old('contact_address.en', $address['en'] ?? '') }}">
                    </div>
                    <div>
                        <span class="lang-label">العربية</span>
                        <input type="text" name="contact_address[ar]" dir="rtl" value="{{ old('contact_address.ar', $address['ar'] ?? '') }}">
                    </div>
                    <div>
                        <span class="lang-label">کوردی</span>
                        <input type="text" name="contact_address[ckb]" dir="rtl" value="{{ old('contact_address.ckb', $address['ckb'] ?? '') }}">
                    </div>
                </div>
                <p class="hint">Shown in the Contact section. Leave a language blank to fall back to English.</p>
            </div>

            <div class="field">
                <label>Map location</label>
                <div class="lang-tabs">
                    <div>
                        <span class="lang-label">Latitude</span>
                        <input type="text" name="contact_map_lat" value="{{ old('contact_map_lat', gs_setting('contact.map_lat')) }}" placeholder="36.1821139">
                    </div>
                    <div>
                        <span class="lang-label">Longitude</span>
                        <input type="text" name="contact_map_lng" value="{{ old('contact_map_lng', gs_setting('contact.map_lng')) }}" placeholder="43.9785422">
                    </div>
                    <div>
                        <span class="lang-label">Zoom (3–21)</span>
                        <input type="number" name="contact_map_zoom" min="3" max="21" value="{{ old('contact_map_zoom', gs_setting('contact.map_zoom', 16)) }}">
                    </div>
                </div>
                <p class="hint">To get exact numbers: open <a href="https://www.google.com/maps" target="_blank" rel="noopener">Google Maps</a>, right-click your building → the first menu item is the lat/lng, click it to copy. Clear both and the map pins your address by name instead; clear the address too and the map is hidden.</p>
            </div>

            <div class="field">
                <label for="contact_map_embed">Google Maps embed <span style="font-weight:400;color:#66757f">(optional — overrides the coordinates)</span></label>
                <textarea id="contact_map_embed" name="contact_map_embed" rows="3" placeholder="&lt;iframe src=&quot;https://www.google.com/maps/embed?pb=…&quot;&gt;&lt;/iframe&gt;">{{ old('contact_map_embed', gs_setting('contact.map_embed')) }}</textarea>
                <p class="hint">In Google Maps: find your place → <strong>Share</strong> → <strong>Embed a map</strong> → Copy HTML, and paste it here. Paste the whole <code>&lt;iframe&gt;</code> or just the URL — either works. This pins the map to the exact business listing instead of a coordinate. Only google.com map links are accepted; anything else is ignored and the coordinates are used instead.</p>
            </div>
        </div>

        <div class="card">
            <h2>About</h2>
            <div class="field">
                <label for="about_ceo_name">CEO name</label>
                <input type="text" id="about_ceo_name" name="about_ceo_name" value="{{ old('about_ceo_name', gs_setting('about.ceo_name')) }}" required>
                <p class="hint">Appears in the About section and on the Founder photo badge. The job title next to it lives in <a href="{{ route('admin.strings.index') }}">UI Strings → About → ceoRole</a>.</p>
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
