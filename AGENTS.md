# Project Rules — EGING GEAR LAB

This project follows `oosaka0123-sudo/ai-master`. The master Security / Secret / no fabrication / Council gates have priority.

## Purpose / User Journey

- Mobile-first specialist site: fishing conditions → 3–5 Egi model comparison → single product detail → official evidence / verified purchase destination.
- Visitors must understand the purpose and reach product comparison from the first viewport. Avoid blocking intro video, duplicate condition forms and internal engineering metrics in the primary UX.
- Do not imply that a published 3.5-go model is suitable for every season, small squid, deep water or strong current. Explicitly acknowledge missing deep models.

## Current public data exception (2026-10-14 owner-directed redesign)

- Long-term primary runtime is PHP + MySQL CMS. Preserve database records, private runtime and admin.
- Because MySQL authentication failed with SQLSTATE 1045, `public/_comparison_egis.php` is the **temporary single verified source for public 3.5-go comparisons and details**. Both `compare.php` and `gear-compare-item.php` must consume this shared source. Do not publish separate CMS and static product listings side-by-side.
- Do not reopen the 7-question DB simulator as the primary CTA until API result → actual products passes an E2E check. Keep `/conditions.php` as working alternative.
- Once CMS auth recovers, reconcile every published field and migrate to one source rather than retaining contradictory static/CMS copies.

## Product/commercial content

- All product claims, specs, prices, stock and campaigns must be verifiable and date-aware; use manufacturer values and link to primary sources.
- Do not invent affiliate rankings, field tests, user reviews, photos, prices or EC URLs.
- Product images only after documented legal use rights. Different-product stock photos are never acceptable product photos. No scraped manufacturer photos by default.
- Shopping URLs are affiliate links only when a verified partner-issued URL is present. Regular Yahoo search links are labeled as searches, not partner/product links. Display PR for real affiliate CTAs.
- Never modify shared hosting credentials or delete production records absent the applicable approvals.

## Quality / Publish

- Use `ai-master` and `docs/SITE_DIRECTION_COUNCIL_2026-10-14.md` for decision background, not stale chat summaries.
- Prefer small readable PRs and run CI PHP lint, browser smoke + condition journey tests and Lighthouse.
- Actual deploy uses central `rss7-ai-works` FTPS broker. Report completed only for live-verified URLs and interactions.
- Keep sources, evidence, image rights and advertising status honest; no fabricated completion claims.
