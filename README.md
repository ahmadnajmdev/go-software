# GoSoftware — Website + CMS

Trilingual (English / Arabic / Kurdish Sorani) marketing site for GoSoftware with a full CMS,
built from the claude.ai/design export `GoSoftware.dc.html`. Laravel 13 + Blade + Alpine.js + SQLite.

## Local development (Laravel Herd)

The project lives at `~/Herd/gosoftware` and is served automatically at **http://gosoftware.test**.

```bash
composer install
npm install
php artisan migrate:fresh --seed   # seeds ALL content so first boot matches the design
npm run build                      # or `npm run dev` while working on assets
```

## Admin

- Panel: **http://gosoftware.test/admin** — login `info@gosoftware.krd` / `password`
  (set `ADMIN_PASSWORD` in `.env` before seeding to change it).
- Manages: services, projects, team, testimonials, blog posts,
  all ~180 UI strings (3 languages side by side), theme (accent / mood / shape),
  section order & visibility, contact inbox (read/unread), media uploads.
- **Inline editing:** while logged in, open the public site and hit the floating pencil
  button — click any text to edit it in place (saved per current language), drag service/project
  cards to reorder, use the section chips to move/hide sections.

## Architecture notes

- Translatable content is stored as JSON columns `{"en":…,"ar":…,"ckb":…}`; models use the
  `HasTranslations` trait (`->tr('field')`). UI strings live in `ui_strings` and render via the
  global `t('key')` helper (cached; busted automatically on save).
- Language switching is session-based (`GET /lang/{en|ar|ckb}`); `ar`/`ckb` render RTL.
- Theme settings become CSS variables via `App\Support\Theme` (a port of the design's
  `applyTheme()`), emitted in the layout `<head>`.
- Design defaults are transcribed in `database/seeders/data/` — the admin "Reset all strings"
  action restores them.
- Seeded imagery hotlinks Unsplash (exactly like the design). For production, replace images
  via the admin Media library (uploads are stored on the `public` disk — `storage:link` required).
- Contact notifications use `MAIL_MAILER=log` (see `storage/logs/laravel.log`); point mail env
  vars at a real mailer for production.

## Tests

```bash
php artisan test
```

Covers: home rendering in all 3 locales, language switching, blog visibility rules,
contact submission + honeypot, admin auth gates, inline-edit API whitelisting and
per-locale persistence, section hide/reorder.
