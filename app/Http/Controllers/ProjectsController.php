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

        // ?category=slug pre-selects a chip so the page is linkable per category
        $selected = $request->query('category');

        return view('projects', [
            'projects' => Project::with('category')->ordered()->get(),
            'categories' => $categories,
            'selected' => $categories->contains('slug', $selected) ? $selected : 'all',
        ]);
    }
}
