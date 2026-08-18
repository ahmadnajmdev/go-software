<?php

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;

/**
 * Clear every stock photograph.
 *
 * Recognisable Unsplash images were standing in as "the GoSoftware team" and
 * as three client faces. To a prospect who recognises them — and in this
 * market plenty do — they say the site is a template.
 *
 * Nulled rather than replaced: <x-photo> renders a plain branded panel when
 * a photo is missing, which is honest. Substituting different stock would
 * repeat the mistake. See BLOCKED.md for the real shots needed.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (Setting::where('key', 'like', 'images.%')->get() as $setting) {
            $value = $setting->value;

            if (is_string($value) && $this->isStock($value)) {
                $setting->update(['value' => null]);

                continue;
            }

            if (is_array($value)) {
                $kept = array_values(array_filter(
                    $value,
                    fn ($item) => ! (is_string($item) && $this->isStock($item))
                ));

                if ($kept !== $value) {
                    $setting->update(['value' => $kept ?: null]);
                }
            }
        }

        // the three stock faces in the hero are no longer rendered at all
        Setting::where('key', 'images.hero_avatars')->delete();

        foreach ([Project::class => 'image', Service::class => 'image', Client::class => 'logo'] as $model => $field) {
            $model::get()->each(function ($row) use ($field) {
                if ($this->isStock((string) $row->{$field})) {
                    $row->update([$field => null]);
                }
            });
        }

        Testimonial::get()->each(function (Testimonial $testimonial) {
            if ($this->isStock((string) $testimonial->avatar)) {
                $testimonial->update(['avatar' => null]);
            }
        });

        Cache::forget('gs.settings');
    }

    public function down(): void
    {
        // Bringing stock photography back is not an improvement.
    }

    private function isStock(string $url): bool
    {
        return (bool) preg_match('#(images\.unsplash\.com|source\.unsplash\.com|pexels\.com|pixabay\.com)#i', $url);
    }
};
