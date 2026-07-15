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
