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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private const UNSPLASH = 'https://images.unsplash.com/';

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
        $strings = require database_path('seeders/data/ui_strings.php');

        foreach ($strings as $key => $value) {
            $group = $this->groupFor($key);

            // pricing, blog and the team grid were removed from the site
            if (in_array($group, ['Pricing', 'Blog', 'Team'])
                || in_array($key, ['navPricing', 'navBlog', 'ftBlog', 'ftTeam'])) {
                continue;
            }

            UiString::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => $group],
            );
        }
    }

    /** Bucket design keys into admin-editor groups by their prefix conventions. */
    private function groupFor(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'nav') || in_array($key, ['getQuote', 'location', 'followUs']) => 'Navigation',
            str_starts_with($key, 'h1') || str_starts_with($key, 'h2')
                || in_array($key, ['projDelivered', 'yrsBadge', 'ofEng']) => 'Hero',
            (bool) preg_match('/^f\d/', $key) => 'Feature strip',
            str_starts_with($key, 'about') || (bool) preg_match('/^ab\d/', $key)
                || in_array($key, ['ceoRole', 'yearsIn']) => 'About',
            str_starts_with($key, 'svc') || in_array($key, ['webDev', 'mgmtSystems', 'learnMore']) => 'Services',
            str_starts_with($key, 'why') || str_starts_with($key, 'mq')
                || in_array($key, ['topRated', 'agency2025', 'mobileApps']) => 'Why us',
            str_starts_with($key, 'proc') || (bool) preg_match('/^p\d/', $key) => 'Process',
            str_starts_with($key, 'proj') || (bool) preg_match('/^cat\d/', $key)
                || in_array($key, ['allProjects', 'catAll']) => 'Projects',
            (bool) preg_match('/^st\d/', $key) => 'Stats',
            str_starts_with($key, 'team') || (bool) preg_match('/^role\d/', $key) => 'Team',
            str_starts_with($key, 'founder') => 'Founder',
            str_starts_with($key, 'tst') => 'Testimonials',
            str_starts_with($key, 'price') || str_starts_with($key, 'plan') || str_starts_with($key, 'feat')
                || in_array($key, ['monthly', 'annual', 'save20', 'getStarted', 'mostPopular', 'month', 'year']) => 'Pricing',
            str_starts_with($key, 'blog') || $key === 'readMore' => 'Blog',
            str_starts_with($key, 'ct') || str_starts_with($key, 'ph') || str_starts_with($key, 'opt')
                || str_starts_with($key, 'err')
                || in_array($key, ['callUs', 'emailUs', 'visitUs', 'getDirections', 'formTitle', 'formSub',
                    'sendMsg', 'thanksT', 'thanksB']) => 'Contact',
            str_starts_with($key, 'ft') || in_array($key, ['copyright', 'privacy', 'terms']) => 'Footer',
            default => 'Other',
        };
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
            ['Tom Harding', $strings['tst1R'], $strings['tst1Q'], 'photo-1568602471122-7832951cc4c5'],
            ['Priya Nair', $strings['tst2R'], $strings['tst2Q'], 'photo-1580489944761-15a19d654956'],
            ['Sarah Doyle', $strings['tst3R'], $strings['tst3Q'], 'photo-1607990281513-2c110a25bd8c'],
        ];

        Testimonial::query()->delete();
        foreach ($items as $i => [$author, $role, $quote, $photo]) {
            Testimonial::create([
                'position' => $i + 1,
                'author' => $author,
                'role' => $role,
                'quote' => $quote,
                'avatar' => self::UNSPLASH."{$photo}?auto=format&fit=crop&w=100&q=80",
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
            'social.facebook' => '#',
            'social.linkedin' => '#',
            'social.x' => '#',
            'social.youtube' => '#',
            'logo.dark' => 'images/logo-dark.png',
            'logo.light' => 'images/logo-light.png',
            'about.ceo_name' => 'Ahmad Najm',
            'stats.values' => [
                ['count' => 300, 'suffix' => '+'],
                ['count' => 180, 'suffix' => '+'],
                ['count' => 15, 'suffix' => '+'],
                ['count' => 98, 'suffix' => '%'],
            ],
            'images.hero' => self::UNSPLASH.'photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=900&q=80',
            'images.hero_avatars' => [
                self::UNSPLASH.'photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=80&q=80',
                self::UNSPLASH.'photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&q=80',
                self::UNSPLASH.'photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=80&q=80',
            ],
            'images.about_main' => self::UNSPLASH.'photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80',
            'images.about_inset' => self::UNSPLASH.'photo-1531482615713-2afd69097998?auto=format&fit=crop&w=400&q=80',
            'images.ceo' => self::UNSPLASH.'photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80',
            'images.founder' => self::UNSPLASH.'photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80',
            'images.why' => self::UNSPLASH.'photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=900&q=80',
        ];

        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
