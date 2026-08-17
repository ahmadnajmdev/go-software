<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Support\Settings;
use App\Support\Theme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'sections' => Section::ordered()->get(),
            'accents' => Theme::ACCENTS,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'theme_accent' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_mood' => ['required', Rule::in(Theme::MOODS)],
            'theme_shape' => ['required', Rule::in(Theme::SHAPES)],
            'contact_phone' => ['required', 'string', 'max:40'],
            'contact_email' => ['required', 'email', 'max:190'],
            'contact_address.en' => ['nullable', 'string', 'max:300'],
            'contact_address.ar' => ['nullable', 'string', 'max:300'],
            'contact_address.ckb' => ['nullable', 'string', 'max:300'],
            'contact_map_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'contact_map_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'contact_map_zoom' => ['nullable', 'integer', 'between:3,19'],
            'about_ceo_name' => ['required', 'string', 'max:120'],
            'social_facebook' => ['nullable', 'string', 'max:300'],
            'social_linkedin' => ['nullable', 'string', 'max:300'],
            'social_x' => ['nullable', 'string', 'max:300'],
            'social_youtube' => ['nullable', 'string', 'max:300'],
            'sections' => ['required', 'array'],
            'sections.*.position' => ['required', 'integer', 'min:1'],
            'sections.*.visible' => ['nullable'],
        ]);

        Settings::set('theme.accent', $data['theme_accent']);
        Settings::set('theme.mood', $data['theme_mood']);
        Settings::set('theme.shape', $data['theme_shape']);
        Settings::set('contact.phone', $data['contact_phone']);
        Settings::set('contact.email', $data['contact_email']);
        Settings::set('about.ceo_name', $data['about_ceo_name']);

        // Address is per-locale; drop blank languages so the en fallback kicks in.
        Settings::set('contact.address', array_filter([
            'en' => trim($data['contact_address']['en'] ?? ''),
            'ar' => trim($data['contact_address']['ar'] ?? ''),
            'ckb' => trim($data['contact_address']['ckb'] ?? ''),
        ], fn ($v) => $v !== ''));

        Settings::set('contact.map_lat', $data['contact_map_lat'] ?? null);
        Settings::set('contact.map_lng', $data['contact_map_lng'] ?? null);
        Settings::set('contact.map_zoom', (int) ($data['contact_map_zoom'] ?? 16));

        foreach (['facebook', 'linkedin', 'x', 'youtube'] as $network) {
            Settings::set("social.{$network}", $data["social_{$network}"] ?? '#');
        }

        foreach (Section::all() as $section) {
            $input = $data['sections'][$section->key] ?? null;
            if ($input) {
                $section->update([
                    'position' => (int) $input['position'],
                    'visible' => filter_var($input['visible'] ?? false, FILTER_VALIDATE_BOOL),
                ]);
            }
        }

        return back()->with('ok', 'Settings saved.');
    }
}
