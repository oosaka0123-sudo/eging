import { chromium } from 'playwright';

const base = 'http://127.0.0.1:8080';
const scenes = [
  { query: '', expected: 5 },
  { query: '?scene=shallow', expected: 3 },
  { query: '?scene=standard', expected: 3 },
  { query: '?scene=current', expected: 3 }
];
const models = [
  'yamashita-k-basic',
  'yamashita-k-shallow',
  'yamashita-k-super-shallow',
  'shimano-clinch-flashboost',
  'daiwa-amorous-joint',
  'shimano-clinch-deep-flashboost'
];
const browser = await chromium.launch({ headless: true });
try {
  for (const viewport of [{width:390,height:844},{width:1440,height:900}]) {
    const page = await browser.newPage({viewport});
    const home = await page.goto(base+'/',{waitUntil:'networkidle'});
    if (home.status() !== 200) throw Error('home status '+home.status());
    if (await page.locator('.opening').count() !== 0) throw Error('opening still blocks');
    if (await page.locator('#quickCondition').count() !== 0) throw Error('broken database diagnosis still on home');
    if (await page.locator('.egl-scene').count() !== 3) throw Error('expected three field choices');
    if (!await page.locator('h1').innerText().then(s=>s.includes('エギ'))) throw Error('home unclear');

    for (const {query,expected} of scenes) {
      const response=await page.goto(base+'/compare.php'+query,{waitUntil:'networkidle'});
      if (response.status() !== 200) throw Error('compare not 200 '+query);
      const count=await page.locator('article.egl-product').count();
      if (count !== expected) throw Error('compare '+query+' expected '+expected+' got '+count);
      const tableCount=await page.locator('.egl-spec-table tbody tr').count();
      if (tableCount!==expected) throw Error('comparison table differs from cards for '+query);
      if (query.includes('current')) {
        const target=page.locator('article.egl-product a[href*="shimano-clinch-deep-flashboost"]');
        if(await target.count()!==1)throw Error('deep-water scene must include deep model');
        if(!(await page.locator('body').innerText()).includes('1.9')) throw Error('deep-water 1.9s/m not visible');
      }

      const officialLinks = page.locator('article.egl-product .egl-product__footer a');
      if (await officialLinks.count() !== expected) throw Error('missing manufacturer photo links for '+query);
      for (const official of await officialLinks.all()) {
        const label=await official.innerText();
        const href=await official.getAttribute('href');
        if (!label.includes('製品写真') || !href?.startsWith('https://')) throw Error('bad official photo link for '+query);
      }
      if (await page.locator('.gear-compare-photo-guides').count() !== 0) throw Error('unrelated placeholder gallery was not removed');
      const emptyPhotoSlots = await page.locator('.egl-product__visual-label').count();
      const imageSlots = await page.locator('.egl-product__visual img').count();
      if (emptyPhotoSlots + imageSlots !== expected) throw Error('every product must have photo or honest empty state');
      for (const img of await page.locator('.egl-product__visual img').all()) {
        if (!await img.evaluate(el=>el.complete && el.naturalWidth > 0)) throw Error('broken product photo');
      }

      if ((await page.locator('body').innerText()).includes('商品データを更新中です')) throw Error('DB failure leaked into public comparison');
      if (await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth+3)) throw Error('horizontal overflow compare at '+viewport.width);
      const link=await page.locator('article.egl-product a.egl-product__grid').first().getAttribute('href');
      if (!link.startsWith('/gear-compare-item.php?model='))throw Error('comparison item does not link to detail');
      const details=await page.goto(base+link,{waitUntil:'networkidle'});
      if(details.status()!==200)throw Error('detail invalid '+link);
      if (await page.locator('.egl-detail-specs div').count()<6)throw Error('missing product specs');
      if (await page.locator('a[href^="https://"]').count()===0)throw Error('no official product source');
    }
    for(const model of models){
      const res=await page.goto(base+'/gear-compare-item.php?model='+model,{waitUntil:'domcontentloaded'});
      if(res.status()!==200)throw Error('detail '+model+' '+res.status());
      if(await page.locator('h1').count()!==1)throw Error('bad detail headline');
      if(await page.locator('.egl-detail-photo').count()!==1)throw Error('no product photo/place holder');
    }
    const bad=await page.goto(base+'/gear-compare-item.php?model=not-a-product',{waitUntil:'domcontentloaded'});
    if(bad.status()!==404)throw Error('unknown model must return 404');
    const redirect=await page.goto(base+'/simulator.php',{waitUntil:'domcontentloaded'});
    if(redirect.status()!==200 || !page.url().includes('/conditions.php'))throw Error('legacy simulator lacks safe redirect');
    if(viewport.width<=390){
      await page.goto(base+'/');
      await page.click('#menuBtn');
      if(await page.locator('#menuBtn').getAttribute('aria-expanded')!=='true')throw Error('menu opening failed');
      if(await page.locator('#mobileMenu').getAttribute('aria-hidden')!=='false')throw Error('menu aria-hidden wrong');
      await page.keyboard.press('Escape');
      if(await page.locator('#menuBtn').getAttribute('aria-expanded')!=='false')throw Error('ESC did not close menu');
    }
    await page.close();
  }
  console.log('Condition-to-choice E2E checks passed for 390px/1440px, 4 scenes and 5 product details.');
} finally {
  await browser.close();
}
