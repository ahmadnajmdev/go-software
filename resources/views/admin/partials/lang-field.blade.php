@php($v = is_array($item->{$field} ?? null) ? $item->{$field} : [])
<div class="field">
    <label>{{ $label }}</label>
    <div class="lang-tabs">
        <div>
            <span class="lang-label">EN</span>
            @if(($type ?? 'input') === 'textarea')
                <textarea name="{{ $field }}[en]" @if($required ?? false) required @endif>{{ old("$field.en", $v['en'] ?? '') }}</textarea>
            @else
                <input type="text" name="{{ $field }}[en]" value="{{ old("$field.en", $v['en'] ?? '') }}" @if($required ?? false) required @endif>
            @endif
        </div>
        <div>
            <span class="lang-label">العربية</span>
            @if(($type ?? 'input') === 'textarea')
                <textarea name="{{ $field }}[ar]" dir="rtl">{{ old("$field.ar", $v['ar'] ?? '') }}</textarea>
            @else
                <input type="text" name="{{ $field }}[ar]" dir="rtl" value="{{ old("$field.ar", $v['ar'] ?? '') }}">
            @endif
        </div>
        <div>
            <span class="lang-label">کوردی</span>
            @if(($type ?? 'input') === 'textarea')
                <textarea name="{{ $field }}[ckb]" dir="rtl">{{ old("$field.ckb", $v['ckb'] ?? '') }}</textarea>
            @else
                <input type="text" name="{{ $field }}[ckb]" dir="rtl" value="{{ old("$field.ckb", $v['ckb'] ?? '') }}">
            @endif
        </div>
    </div>
</div>
