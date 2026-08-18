<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Support\MapEmbed;
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
            'contact_map_zoom' => ['nullable', 'integer', 'between:3,21'],
            'contact_map_embed' => [
                'nullable', 'string', 'max:2000',
                // reject a bad paste loudly rather than silently ignoring it
                function ($attribute, $value, $fail) {
                    if (filled($value) && ! MapEmbed::custom($value)) {
                        $fail('The map embed must be a Google Maps link — paste the '
                            .'<iframe> from Share → Embed a map, or its https://www.google.com/maps/… URL.');
                    }
                },
            ],
            'about_ceo_name' => ['required', 'string', 'max:120'],
            // A blank field means "no such channel" and renders nothing; a
            // filled one must be a real URL, never a "#" placeholder.
            ...self::socialRules(),
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
        // store the extracted src, not the pasted markup
        Settings::set('contact.map_embed', MapEmbed::custom($data['contact_map_embed'] ?? null));

        // Blank stores null, not "#": Social::company() then omits the icon
        // instead of rendering a dead link.
        foreach (self::socialFields() as $field => $setting) {
            $url = trim((string) ($data[$field] ?? ''));
            Settings::set($setting, $url !== '' ? $url : null);
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

    /** form field => setting key, for both the company and founder profiles */
    public static function socialFields(): array
    {
        $fields = [];

        foreach (array_keys(config('social.networks', [])) as $network) {
            $fields["social_{$network}"] = "social.{$network}";
        }

        foreach (array_keys(config('social.founder_networks', [])) as $network) {
            $fields["founder_{$network}"] = "founder.{$network}";
        }

        return $fields;
    }

    private static function socialRules(): array
    {
        return array_fill_keys(
            array_keys(self::socialFields()),
            ['nullable', 'url:http,https', 'max:300'],
        );
    }
}
