<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CaptureAttribution;
use App\Http\Requests\StoreContactRequest;
use App\Models\ContactSubmission;
use App\Models\User;
use App\Notifications\NewContactSubmission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        $submission = ContactSubmission::create([
            ...$request->safe()->except('website'),
            ...$request->session()->get(CaptureAttribution::KEY, []),
            'source' => $request->safe()->string('source')->toString()
                ?: $this->pageFromReferer($request->headers->get('referer')),
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
        ]);

        // The lead is safe in the database before anyone is told about it, so a
        // mail failure can never lose one.
        $this->notifyTeam($submission);

        Log::info('New contact submission', ['id' => $submission->id, 'email' => $submission->email]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('contact_sent', true);
    }

    /** Which page the form was submitted from, e.g. "/ckb" or "/projects". */
    private function pageFromReferer(?string $referer): string
    {
        $path = (string) parse_url((string) $referer, PHP_URL_PATH);

        return Str::limit($path ?: '/', 190, '');
    }

    /**
     * Every admin account is notified. Queued behind the response, and never
     * allowed to turn a stored lead into a 500 for the visitor.
     */
    private function notifyTeam(ContactSubmission $submission): void
    {
        try {
            $admins = User::all();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new NewContactSubmission($submission));
            }
        } catch (\Throwable $e) {
            Log::error('Contact notification failed', [
                'submission' => $submission->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
