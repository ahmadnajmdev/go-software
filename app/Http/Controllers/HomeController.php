<?php

namespace App\Http\Controllers;

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
            'projects' => Project::ordered()->get(),
            'testimonials' => Testimonial::ordered()->get(),
        ]);
    }
}
