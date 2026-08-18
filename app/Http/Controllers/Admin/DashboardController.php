<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Support\Analytics;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const RANGES = [7 => 'Last 7 days', 30 => 'Last 30 days', 90 => 'Last 90 days'];

    public function __invoke(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $days = array_key_exists($days, self::RANGES) ? $days : 30;

        $now = Analytics::forDays($days);
        $before = $now->previous();

        return view('admin.dashboard', [
            'days' => $days,
            'ranges' => self::RANGES,
            'analytics' => $now,
            'headline' => [
                ['label' => 'Page views', 'value' => $now->pageViews(), 'was' => $before->pageViews()],
                ['label' => 'Visitors', 'value' => $now->visitors(), 'was' => $before->visitors()],
                ['label' => 'Enquiries', 'value' => $now->enquiries(), 'was' => $before->enquiries()],
                ['label' => 'Conversion', 'value' => $now->conversionRate(), 'was' => $before->conversionRate(), 'suffix' => '%'],
            ],
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
