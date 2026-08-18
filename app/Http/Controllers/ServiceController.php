<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Support\ServiceCatalogue;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public function __invoke(string ...$segments)
    {
        // route params arrive as (locale, slug) or (slug) depending on the URL
        $slug = end($segments);

        abort_unless(ServiceCatalogue::has($slug), Response::HTTP_NOT_FOUND);

        return view('service', [
            'page' => ServiceCatalogue::page($slug),
            'service' => Service::where('slug', $slug)->first(),
            'others' => ServiceCatalogue::published()->where('slug', '!=', $slug)->take(3),
            'projects' => Project::with('category')->ordered()->take(2)->get(),
        ]);
    }
}
