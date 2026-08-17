<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\UiString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class InlineEditController extends Controller
{
    /** Hard whitelist: inline-editable models and their editable fields. */
    private const MODELS = [
        'services' => [Service::class, ['tag', 'title', 'description'], ['image']],
        'projects' => [Project::class, ['title'], ['image']],
        'testimonials' => [Testimonial::class, ['author', 'role', 'quote'], ['avatar']],
    ];

    /** Settings keys whose image may be swapped from inline edit mode. */
    private const SETTING_IMAGES = [
        'images.hero', 'images.about_main', 'images.about_inset', 'images.ceo',
        'images.founder', 'images.why', 'logo.dark', 'logo.light',
    ];

    private const LOCALES = ['en', 'ar', 'ckb'];

    public function text(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['string', 'model'])],
            'locale' => ['required', Rule::in(self::LOCALES)],
            'value' => ['present', 'string', 'max:5000'],
            'key' => ['required_if:type,string', 'string'],
            'model' => ['required_if:type,model', Rule::in(array_keys(self::MODELS))],
            'id' => ['required_if:type,model', 'integer'],
            'field' => ['required_if:type,model', 'string'],
        ]);

        $value = trim(strip_tags($data['value']));

        if ($data['type'] === 'string') {
            $string = UiString::where('key', $data['key'])->firstOrFail();
            $current = $string->value;

            if ($value === '') {
                unset($current[$data['locale']]); // revert to fallback
            } else {
                $current[$data['locale']] = $value;
            }

            $string->update(['value' => $current]);
            Cache::forget('gs.strings');

            return response()->json(['ok' => true]);
        }

        [$class, $translatable, ] = self::MODELS[$data['model']];
        $item = $class::findOrFail($data['id']);

        abort_unless(in_array($data['field'], $translatable), 422, 'Field not editable.');

        $field = $data['field'];

        if (is_array($item->{$field})) {
            $current = $item->{$field};
            if ($value === '') {
                unset($current[$data['locale']]);
            } else {
                $current[$data['locale']] = $value;
            }
            $item->update([$field => $current]);
        } else {
            $item->update([$field => $value]); // untranslated scalar (tag, name, author)
        }

        return response()->json(['ok' => true]);
    }

    public function image(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['model', 'setting'])],
            'value' => ['required', 'string', 'max:500'],
            'key' => ['required_if:type,setting', Rule::in(self::SETTING_IMAGES)],
            'model' => ['required_if:type,model', Rule::in(array_keys(self::MODELS))],
            'id' => ['required_if:type,model', 'integer'],
            'field' => ['required_if:type,model', 'string'],
        ]);

        abort_unless(
            str_starts_with($data['value'], 'uploads/') || preg_match('#^https://[\w./?&=%~-]+$#', $data['value']),
            422, 'Invalid image reference.'
        );

        if ($data['type'] === 'setting') {
            \App\Support\Settings::set($data['key'], $data['value']);

            return response()->json(['ok' => true]);
        }

        [$class, , $imageFields] = self::MODELS[$data['model']];

        abort_unless(in_array($data['field'], $imageFields), 422, 'Field not editable.');

        $class::findOrFail($data['id'])->update([$data['field'] => $data['value']]);

        return response()->json(['ok' => true]);
    }

    public function sections(Request $request)
    {
        $data = $request->validate([
            'key' => ['required', 'string', 'exists:sections,key'],
            'action' => ['required', Rule::in(['up', 'down', 'toggle'])],
        ]);

        $section = Section::where('key', $data['key'])->firstOrFail();

        if ($data['action'] === 'toggle') {
            $section->update(['visible' => ! $section->visible]);

            return response()->json(['ok' => true, 'visible' => $section->visible]);
        }

        $swapWith = $data['action'] === 'up'
            ? Section::where('position', '<', $section->position)->orderByDesc('position')->first()
            : Section::where('position', '>', $section->position)->orderBy('position')->first();

        if ($swapWith) {
            [$a, $b] = [$section->position, $swapWith->position];
            $section->update(['position' => $b]);
            $swapWith->update(['position' => $a]);
        }

        return response()->json(['ok' => true]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'model' => ['required', Rule::in(['services', 'projects', 'testimonials'])],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        [$class, , ] = self::MODELS[$data['model']];

        foreach (array_values($data['ids']) as $index => $id) {
            $class::whereKey($id)->update(['position' => $index + 1]);
        }

        return response()->json(['ok' => true]);
    }

    public function storeItem(Request $request)
    {
        $data = $request->validate(['model' => ['required', Rule::in(['services', 'projects'])]]);

        if ($data['model'] === 'services') {
            Service::create([
                'position' => Service::max('position') + 1,
                'tag' => 'NEW',
                'title' => ['en' => 'New service'],
                'description' => ['en' => 'Describe this service.'],
            ]);
        } else {
            Project::create([
                'position' => Project::max('position') + 1,
                'title' => ['en' => 'New project'],
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroyItem(Request $request)
    {
        $data = $request->validate([
            'model' => ['required', Rule::in(['services', 'projects'])],
            'id' => ['required', 'integer'],
        ]);

        [$class, , ] = self::MODELS[$data['model']];
        $class::findOrFail($data['id'])->delete();

        return response()->json(['ok' => true]);
    }
}
