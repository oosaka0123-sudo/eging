(()=>{const q=(s,c=document)=>c.querySelector(s),qa=(s,c=document)=>[...c.querySelectorAll(s)];const body=document.body,menu=q('#mobileMenu'),btn=q('#menuBtn');
if(btn&&menu){btn.addEventListener('click',()=>{const o=menu.classList.toggle('open');body.classList.toggle('menu-open',o);btn.setAttribute('aria-expanded',String(o));btn.setAttribute('aria-label',o?'メニューを閉じる':'メニューを開く')});qa('a',menu).forEach(a=>a.addEventListener('click',()=>{menu.classList.remove('open');body.classList.remove('menu-open');btn.setAttribute('aria-expanded','false')}))}
const opening=q('#opening');if(opening){const seen=sessionStorage.getItem('egl-opening');const exit=()=>{opening.classList.add('exit');opening.setAttribute('aria-hidden','true');const skip=q('[data-skip]',opening);if(skip)skip.tabIndex=-1;sessionStorage.setItem('egl-opening','1');setTimeout(()=>opening.remove(),700)};q('[data-skip]',opening)?.addEventListener('click',exit);if(seen)opening.remove();else setTimeout(exit,900)}
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.14});qa('.reveal').forEach(el=>io.observe(el));

if(!matchMedia('(prefers-reduced-motion: reduce)').matches){
  qa('a[href]').forEach(a=>{
    const href=a.getAttribute('href');
    if(!href||href.startsWith('#')||href.startsWith('mailto:')||a.target==='_blank')return;
    let target;
    try{target=new URL(href,location.href)}catch{return}
    if(target.origin!==location.origin||target.pathname===location.pathname&&target.search===location.search)return;
    a.addEventListener('click',e=>{
      if(e.metaKey||e.ctrlKey||e.shiftKey||e.altKey||e.button!==0)return;
      e.preventDefault();
      body.classList.add('page-leaving');
      setTimeout(()=>{location.href=target.href},260);
    });
  });
}
const heroVisual=q('.hero__visual');if(heroVisual&&!matchMedia('(prefers-reduced-motion: reduce)').matches){let raf=0,active=true;const move=e=>{if(!active)return;cancelAnimationFrame(raf);raf=requestAnimationFrame(()=>{const x=(e.clientX/innerWidth-.5)*8,y=(e.clientY/innerHeight-.5)*8;heroVisual.style.transform=`translate3d(${x}px,${y}px,0) scale(1.015)`})};addEventListener('pointermove',move,{passive:true});new IntersectionObserver(([entry])=>{active=entry.isIntersecting;if(!active)heroVisual.style.transform=''}, {threshold:0}).observe(heroVisual)}
const form=q('#quickCondition'),out=q('#quickResult');let controller=null;if(form&&out){form.addEventListener('submit',async e=>{e.preventDefault();controller?.abort();controller=new AbortController();const submit=q('button[type="submit"]',form);submit.disabled=true;out.textContent='海況条件を照合中…';const fd=new FormData(form),payload={season:fd.get('season'),field_type:fd.get('field'),depth_band:fd.get('depth'),wind_band:fd.get('wind'),tide_phase:fd.get('tide'),target_size:fd.get('target_size'),time_of_day:fd.get('time_of_day')};try{const res=await fetch('/api/simulate.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload),signal:controller.signal});const data=await res.json();if(!res.ok){if(data.error==='no_rule'){out.innerHTML='この条件はまだ判定データがありません。<a href="/guide.php">条件ガイドを見る</a>';return}throw new Error(data.error||'request_failed')}const o=data.output||{};out.innerHTML=`<strong>推奨レンジ</strong><br>エギ ${o.egi||'—'} / ロッド ${o.rod||'—'} / PE ${o.pe||'—'} / リーダー ${o.leader||'—'}<br><small>${data.rationale||''}</small>`}catch(err){if(err.name!=='AbortError')out.innerHTML='現在、診断データを読み込めません。<a href="/guide.php">ギア選びの基礎を見る</a>'}finally{submit.disabled=false}})}
})();