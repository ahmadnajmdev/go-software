<?php

namespace App\Console\Commands;

use App\Models\AnalyticsEvent;
use Illuminate\Console\Command;

/**
 * The privacy policy says the event log is deleted after twelve months. This
 * is what does it, so the promise is kept by the code rather than by memory.
 */
class PruneAnalytics extends Command
{
    protected $signature = 'analytics:prune {--months=12 : How much history to keep}';

    protected $description = 'Delete analytics events older than the retention period';

    public function handle(): int
    {
        $months = max(1, (int) $this->option('months'));
        $cutoff = now()->subMonths($months);

        $deleted = AnalyticsEvent::where('created_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deleted} event(s) older than {$cutoff->toDateString()}.");

        return self::SUCCESS;
    }
}
