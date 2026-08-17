<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'sections' => Section::ordered()->get(),
            'services' => Service::ordered()->get(),
            'projects' => Project::with('category')->ordered()->get(),
            'categories' => Category::ordered()->get(),
            'testimonials' => Testimonial::ordered()->get(),
            'clients' => Client::ordered()->get(),
        ]);
    }
}
