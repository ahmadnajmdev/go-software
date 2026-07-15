<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['items' => Project::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.projects.form', ['item' => new Project()]);
    }

    public function store(Request $request)
    {
        Project::create($this->data($request) + ['position' => Project::max('position') + 1]);

        return redirect()->route('admin.projects.index')->with('ok', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['item' => $project]);
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
        return $request->validate([
            'image' => ['nullable', 'string', 'max:500'],
            'category.en' => ['required', 'string', 'max:120'],
            'category.ar' => ['nullable', 'string', 'max:120'],
            'category.ckb' => ['nullable', 'string', 'max:120'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'title.ckb' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
