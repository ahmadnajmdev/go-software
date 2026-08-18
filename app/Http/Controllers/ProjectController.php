<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function __invoke(string ...$segments)
    {
        $slug = end($segments);

        $project = Project::with(['category', 'industry'])->where('slug', $slug)->first();

        abort_unless($project && $project->hasStory(), Response::HTTP_NOT_FOUND);

        // Same industry first, then anything else — a visitor who came for a
        // restaurant wants to see other restaurants.
        $related = Project::with(['category', 'industry'])
            ->where('id', '!=', $project->id)
            ->orderByRaw('industry_id = ? DESC', [$project->industry_id])
            ->ordered()
            ->take(3)
            ->get();

        return view('project', compact('project', 'related'));
    }
}
