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
