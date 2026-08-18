<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function withStory(array $overrides = []): Project
    {
        $project = Project::first();

        $project->update(array_merge([
            'slug' => 'folivya-academy',
            'client' => 'Folivya Academy',
            'industry_id' => Category::where('slug', 'education')->value('id'),
            'outcome' => ['en' => 'Enrolment moved off WhatsApp.', 'ckb' => 'تۆمارکردن لە واتسئەپ دەرچوو.'],
            'problem' => ['en' => 'Enrolment ran through a WhatsApp group.'],
            'solution' => ['en' => 'A portal parents log into.'],
            'result' => ['en' => 'Admin time down 70%.'],
            'technology' => 'Laravel, Alpine.js',
            'platforms' => 'Web',
            'timeline' => '8 weeks',
            'live_since' => 'March 2025',
        ], $overrides));

        return $project->fresh();
    }

    public function test_a_project_with_a_story_gets_a_page_in_every_language(): void
    {
        $project = $this->withStory();

        foreach (['en' => '', 'ar' => '/ar', 'ckb' => '/ckb'] as $locale => $prefix) {
            $html = $this->get("{$prefix}/projects/{$project->slug}")->assertOk()->getContent();

            $this->assertStringContainsString(e($project->tr('title', $locale)), $html);
            $this->assertStringContainsString(e(t('prjProblem', $locale)), $html);
            $this->assertStringContainsString(e(t('prjSolution', $locale)), $html);
            $this->assertStringContainsString(e(t('prjResult', $locale)), $html);
            $this->assertStringContainsString(e(t('prjGlance', $locale)), $html);
        }
    }

    public function test_the_page_carries_the_full_structure(): void
    {
        $project = $this->withStory([
            'quote' => ['en' => 'They understood the school first.'],
            'quote_author' => 'Rebin Aziz',
            'quote_role' => 'Director',
            'screenshots' => ['uploads/a.png', 'uploads/b.png', 'uploads/c.png'],
        ]);

        $html = $this->get("/projects/{$project->slug}")->assertOk()->getContent();

        foreach (['Folivya Academy', 'Enrolment moved off WhatsApp.', '8 weeks', 'March 2025',
            'Laravel', 'Alpine.js', 'They understood the school first.', 'Rebin Aziz', 'Director',
            'uploads/a.png', 'uploads/c.png'] as $expected) {
            $this->assertStringContainsString($expected, $html, "missing: {$expected}");
        }

        $this->assertStringContainsString('"@type":"BreadcrumbList"', $html);
        $this->assertStringContainsString(e(t('prjRelated', 'en')), $html);
    }

    public function test_sections_without_content_are_simply_absent(): void
    {
        $project = $this->withStory(['quote' => null, 'screenshots' => null, 'technology' => null]);

        $html = $this->get("/projects/{$project->slug}")->assertOk()->getContent();

        // nothing is invented to fill a gap
        $this->assertStringNotContainsString(t('prjScreens', 'en'), $html);
        $this->assertStringNotContainsString(t('prjTech', 'en'), $html);
        $this->assertStringNotContainsString('<blockquote', $html);
    }

    public function test_a_project_without_a_story_has_no_page_and_no_internal_link(): void
    {
        $bare = Project::where('id', '!=', $this->withStory()->id)->first();
        $bare->update(['problem' => null, 'solution' => null, 'result' => null, 'url' => 'https://powerorbits.com']);

        $this->assertFalse($bare->hasStory());
        $this->get("/projects/{$bare->slug}")->assertNotFound();

        // its tile keeps the old off-site behaviour rather than linking nowhere
        $html = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringContainsString('href="https://powerorbits.com"', $html);
        $this->assertStringNotContainsString(url("/projects/{$bare->slug}"), $html);
    }

    public function test_an_external_link_becomes_a_button_inside_the_page(): void
    {
        $project = $this->withStory(['url' => 'https://shopp.krd']);

        // the tile now goes to our page, not off-site
        $archive = $this->get('/projects')->assertOk()->getContent();
        $this->assertStringContainsString(url("/projects/{$project->slug}"), $archive);

        // and the live site is reachable from inside the detail page
        $detail = $this->get("/projects/{$project->slug}")->assertOk()->getContent();
        $this->assertStringContainsString('href="https://shopp.krd"', $detail);
        $this->assertStringContainsString(t('prjVisit', 'en'), $detail);
        $this->assertStringContainsString('target="_blank"', $detail);
    }

    public function test_the_page_offers_a_cta_and_a_project_specific_whatsapp_message(): void
    {
        $project = $this->withStory();

        $html = $this->get("/projects/{$project->slug}")->assertOk()->getContent();

        $this->assertStringContainsString('data-gs-location="project_detail"', $html);
        $this->assertStringContainsString(rawurlencode($project->tr('title', 'en')), $html);
    }

    public function test_industry_is_the_primary_filter_and_type_the_secondary(): void
    {
        $html = $this->get('/projects')->assertOk()->getContent();

        foreach (['retail', 'food', 'education', 'real-estate', 'logistics', 'ecommerce', 'services'] as $slug) {
            $this->assertStringContainsString("cat === '{$slug}'", $html, "no {$slug} industry chip");
        }

        // type survives as the secondary row
        $this->assertStringContainsString(t('prjFilterType', 'en'), $html);
        $this->assertStringContainsString("cat === 'website'", $html);
    }

    public function test_a_tile_matches_both_its_industry_and_its_type(): void
    {
        $project = $this->withStory();
        $project->update(['category_id' => Category::where('slug', 'website')->value('id')]);

        $html = $this->get('/projects')->assertOk()->getContent();

        // Alpine checks the tile's own slug list, so one tile answers to both
        preg_match('#x-show="cat === \'all\' \|\| (.+?)\.includes\(cat\)"#', $html, $m);
        $this->assertNotEmpty($m, 'no tile filter expression');

        $lists = [];
        preg_match_all('#x-show="cat === \'all\' \|\| JSON\.parse\(\'(.+?)\'\)#', $html, $all);
        foreach ($all[1] as $encoded) {
            $lists[] = json_decode(str_replace('\u0022', '"', $encoded), true);
        }

        $this->assertContains(['education', 'website'], $lists,
            'no tile answers to both its industry and its type');
    }

    public function test_a_cta_follows_the_grid_rather_than_only_the_page_bottom(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('data-gs-location="projects_grid"', $html);
        $this->assertLessThan(
            strpos($html, 'data-section="contact"'),
            strpos($html, 'data-gs-location="projects_grid"')
        );
    }

    public function test_the_admin_can_write_a_full_case_study(): void
    {
        $project = Project::first();

        $this->actingAs(User::first())->put("/admin/projects/{$project->id}", [
            'title' => ['en' => 'Fantasy Town'],
            'slug' => 'fantasy-town',
            'client' => 'Fantasy Town',
            'industry_id' => Category::where('slug', 'services')->value('id'),
            'category_id' => Category::where('slug', 'system')->value('id'),
            'outcome' => ['en' => 'Ticketing and stock in one place.'],
            'problem' => ['en' => 'Paper tickets.', 'ar' => '', 'ckb' => ''],
            'solution' => ['en' => 'A POS and ticketing system.'],
            'result' => ['en' => 'Queues halved.'],
            'screenshots' => "uploads/one.png\n\nuploads/two.png\n",
            'technology' => 'Laravel, Flutter',
            'quote' => ['en' => 'It just works.'],
            'quote_author' => 'Aram',
        ])->assertRedirect('/admin/projects')->assertSessionHasNoErrors();

        $saved = $project->fresh();
        $this->assertSame('fantasy-town', $saved->slug);
        $this->assertSame(['uploads/one.png', 'uploads/two.png'], $saved->screenshots);
        // blank locales are dropped so tr() falls back to English
        $this->assertSame(['en'], array_keys($saved->problem));
        $this->assertTrue($saved->hasStory());

        $this->get('/projects/fantasy-town')->assertOk()->assertSee('Queues halved.');
    }

    public function test_a_duplicate_slug_is_rejected(): void
    {
        $this->withStory();
        $other = Project::where('slug', '!=', 'folivya-academy')->first();

        $this->actingAs(User::first())->put("/admin/projects/{$other->id}", [
            'title' => ['en' => 'Clash'],
            'slug' => 'folivya-academy',
        ])->assertSessionHasErrors('slug');
    }
}
