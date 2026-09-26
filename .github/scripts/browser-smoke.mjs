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
    if (m.type() === 'error') errors.push('console: ' + m.text());
  });

  for (const path of paths) {
    errors.length = 0;
    const response = await page.goto(base + path, { waitUntil: 'domcontentloaded' });
    const status = response?.status() ?? 0;
    const h1 = await page.locator('h1').count();
    const overflow = await page.evaluate(
      () => document.documentElement.scrollWidth > document.documentElement.clientWidth + 2
    );

    if (status >= 500 || h1 < 1 || overflow || errors.length) {
      failed = true;
      console.error(JSON.stringify({
        viewport: viewport.name, path, status, h1, overflow, errors
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
