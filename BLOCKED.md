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

## Assets — photography

- [ ] **The founder photo is missing from disk.**
      Found while verifying pages in a browser: the `images.founder` setting
      points at `storage/uploads/2026/08/cECgg6a8Z9Gf00W8h6AAidkOzxukWzHFWotcRM5V.jpg`,
      which **returns 403 on every page load** — the file is not there. This is the
      only console error on the site. It matters for CRO-11, which plans to use
      the real founder photo in place of the Unsplash stock images.
      Needed: the original photo, re-uploaded through the admin media panel.
