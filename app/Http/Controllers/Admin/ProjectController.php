<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Project;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['items' => Project::with('category')->ordered()->get()]);
    }

    public function create()
    {
        return view('admin.projects.form', [
            'item' => new Project(),
            'media' => $this->library(),
            'categories' => Category::ordered()->types()->get(),
            'industries' => Category::ordered()->industries()->get(),
        ]);
    }

    public function store(Request $request)
    {
        Project::create($this->data($request) + ['position' => Project::max('position') + 1]);

        return redirect()->route('admin.projects.index')->with('ok', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', [
            'item' => $project,
            'media' => $this->library(),
            'categories' => Category::ordered()->types()->get(),
            'industries' => Category::ordered()->industries()->get(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($this->data($request));

        return redirect()->route('admin.projects.index')->with('ok', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('ok', 'Project deleted.');
    }

    private function data(Request $request): array
    {
        $data = $request->validate([
            'image' => ['nullable', 'string', 'max:500'],
            'image_file' => ImageUpload::RULES,
            'fit' => ['nullable', 'in:cover,contain'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'industry_id' => ['nullable', 'exists:categories,id'],
            'url' => ['nullable', 'url', 'max:500'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'title.ckb' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('projects', 'slug')->ignore($request->route('project'))],
            'client' => ['nullable', 'string', 'max:160'],
            'technology' => ['nullable', 'string', 'max:300'],
            'platforms' => ['nullable', 'string', 'max:160'],
            'timeline' => ['nullable', 'string', 'max:80'],
            'live_since' => ['nullable', 'string', 'max:80'],
            'quote_author' => ['nullable', 'string', 'max:120'],
            'quote_role' => ['nullable', 'string', 'max:160'],
            'screenshots' => ['nullable', 'string', 'max:3000'],
            ...self::translatableRules(['outcome' => 300, 'problem' => 3000, 'solution' => 3000, 'result' => 2000, 'quote' => 1000]),
        ]);

        // An uploaded file wins over whatever the path field holds.
        if ($request->hasFile('image_file')) {
            $data['image'] = ImageUpload::store($request->file('image_file'))->path;
        }

        $data['fit'] = $data['fit'] ?? 'cover';

        // One screenshot path per line.
        if (array_key_exists('screenshots', $data)) {
            $data['screenshots'] = array_values(array_filter(array_map(
                'trim', preg_split('/\R/', (string) $data['screenshots'])
            ))) ?: null;
        }

        // A slug is only generated when the project actually needs a page.
        if (blank($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($data['title']['en'] ?? '') ?: null;
        }

        // Drop empty locales so tr() falls back to English rather than "".
        foreach (['outcome', 'problem', 'solution', 'result', 'quote'] as $field) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $data[$field] = array_filter(array_map('trim', (array) $data[$field]), 'strlen') ?: null;
        }

        unset($data['image_file']);

        return $data;
    }

    /** @param array<string, int> $fields field => max length */
    private static function translatableRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field => $max) {
            foreach (['en', 'ar', 'ckb'] as $locale) {
                $rules["{$field}.{$locale}"] = ['nullable', 'string', "max:{$max}"];
            }
        }

        return $rules;
    }

    private function library()
    {
        return Media::latest()->take(24)->get();
    }
}
