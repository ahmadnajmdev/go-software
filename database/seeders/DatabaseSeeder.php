<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\UiString;
use App\Support\UiStringDefaults;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->admin();
        $this->uiStrings();
        $this->sections();
        $this->servicesAndProjects();
        $this->clients();
        $this->testimonials();
        $this->settings();

        Cache::forget('gs.strings');
        Cache::forget('gs.settings');

        $this->command->info('Admin login: info@gosoftware.krd / '.env('ADMIN_PASSWORD', 'password').' (set ADMIN_PASSWORD in .env to change)');
    }

    private function admin(): void
    {
        User::updateOrCreate(
            ['email' => 'info@gosoftware.krd'],
            ['name' => 'GoSoftware Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'password'))],
        );
    }

    private function uiStrings(): void
    {
        foreach (UiStringDefaults::all() as $key => $value) {
            if (UiStringDefaults::isRetired($key)) {
                continue;
            }

            UiString::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => UiStringDefaults::groupFor($key)],
            );
        }
    }

    private function sections(): void
    {
        $keys = ['hero', 'strip', 'about', 'services', 'why', 'process', 'projects',
                 'stats', 'founder', 'testimonials', 'contact'];

        foreach ($keys as $i => $key) {
            Section::updateOrCreate(['key' => $key], ['position' => $i + 1, 'visible' => true]);
        }
    }

    private function servicesAndProjects(): void
    {
        $defaults = require database_path('seeders/data/cms_defaults.php');

        Service::query()->delete();
        foreach ($defaults['services'] as $i => $svc) {
            Service::create([
                'position' => $i + 1,
                'image' => $svc['img'],
                'tag' => $svc['tag'],
                'title' => $svc['title'],
                'description' => $svc['desc'],
            ]);
        }

        Project::query()->delete();

        // Categories are shared across projects, so resolve each one once and
        // reuse it. Keyed by name so re-seeding never duplicates or renames.
        $categories = [];

        foreach ($defaults['projects'] as $i => $prj) {
            $key = mb_strtolower($prj['cat']['en']);

            $categories[$key] ??= Category::firstOrCreate(
                ['slug' => Str::slug($prj['cat']['en'])],
                ['position' => count($categories) + 1, 'name' => $prj['cat']],
            );

            Project::create([
                'position' => $i + 1,
                'image' => $prj['img'],
                'category_id' => $categories[$key]->id,
                'title' => $prj['title'],
            ]);
        }
    }

    private function testimonials(): void
    {
        $strings = require database_path('seeders/data/ui_strings.php');

        $items = [
            ['Tom Harding', $strings['tst1R'], $strings['tst1Q']],
            ['Priya Nair', $strings['tst2R'], $strings['tst2Q']],
            ['Sarah Doyle', $strings['tst3R'], $strings['tst3Q']],
        ];

        Testimonial::query()->delete();
        foreach ($items as $i => [$author, $role, $quote]) {
            Testimonial::create([
                'position' => $i + 1,
                'author' => $author,
                'role' => $role,
                'quote' => $quote,
                'avatar' => null,
                'rating' => 5,
            ]);
        }
    }

    /**
     * Placeholder marquee names. Keyed by name so re-seeding never wipes a
     * logo that was uploaded through Admin → Clients.
     */
    private function clients(): void
    {
        $names = ['Northwind', 'Vertex', 'Loopline', 'Brightsend', 'Corely', 'Magnify'];

        foreach ($names as $i => $name) {
            Client::updateOrCreate(['name' => $name], ['position' => $i + 1]);
        }
    }

    private function settings(): void
    {
        $values = [
            'theme.accent' => '#2ca69c',
            'theme.mood' => 'midnight',
            'theme.shape' => 'soft',
            'contact.phone' => '+9647517110459',
            'contact.email' => 'info@gosoftware.krd',
            'contact.address' => [
                'en' => 'Justice Tower, Floor 16, Office 21 — Erbil, Kurdistan Region, Iraq',
                'ar' => 'برج جَستِس، الطابق 16، مكتب 21 — أربيل، إقليم كوردستان، العراق',
                'ckb' => 'تاوەری جەستس، نهۆمی ١٦، ئۆفیسی ٢١ — هەولێر، هەرێمی کوردستان، عێراق',
            ],
            // Adjustable from Admin → Settings → Contact → Map location.
            // Leave map_embed null to drive the Google Maps embed off these
            // coordinates; paste a Share → Embed a map link there to override.
            'contact.map_lat' => '36.1821139',
            'contact.map_lng' => '43.9785422',
            'contact.map_zoom' => 17,
            'contact.map_embed' => null,
            'social.facebook' => null,
            'social.linkedin' => null,
            'social.x' => null,
            'social.youtube' => null,
            'logo.dark' => 'images/logo-dark.png',
            'logo.light' => 'images/logo-light.png',
            'about.ceo_name' => 'Ahmad Najm',
            // No stock photography ships as a default. Real photos are
            // uploaded through the admin media panel; until then <x-photo>
            // renders a plain branded panel. See BLOCKED.md for the shots needed.
            'images.hero' => null,
            'images.about_main' => null,
            'images.about_inset' => null,
            'images.ceo' => null,
            'images.founder' => null,
            'images.why' => null,
        ];

        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
