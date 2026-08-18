# Blocked — input needed from Ahmad

Items the CRO implementation cannot complete without real information.
Nothing here has been guessed or filled with placeholder data.

Grouped by category. Each item states exactly what is needed and what is
currently in the code as a result.

---

## Analytics (CRO-01)

- [ ] **Google Tag Manager container ID.**
      Needed: the `GTM-XXXXXXX` value.
      Where it goes: `GTM_ID=` in `.env` (already added, currently empty).
      Current behaviour: with `GTM_ID` empty, **no tracking code is emitted at
      all** — no container, no noscript iframe. The `dataLayer.push` calls still
      run harmlessly, so every event is already wired and will start reporting
      the moment the ID is set. Nothing else needs to change.
      Note: GA4, Meta Pixel and Microsoft Clarity load *through* the container,
      so no other third-party ID belongs in this codebase.

---

## Contact form and lead handling (CRO-02)

- [ ] **Where should lead notifications go?**
      Current behaviour: every account in the `users` table (i.e. every admin
      login) is emailed on a new submission. That is right for a one-admin
      site. If leads should go to a shared inbox, a second person, or a
      WhatsApp/Slack channel instead, say which and it becomes a one-line change.

- [ ] **Production mail transport.**
      `.env` currently has `MAIL_MAILER=log`, so **notification emails are written
      to the log file and not actually sent.** Leads *are* stored and visible in
      the admin inbox regardless, so nothing is lost — but nobody is alerted
      until real SMTP credentials are set. Needed: SMTP host, port, username,
      password, and the from-address to send as.
      Also note `QUEUE_CONNECTION=database`: a queue worker must be running in
      production (`php artisan queue:work`) or queued notifications will sit
      unprocessed. If there is no worker, say so and I will make the send
      synchronous instead.

- [ ] **Confirm the Arabic and Kurdish validation messages.**
      Six new strings were added (`errGeneric`, `errName`, `errEmail`,
      `errEmailValid`, `errMessage`, `errTooLong`). English is definitive; the
      Arabic and Kurdish are my translations and should be read before launch.
      They are editable in the admin panel under **Strings → Contact**, so no
      code change is needed to correct them. Current text:

      | Key | English |
      |---|---|
      | `errGeneric` | Please check the highlighted fields and try again. |
      | `errName` | Please tell us your name. |
      | `errEmail` | Please enter an email address we can reply to. |
      | `errEmailValid` | That email address doesn't look right. |
      | `errMessage` | Please tell us a little about your project. |
      | `errTooLong` | That's a little too long — please shorten it. |

---

## Social profiles (CRO-04)

- [ ] **Real social media URLs.** None are configured, so **none of the icons
      render** — they are absent rather than dead. Nothing has been guessed.
      Fill in whichever exist at **Admin → Settings → Social links**; each one
      appears the moment it is saved. Must be a full `https://` URL.

      Company: Facebook · Instagram · LinkedIn · YouTube · X (Twitter)
      Founder (shown on the founder card): Ahmad's LinkedIn · Ahmad's Instagram

      If a channel does not exist, leave it blank — that is a supported state,
      not an omission.

- [ ] **Careers link: removed.** It pointed at `#`. A dead careers link
      undermines the "dedicated teams" claim, so it is gone rather than
      pointing at a stub. Say the word if you want a real careers page.

---

## Legal (CRO-04)

- [ ] **The privacy policy and terms of service need your review before launch.**
      Both pages are live at `/privacy-policy` and `/terms-of-service` (and the
      `/ar` and `/ckb` variants), written as standard content for a Kurdistan/
      Iraq-based software agency. **They have not been reviewed by a lawyer.**
      The copy is in `resources/legal/privacy.php` and `resources/legal/terms.php`.

      Specific points to confirm, because I chose a sensible default rather than
      knowing your actual position:
      - **Data retention:** privacy policy says enquiries are deleted "normally no
        more than three years after our last contact". Is three years right?
      - **Governing law:** terms name the laws of the Kurdistan Region of Iraq and
        the courts of Erbil. Confirm.
      - **Analytics named:** the policy names Google Analytics 4, Meta Pixel and
        Microsoft Clarity because CRO-01 assumes those load through GTM. If you
        load something else, the policy must say so.
      - The Arabic and Kurdish versions are my translations and should be read
        alongside the English.

---

## Service pages (CRO-13)

- [ ] **Pricing.** Every service page has a "What it costs" section, and none of
      them states a number — nothing has been invented. Each currently says the
      project is priced individually and promises an indicative range within one
      working day. To publish real figures I need:
      - Website packages — what tiers, and what is in each?
      - **POS: price per terminal**, and the one-off central system charge. The
        page already says pricing works this way, so this is the one I most need.
      - Indicative ranges for mobile apps and custom management systems.
      - E-commerce: setup, and anything ongoing.

- [ ] **Which payment and delivery integrations have you ACTUALLY built?**
      The e-commerce page deliberately **does not name FIB, Zain Cash or Nass
      Wallet**, because I cannot source the claim and naming an integration you
      have not built is the kind of thing a prospect discovers at the worst
      moment. It currently says cash on delivery is handled properly (which is
      the real differentiator here) and that we confirm which providers we can
      connect before promising. Tell me which of these you have shipped and I
      will name them explicitly — it is the strongest section on that page:
      FIB · Zain Cash · Nass Wallet · FastPay · any courier APIs.

- [ ] **Support response times (CRO-23 too).** The support page says response
      times are written into the agreement rather than left vague, and that the
      exact tiers are being finalised. Give me the tiers and I will publish them.

---

## Statistics (CRO-09) — the numbers on the homepage are unverified

Every figure now lives in **`config/stats.php`**. Correct them there and each
one updates everywhere it appears. I have changed none of them — they are what
the site was already claiming.

Why this matters: the page showed "300+ Projects delivered" and "15+ Years in
software" within two screens of each other. That reads as 20 projects a year
for fifteen years, or as numbers nobody counted. A prospect who notices
discounts everything else on the page. (The "3+ yrs of engineering" hero widget
that made it worse was already removed in an earlier commit.)

- [ ] **True number of projects delivered.** Currently claims **300+**.
- [ ] **True founding year** — currently implied by **15+ years in software**,
      shown both in the stats band and on the About photo badge.
- [ ] **Number of clients.** Currently claims **180+**.
- [ ] **Is the 98% satisfaction rate measured, and from what sample?** If it is
      not measured, say so and I will remove it. An unmeasured percentage is
      the easiest claim on the page to disbelieve.
- [ ] **Who awarded "Top-rated Software agency 2025"?**
      **The badge is currently hidden**, because it claimed an award without
      naming who gave it, and an unsourced award costs more trust than it earns.
      Set `award.awarded_by` in `config/stats.php` to the organisation's name
      and the badge returns, with the source shown next to it. If there is no
      awarding body, leave it — the badge stays off.

---

## Assets (CRO-08)

- [ ] **Google Play URL for the Asaari app.** The App Store link is fixed
      (see below) but there is no Play Store link anywhere on the site, and
      Android is the majority platform in this market. Send me the URL.

- [ ] **Run `php artisan media:localise --apply` on production, or upload the
      two remaining logos yourself.** Three client logos were hot-linked from
      servers we do not control. The Zuu logo is now downloaded and committed
      to `public/images/clients/zuu.svg`. The other two I could not fetch —
      the audit gave truncated URLs:
      - Asaari — `play-lh.googleusercontent.com/…` (a Play Store CDN path)
      - Suli Shopping — `encrypted-tbn0.gstatic.com/…` (a Google **Images
        thumbnail**, which is designed to expire — this one will break)

      Because site content is admin-managed, these live in the production
      database, not in this repository, so I cannot reach them from here. The
      new `media:localise` command walks whatever is actually stored,
      downloads it and repoints the reference. Run it with no flags first to
      see the list, then with `--apply`. Anything it fails to fetch is left
      untouched rather than replaced with a broken path. Best of all: send me
      the original high-resolution logo files and I will commit them properly.

- [ ] **The App Store storefront is fixed automatically on deploy.** No action
      needed — a migration rewrites `apps.apple.com/<country>/…` to `/iq/…`
      for every project link. Noting it so you know it happened.

---

## Photography (CRO-11) — this is the biggest visual gap

All stock imagery has been removed: the "GoSoftware team" photo, the three
stock faces used as client avatars, the "Coding" inset and the "Why Choose Us"
photo were recognisable Unsplash pictures, and to anyone who recognises them
they say the site is a template.

Nothing stock replaced them — that would repeat the mistake. Where a photo is
missing the site now renders a plain branded panel, which reads as "no photo
yet" rather than as a stranger being passed off as us. **Each one appears the
moment you upload a real photo through Admin → Media; no code change needed.**

Please send these shots. Aspect ratios matter — they are what the layout
reserves, so a photo at the right ratio drops in without cropping badly:

| Where | Setting | Ratio | Rendered at | What it should show |
|---|---|---|---|---|
| Hero | `images.hero` | **5:4 landscape** | 620×500 | The strongest single image you have: the team at work in the Erbil office, or a real screen from a system you built. This is the first thing a visitor sees. |
| About — main | `images.about_main` | **4:5 portrait** | 620×460 | The office, or the team together. |
| About — inset | `images.about_inset` | **7:5 landscape** | 210×150 | A close detail — a screen, a whiteboard, hands on a keyboard. Small, so keep it simple. |
| About — byline | `images.ceo` | **1:1 square** | 50×50 | Ahmad, head and shoulders. Crops to a circle. |
| Founder | `images.founder` | **4:5 portrait** | 620×480 | Ahmad, larger. This is the strongest trust asset on the site. |
| Why GoSoftware | `images.why` | **4:5 portrait** | 620×460 | Engineers working — real desks, real screens. |
| Service cards ×4 | per service | **16:10 landscape** | 620×220 | One per service. A real screen from that kind of project is stronger than a photo of people. |
| Project tiles | per project | **1:1 square** | 600×600 | A screenshot of each project. |

Please send at 2× the rendered size, so 1240px wide for the hero. JPEG or PNG
is fine — CRO-21 converts them to WebP.

- [ ] **The founder photo is missing from disk.**
      Found while verifying pages in a browser: the `images.founder` setting
      points at `storage/uploads/2026/08/cECgg6a8Z9Gf00W8h6AAidkOzxukWzHFWotcRM5V.jpg`,
      which **returns 403 on every page load** — the file is not there. This is the
      only console error on the site. It matters for CRO-11, which plans to use
      the real founder photo in place of the Unsplash stock images.
      Needed: the original photo, re-uploaded through the admin media panel.
