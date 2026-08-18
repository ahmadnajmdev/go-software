<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Media;
use App\Models\Project;
use App\Models\Setting;
use App\Support\Settings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Pull externally-hosted images onto our own disk.
 *
 * Client logos were being hot-linked from servers we do not control — a
 * client's own site, a Play Store CDN path, and a Google Images thumbnail
 * that expires by design. Any of them can vanish without warning and take a
 * logo off the homepage with it.
 *
 * Content is admin-managed, so which URLs are involved differs per install.
 * This walks whatever is actually stored, downloads it, and rewrites the
 * reference. Dry-run unless --apply is passed.
 */
class LocaliseMedia extends Command
{
    protected $signature = 'media:localise
                            {--apply : Actually download and rewrite (otherwise just report)}
                            {--include-unsplash : Also pull down Unsplash placeholders}';

    protected $description = 'Download hot-linked images and repoint the site at local copies';

    /** Hosts we serve from ourselves and must never fetch from at runtime. */
    private const KEEP = ['gosoftware.krd', 'www.gosoftware.krd'];

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $found = 0;
        $done = 0;

        foreach ($this->targets() as [$label, $url, $store]) {
            if (! $this->isExternal($url)) {
                continue;
            }

            $found++;
            $this->line(sprintf('  %-34s %s', $label, Str::limit($url, 70)));

            if (! $apply) {
                continue;
            }

            if ($path = $this->download($url)) {
                $store($path);
                $done++;
                $this->info("      → {$path}");
            } else {
                $this->error('      → download failed; left as-is');
            }
        }

        if (! $found) {
            $this->info('No externally-hosted images. Nothing to do.');

            return self::SUCCESS;
        }

        $this->newLine();
        $apply
            ? $this->info("Localised {$done} of {$found} image(s).")
            : $this->warn("{$found} externally-hosted image(s). Re-run with --apply to pull them in.");

        return self::SUCCESS;
    }

    /** @return iterable<array{0:string,1:?string,2:callable}> */
    private function targets(): iterable
    {
        foreach (Client::all() as $client) {
            yield ["client:{$client->name}", $client->logo, fn ($p) => $client->update(['logo' => $p])];
        }

        foreach (Project::all() as $project) {
            yield ["project:{$project->tr('title', 'en')}", $project->image, fn ($p) => $project->update(['image' => $p])];
        }

        foreach (Setting::where('key', 'like', 'images.%')->get() as $setting) {
            $value = $setting->value;

            if (is_string($value)) {
                yield ["setting:{$setting->key}", $value, fn ($p) => Settings::set($setting->key, $p)];

                continue;
            }

            // images.hero_avatars and friends are lists
            foreach ((array) $value as $i => $item) {
                if (! is_string($item)) {
                    continue;
                }

                yield ["setting:{$setting->key}[{$i}]", $item, function ($p) use ($setting, $i) {
                    $list = (array) Settings::get($setting->key);
                    $list[$i] = $p;
                    Settings::set($setting->key, $list);
                }];
            }
        }
    }

    private function isExternal(?string $url): bool
    {
        if (blank($url) || ! Str::startsWith($url, ['http://', 'https://'])) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST) ?: '';

        if (in_array($host, self::KEEP, true)) {
            return false;
        }

        return ! Str::contains($host, 'unsplash.com') || $this->option('include-unsplash');
    }

    /** Store under uploads/ and register it in the media library. */
    private function download(string $url): ?string
    {
        try {
            $response = Http::timeout(25)->withHeaders(['User-Agent' => 'GoSoftware/1.0'])->get($url);

            if (! $response->successful()) {
                return null;
            }
        } catch (\Throwable) {
            return null;
        }

        $body = $response->body();
        $mime = Str::before((string) $response->header('Content-Type'), ';') ?: 'image/png';

        $extension = match ($mime) {
            'image/svg+xml' => 'svg',
            'image/jpeg' => 'jpg',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'png',
        };

        // An SVG is a document, not a bitmap — refuse one carrying script.
        if ($extension === 'svg' && preg_match('/<script|onload=|onerror=|javascript:/i', $body)) {
            $this->error('      → SVG contains script; refusing to store it');

            return null;
        }

        $path = 'uploads/'.date('Y/m').'/'.Str::random(40).'.'.$extension;
        Storage::disk('public')->put($path, $body);

        Media::create([
            'path' => $path,
            'original_name' => basename(parse_url($url, PHP_URL_PATH) ?: 'image'),
            'mime' => $mime,
            'size' => strlen($body),
        ]);

        return $path;
    }
}
