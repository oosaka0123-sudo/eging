import { chromium } from 'playwright';

const base = 'http://127.0.0.1:8080';
const paths = [
  '/', '/gear.php', '/conditions.php', '/simulator.php',
  '/compare.php', '/guide.php', '/concept.php',
  '/contact.php', '/privacy.php', '/404.php'
];
const viewports = [
  { name: 'mobile390', width: 390, height: 844 },
  { name: 'desktop1440', width: 1440, height: 1000 }
];

const browser = await chromium.launch({ headless: true });
let failed = false;

for (const viewport of viewports) {
  const context = await browser.newContext({ viewport });
  const page = await context.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push('pageerror: ' + e.message));
  page.on('console', m => {
    if (m.type() === 'error' && !m.text().startsWith('Failed to load resource:')) {
      errors.push('console: ' + m.text());
    }
  });
  page.on('response', response => {
    const url = response.url();
    const status = response.status();
    const allowedMissing = url.endsWith('/assets/video/opening.mp4');
    if (status >= 400 && !allowedMissing) {
      errors.push('http ' + status + ': ' + url);
    }
  });

  for (const path of paths) {
    errors.length = 0;
    const response = await page.goto(base + path, { waitUntil: 'domcontentloaded' });
    const status = response?.status() ?? 0;
    const audit = await page.evaluate(() => {
      const title = document.title.trim();
      const description = document.querySelector('meta[name="description"]')?.getAttribute('content')?.trim() || '';
      const canonical = document.querySelector('link[rel="canonical"]')?.getAttribute('href') || '';
      const manifest = document.querySelector('link[rel="manifest"]')?.getAttribute('href') || '';
      const h1 = document.querySelectorAll('h1').length;
      const unlabeledImages = [...document.querySelectorAll('img')].filter(img => !img.hasAttribute('alt')).length;
      const invalidFormControls = [...document.querySelectorAll('input:not([type="hidden"]), textarea, select')].filter(el => {
        if (el.getAttribute('aria-hidden') === 'true') return false;
        const id = el.id;
        return !el.closest('label') && !(id && document.querySelector('label[for="' + CSS.escape(id) + '"]')) && !el.getAttribute('aria-label');
      }).length;
      const overflow = document.documentElement.scrollWidth > document.documentElement.clientWidth + 2;
      return { title, description, canonical, manifest, h1, unlabeledImages, invalidFormControls, overflow };
    });

    const semanticError =
      !audit.title ||
      !audit.description ||
      !audit.canonical.startsWith('https://eging.rss7.net') ||
      audit.manifest !== '/manifest.webmanifest' ||
      audit.h1 < 1 ||
      audit.unlabeledImages > 0 ||
      audit.invalidFormControls > 0;

    if (status >= 500 || audit.overflow || semanticError || errors.length) {
      failed = true;
      console.error(JSON.stringify({
        viewport: viewport.name, path, status, audit, errors
      }));
    }
  }

  if (viewport.width <= 430) {
    await page.goto(base + '/', { waitUntil: 'domcontentloaded' });
    await page.click('#menuBtn');
    const open = await page.locator('#mobileMenu').evaluate(el => el.classList.contains('open'));
    if (!open) {
      failed = true;
      console.error('mobile menu failed to open');
    }
  }

  await context.close();
}

await browser.close();
if (failed) process.exit(1);
console.log('Browser smoke checks passed.');
