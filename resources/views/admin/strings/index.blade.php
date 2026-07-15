@extends('admin.layout')

@section('title', 'UI Strings')

@section('header-actions')
    <form method="POST" action="{{ route('admin.strings.reset') }}" onsubmit="return confirm('Reset ALL strings to design defaults');">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">Reset all to defaults</button>
    </form>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.strings.update') }}">
        @csrf
        @method('PUT')

        <div class="sticky-save">
            <button type="submit" class="btn">Save all strings</button>
            <span class="hint" style="margin:0">Empty inputs keep the previous value.</span>
        </div>

        @foreach($groups as $group => $strings)
            <details class="card" @if($loop->first) open @endif>
                <summary>{{ $group ?: 'general' }} ({{ $strings->count() }})</summary>
                @foreach($strings as $string)
                    <div class="field">
                        <label><code>{{ $string->key }}</code></label>
                        <div class="lang-tabs">
                            <div>
                                <span class="lang-label">EN</span>
                                <input type="text" name="strings[{{ $string->key }}][en]" value="{{ old('strings.'.$string->key.'.en', $string->value['en'] ?? '') }}">
                            </div>
                            <div>
                                <span class="lang-label">العربية</span>
                                <input type="text" name="strings[{{ $string->key }}][ar]" dir="rtl" value="{{ old('strings.'.$string->key.'.ar', $string->value['ar'] ?? '') }}">
                            </div>
                            <div>
                                <span class="lang-label">کوردی</span>
                                <input type="text" name="strings[{{ $string->key }}][ckb]" dir="rtl" value="{{ old('strings.'.$string->key.'.ckb', $string->value['ckb'] ?? '') }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </details>
        @endforeach
    </form>
@endsection
