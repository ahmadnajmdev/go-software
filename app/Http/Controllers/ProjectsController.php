<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categories = Category::ordered()->get();
        $industries = $categories->where('kind', 'industry')->values();
        $types = $categories->where('kind', 'type')->values();

        // ?category=slug pre-selects a chip, so the page is linkable per
        // industry as well as per type — the industry router depends on it.
        $selected = $request->query('category');

        return view('projects', [
            'projects' => Project::with(['category', 'industry'])->ordered()->get(),
            'industries' => $industries,
            'types' => $types,
            'selected' => $categories->contains('slug', $selected) ? $selected : 'all',
        ]);
    }
}
