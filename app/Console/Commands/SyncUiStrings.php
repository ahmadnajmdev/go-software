<?php

namespace App\Console\Commands;

use App\Support\UiStringDefaults;
use Illuminate\Console\Command;

class SyncUiStrings extends Command
{
    protected $signature = 'strings:sync';

    protected $description = 'Insert UI strings this install has never seen (never overwrites edited copy)';

    public function handle(): int
    {
        $created = UiStringDefaults::syncMissing();

        if (! $created) {
            $this->info('Every default string is already present.');

            return self::SUCCESS;
        }

        $this->info('Added '.count($created).' missing string(s):');
        $this->line('  '.implode(', ', $created));

        return self::SUCCESS;
    }
}
