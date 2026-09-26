# Launch Checklist

## Before production deploy
- [ ] `Production Preflight` succeeds
- [ ] `LOLIPOP_DEPLOY_DIR` is the verified subdomain public directory
- [ ] FTPS login succeeds
- [ ] MySQL login succeeds
- [ ] production admin password hash is configured
- [ ] main branch CI is green

## First deploy
- [ ] Run `Deploy to Lolipop` manually
- [ ] Confirm `/health.php` returns `"ok":true`
- [ ] Confirm HOME renders `EGING GEAR LAB`
- [ ] Confirm `robots.txt`
- [ ] Confirm `sitemap.php`
- [ ] Confirm `/admin/login.php`
- [ ] Log in and run `/admin/install.php`
- [ ] Confirm required DB tables are all OK
- [ ] Re-test public contact form
- [ ] Confirm simulator handles "no matching rule" cleanly

## Browser / mobile
- [ ] Chrome desktop
- [ ] Chromium CI 390 x 844
- [ ] Chromium CI 1440 x 1000
- [ ] Android 390-class viewport
- [ ] Safari-family check when available
- [ ] Firefox-family check when available
- [ ] Console has no critical errors
- [ ] No horizontal overflow
- [ ] Mobile menu opens/closes
- [ ] Reduced Motion preserves all information

## SEO / accessibility
- [ ] title / description / canonical
- [ ] WebSite structured data
- [ ] Article structured data on published articles
- [ ] favicon / manifest
- [ ] alt attributes
- [ ] form labels
- [ ] 404 page
- [ ] privacy policy
- [ ] editorial / advertising policy

## After launch
- [ ] Run `Production Monitor` manually
- [ ] Only after it succeeds, consider enabling a schedule
- [ ] Submit sitemap to Google Search Console
- [ ] Submit sitemap to Bing Webmaster Tools
- [ ] Add real gear data with official source URL and checked date
- [ ] Do not publish fake reviews, fabricated field tests, or unsupported rankings
