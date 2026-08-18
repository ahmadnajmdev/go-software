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

## Assets

- [ ] **The founder photo is missing from disk.**
      Found while verifying pages in a browser: the `images.founder` setting
      points at `storage/uploads/2026/08/cECgg6a8Z9Gf00W8h6AAidkOzxukWzHFWotcRM5V.jpg`,
      which **returns 403 on every page load** — the file is not there. This is the
      only console error on the site. It matters for CRO-11, which plans to use
      the real founder photo in place of the Unsplash stock images.
      Needed: the original photo, re-uploaded through the admin media panel.
