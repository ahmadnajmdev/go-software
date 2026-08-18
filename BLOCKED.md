# Blocked — input needed from Ahmad

Items the CRO implementation cannot complete without real information.
Nothing here has been guessed or filled with placeholder data.

Grouped by category. Each item states exactly what is needed and what is
currently in the code as a result.

---

## Analytics

**The admin dashboard now reports on its own, with no third party involved.**
It shows page views, visitors, enquiries and conversion rate against the
previous period, a daily traffic chart, the form drop-off funnel, which CTA
gets clicked, how people reach out, top pages, language split, which FAQs get
opened, and the service/budget/timeline/campaign mix of enquiries. Nothing is
estimated — every number is an event this site recorded or a row in the inbox.

It holds no personal data: no IP, no user agent, no identifiers. Visits are
counted with a one-way code that changes daily, Do Not Track is honoured, your
own visits are excluded while logged in, and `analytics:prune` deletes anything
older than twelve months (scheduled weekly). The privacy policy was updated in
all three languages to say so.

**The dashboard is empty until people visit** — that is expected, not a fault.

- [ ] **A scheduler must be running in production** for the twelve-month
      deletion the privacy policy promises: `php artisan schedule:run` every
      minute via cron. If you cannot run one, tell me and I will move the prune
      into the request cycle instead.

## Analytics — GTM (CRO-01)

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

## FAQ answers (CRO-17) — two need your decision

Eight questions now sit above the final CTA on the home page, plus a
service-specific set on each service page, with `FAQPage` schema so Google can
surface the answers directly. Six are answered outright. Two are written
carefully because the honest answer is not mine to give:

- [ ] **Q4 — payment integrations.** The answer names **cash on delivery**,
      which is real, and deliberately does **not** name FIB, Zain Cash or Nass
      Wallet. It says we confirm what we can connect before promising anything.
      Tell me which you have actually built and I will name them — it is a
      strong answer and right now it is a cautious one. (Same item as the
      e-commerce page above.)

- [ ] **Q6 — what happens when something breaks.** The answer currently says
      response times are written into your agreement. It does **not** claim
      "30 days of free bug fixes after launch", because that is a commitment
      you have to make, not one I can make for you. Confirm it and the answer
      becomes much stronger — a specific warranty is far more persuasive than
      a promise to have written something down. (Also CRO-23.)

Questions 1, 2, 3, 5, 7 and 8 are answered fully and need nothing from you,
though they are worth reading: Q1 and Q2 commit to an indicative range within
one working day and to fixing scope and price in writing before starting.

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

## Statistics (CRO-09) — all removed, none are claimed

**The numbers are off the site.** You said you have no proof for them, so
nothing is claimed anywhere: the teal band (300+ projects, 180+ clients, 15+
years, 98% satisfaction) does not render, the "15+ Years in software" badge on
the About photo is gone, and the "98% satisfaction rate" that was also written
into the Why GoSoftware copy has been replaced.

Nothing was left as an empty shell — the band renders no markup at all, so
there is no blank strip where it used to be.

Everything is still wired. Put a real, defensible number in
**`config/stats.php`** and it comes straight back on its own — the band renders
whichever subset you fill in (one figure, or four), and the About badge follows
`years_in_software`. Nothing else to change.

- [ ] **True number of projects delivered** — `projects_delivered`
- [ ] **True number of clients** — `happy_clients`
- [ ] **True years in business** — `years_in_software` (also drives the About badge)
- [ ] **A measured satisfaction rate** — `satisfaction_rate`. Only if it is
      actually measured, and worth saying from what sample.
- [ ] **Who awarded "Top-rated Software agency 2025"?** The badge is hidden.
      Set `award.awarded_by` and it returns with the source shown beside it.

The Why GoSoftware panel that carried the 98% now reads **"Straight answers, in
writing — fixed scope and price agreed before we start, and a reply within one
working day."** Both of those are commitments already made elsewhere on the
site, so they are yours to keep rather than mine to invent. Say the word if you
would rather it said something else.

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

## Performance (CRO-21) — partly done, one piece needs a decision

Page weight on the home page went from **817 KB to 151 KB** and LCP from 616ms
to 440ms (measured locally, so treat the absolute times as indicative — the
*ratio* is the real result). Two things did it: the Google Maps embed no longer
loads until someone asks for it, and the Arabic font is no longer served to
English visitors.

- [ ] **WebP conversion and responsive srcset are NOT done.** Every image on
      the site is uploaded through the admin panel, so this needs a conversion
      step at upload time plus a backfill command for existing files — and it
      changes how uploads are stored, which I did not want to do without
      asking. It matters much less than it did: with the map gone the page is
      151 KB, and there is currently almost no photography on the site anyway
      (see the Photography section). **My recommendation: send the photos
      first, then I will add WebP conversion and srcset in one pass, sized to
      the real images rather than guessed.**

---

## Trust plumbing (CRO-23)

Everything below is wired and hidden. Each appears the moment you fill it in —
no code change, and nothing unsourced renders in the meantime.

- [ ] **Legal entity name and company registration number.** Set `legal_name`
      and `registration_number` in **`config/company.php`** and they appear
      under the footer copyright. Their absence is conspicuous to anyone
      comparing suppliers, and it is the cheapest trust signal available.

- [ ] **Post-launch warranty: confirm "30 days of free bug fixes".**
      Set `warranty_days` in `config/company.php` to the number of days and the
      statement appears on every service page, next to the code-ownership line,
      in all three languages. Left null it is not claimed anywhere — a warranty
      is a commitment for you to make, not one I can make on your behalf. **No
      local competitor publishes one**, so this is a real differentiator.

- [ ] **Support tiers with response times.** Fill `support_tiers` in
      `config/company.php` — name, price, response time, and what is included —
      and a comparison table appears on the support page. Currently the page
      says response times are written into the agreement, which is true but
      weaker than publishing them.

- [ ] **A team section.** Not built — see below. Even three engineers with
      first names, roles and real photos would substantiate the "dedicated
      teams / senior engineers" claim, which currently has no evidence behind
      it. I need the names, roles and photos before building it, since the
      whole point is that it is real.

**Already done, no input needed:** the IP-ownership statement — *"You own the
code and the data. Full source and credentials handed over at launch."* — now
appears on all seven service pages in all three languages, and as FAQ answer 5.

---

## Project case studies (CRO-14) — the biggest content gap

Seven projects had no detail page at all. Four linked straight off-site (App
Store, shopp.krd, powerorbits.com, climax.krd) with no way back, and three had
no link whatsoever. The section that should create the most desire was the one
causing the most exits.

The pages are built. **Each one turns itself on as soon as it has content** —
fill in the problem, what you built, or the result at **Admin → Projects** and
that project gets a page at `/projects/<slug>`, its tile starts linking there
instead of off-site, and the live-site link becomes a "Visit live site" button
*inside* the page. Leave a project empty and nothing changes for it — no empty
page is ever linked.

Every section renders only if it has content, so nothing is invented to fill a
gap. For each of the seven projects I need:

| Field | What it is |
|---|---|
| **Industry** | Retail · Restaurants · Academies · Real estate · Delivery · E-commerce · Services. This is now the **primary** filter — buyers recognise their own industry long before they recognise "web app". |
| **Client name** | As you are permitted to name them. |
| **One-line outcome** | The hero line. e.g. "Cut monthly stock-taking from three days to two hours." |
| **The problem** | What was going wrong before. A paragraph or two. |
| **What we built** | The solution, in plain terms. |
| **The result** | 2–3 **quantified** outcomes. This is the section that sells. |
| **Screenshots** | 3–5 per project. Upload in Media first, then paste the paths. |
| **Technology** | Comma separated; renders as tags. |
| **Platforms / timeline / live since** | For the "At a glance" bar. |
| **Client quote** | Only with permission. Needs the quote, who said it, and their role. |

The seven: **KurdGPT · Folivya Academy · Fantasy Town · Asaari · Zuu · Power
Orbits · Climax**.

If that is too much at once, do **two** properly — ideally the two with the
best numbers. Two real case studies beat seven thin ones, and the rest can
keep their current tiles until you get to them.

---

## Testimonials (CRO-15) — nothing is rendered until you supply real ones

The section was headed **TESTIMONIALS** and contained a client logo marquee,
duplicated twice, with no quote, no name and no result. The heading primes a
visitor to look for endorsement and gives them none, which is worse than not
having the section.

It is now honestly headed **"Companies we work with"** and shows the logos once,
statically. The three seeded quotes ("Tom Harding", "Priya Nair", "Sarah Doyle")
were invented by nobody and have been deleted. **The quotes area does not render
at all** until a real testimonial exists.

- [ ] **Three client testimonials, with permission to publish.** For each:
      - Name and role
      - Company
      - The quote itself
      - **One concrete result** — "monthly stock-taking went from three days to
        two hours" persuades far more than five stars. There is a dedicated
        field for it and it renders in its own highlighted block.
      - A photo of the person (square, min 200×200) — optional; without one the
        card shows their initial rather than a stock face.
      - **Optional 30–45s phone video.** You mentioned filming with Folivya,
        Fantasy Town and Zuu — the field is built and takes a YouTube or Vimeo
        **embed** URL. A video clip outperforms any written quote.

      Add them at **Admin → Testimonials**. Each appears the moment it is saved.

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
