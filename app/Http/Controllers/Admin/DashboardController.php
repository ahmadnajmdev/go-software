<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'services' => Service::count(),
                'projects' => Project::count(),
                'testimonials' => Testimonial::count(),
                'submissions' => ContactSubmission::count(),
            ],
            'unread' => ContactSubmission::unread()->count(),
            'latest' => ContactSubmission::latest()->take(6)->get(),
        ]);
    }
}
