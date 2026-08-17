<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Project;
use App\Support\ImageUpload;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['items' => Project::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.projects.form', ['item' => new Project(), 'media' => $this->library()]);
    }

    public function store(Request $request)
    {
        Project::create($this->data($request) + ['position' => Project::max('position') + 1]);

        return redirect()->route('admin.projects.index')->with('ok', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['item' => $project, 'media' => $this->library()]);
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
            'category.en' => ['required', 'string', 'max:120'],
            'category.ar' => ['nullable', 'string', 'max:120'],
            'category.ckb' => ['nullable', 'string', 'max:120'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'title.ckb' => ['nullable', 'string', 'max:255'],
        ]);

        // An uploaded file wins over whatever the path field holds.
        if ($request->hasFile('image_file')) {
            $data['image'] = ImageUpload::store($request->file('image_file'))->path;
        }

        unset($data['image_file']);

        return $data;
    }

    private function library()
    {
        return Media::latest()->take(24)->get();
    }
}
