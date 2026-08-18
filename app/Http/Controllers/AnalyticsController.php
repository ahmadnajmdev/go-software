<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Receives the events the site pushes to the dataLayer and stores them on our
 * own disk, so the admin dashboard can report on them without a third party.
 *
 * Public and unauthenticated by necessity, so it is deliberately narrow: a
 * fixed vocabulary of event names, a short list of parameters, hard length
 * limits, a batch cap, rate limiting on the route, and nothing personal
 * recorded at all.
 *
 * Events arrive in batches. One request per event put several writes in flight
 * at once, which is enough to make a database-backed cache lock on the rate
 * limiter's own counter and lose the request before it got here.
 */
class AnalyticsController extends Controller
{
    /** Parameters worth keeping. Anything else in the payload is discarded. */
    private const PARAMS = ['label', 'location', 'source', 'service', 'project', 'question', 'field', 'step', 'budget', 'timeline'];

    private const BATCH_LIMIT = 20;

    public function __invoke(Request $request): Response
    {
        // Someone browsing their own site should not appear in their own
        // numbers, and Do Not Track is honoured before anything is written.
        if (auth()->check() || $this->doesNotTrack($request)) {
            return response()->noContent();
        }

        // A single event or a batch; the client sends batches, but a lone
        // event is a valid thing to receive.
        $events = $request->input('events', [$request->except('events')]);

        if (! is_array($events)) {
            return response()->noContent();
        }

        $rows = [];
        $visitor = $this->visitor($request);
        $now = now();

        foreach (array_slice($events, 0, self::BATCH_LIMIT) as $event) {
            if (! is_array($event)) {
                continue;
            }

            $validator = Validator::make($event, $this->rules());

            if ($validator->fails()) {
                continue; // drop the bad one, keep the rest of the batch
            }

            $data = $validator->validated();
            $params = collect($data)->only(self::PARAMS)->filter(fn ($value) => filled($value));

            $rows[] = [
                'name' => $data['name'],
                'page' => Str::limit((string) ($data['page'] ?? '/'), 190, ''),
                'locale' => $data['language'] ?? null,
                'label' => $data['label'] ?? null,
                'location' => $data['location'] ?? null,
                'params' => $params->isEmpty() ? null : json_encode($params->all(), JSON_UNESCAPED_UNICODE),
                'visitor' => $visitor,
                'created_at' => $now,
            ];
        }

        if ($rows) {
            try {
                // One statement for the whole batch.
                AnalyticsEvent::insert($rows);
            } catch (\Throwable $e) {
                // Analytics must never surface as an error to a visitor, and a
                // failed write is not worth a 500 on a beacon nobody is
                // waiting for. Logged at debug so a real fault stays findable.
                Log::debug('Analytics batch dropped', ['events' => count($rows), 'error' => $e->getMessage()]);
            }
        }

        return response()->noContent();
    }

    private function rules(): array
    {
        return [
            'name' => ['required', Rule::in(AnalyticsEvent::NAMES)],
            'page' => ['nullable', 'string', 'max:190'],
            'language' => ['nullable', 'string', 'max:3'],
            'label' => ['nullable', 'string', 'max:190'],
            'location' => ['nullable', 'string', 'max:60'],
            'source' => ['nullable', 'string', 'max:60'],
            'service' => ['nullable', 'string', 'max:90'],
            'project' => ['nullable', 'string', 'max:190'],
            'question' => ['nullable', 'string', 'max:250'],
            'field' => ['nullable', 'string', 'max:40'],
            'step' => ['nullable', 'integer', 'between:1,10'],
            'budget' => ['nullable', 'string', 'max:40'],
            'timeline' => ['nullable', 'string', 'max:40'],
        ];
    }

    /**
     * A same-day pseudonym. The salt rotates every day, so two visits on
     * different days cannot be tied together, and the hash cannot be reversed
     * to an address. No IP or user agent is ever stored.
     */
    private function visitor(Request $request): string
    {
        return substr(hash_hmac('sha256', implode('|', [
            $request->ip(),
            (string) $request->userAgent(),
            now()->toDateString(),
        ]), config('app.key')), 0, 32);
    }

    private function doesNotTrack(Request $request): bool
    {
        return $request->header('DNT') === '1' || $request->header('Sec-GPC') === '1';
    }
}
