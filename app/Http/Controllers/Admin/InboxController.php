<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;

class InboxController extends Controller
{
    public function index()
    {
        return view('admin.inbox.index', [
            'items' => ContactSubmission::orderByRaw('read_at IS NULL DESC')->latest()->paginate(25),
            'unread' => ContactSubmission::unread()->count(),
        ]);
    }

    public function show(ContactSubmission $submission)
    {
        if (! $submission->read_at) {
            $submission->update(['read_at' => now()]);
        }

        return view('admin.inbox.show', ['item' => $submission]);
    }

    public function toggle(ContactSubmission $submission)
    {
        $submission->update(['read_at' => $submission->read_at ? null : now()]);

        return back();
    }

    public function destroy(ContactSubmission $submission)
    {
        $submission->delete();

        return redirect()->route('admin.inbox.index')->with('ok', 'Submission deleted.');
    }
}
