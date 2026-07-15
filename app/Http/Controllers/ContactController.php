<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $submission = ContactSubmission::create([
            ...$request->safe()->except('website'),
            'locale' => app()->getLocale(),
        ]);

        Log::info('New contact submission', ['id' => $submission->id, 'email' => $submission->email]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('contact_sent', true);
    }
}
