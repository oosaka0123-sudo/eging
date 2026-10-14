(()=>{'use strict';
const body=document.body,menu=document.getElementById('mobileMenu'),btn=document.getElementById('menuBtn');
let savedFocus=null;
function setOpen(open){
 if(!menu||!btn)return;
 menu.classList.toggle('open',open);
 menu.setAttribute('aria-hidden',String(!open));
 menu.inert=!open;
 btn.setAttribute('aria-expanded',String(open));
 btn.setAttribute('aria-label',open?'メニューを閉じる':'メニューを開く');
 body.classList.toggle('egl-menu-open',open);
 if(open){savedFocus=document.activeElement;menu.querySelector('a')?.focus();}
 else if(savedFocus instanceof HTMLElement){savedFocus.focus();savedFocus=null;}
}
btn?.addEventListener('click',()=>setOpen(!menu?.classList.contains('open')));
menu?.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>setOpen(false)));
document.addEventListener('keydown',e=>{
 if(e.key==='Escape' && menu?.classList.contains('open'))setOpen(false);
 if(e.key==='Tab'&&menu?.classList.contains('open')){
  const a=[...menu.querySelectorAll('a:not([hidden])')];if(!a.length)return;
  if(e.shiftKey&&document.activeElement===a[0]){e.preventDefault();a[a.length-1].focus()}
  else if(!e.shiftKey&&document.activeElement===a[a.length-1]){e.preventDefault();a[0].focus()}
 }
});
const header=document.getElementById('siteHeader');
function updateHeader(){header?.setAttribute('data-scrolled',String(scrollY>30))}
window.addEventListener('scroll',updateHeader,{passive:true});updateHeader();
let timer;const toast=document.getElementById('eglToast');
function showToast(text){if(!toast)return;toast.textContent=text;toast.classList.add('is-visible');clearTimeout(timer);timer=setTimeout(()=>toast.classList.remove('is-visible'),2600)}
const share=document.getElementById('shareSite');
share?.addEventListener('click',async()=>{
 const data={title:document.title,text:'エギングギアラボ｜条件から選ぶ、エギングギア。',url:location.href};
 if(navigator.share){try{await navigator.share(data);return}catch(e){if(e?.name==='AbortError')return}}
 try{
  if(navigator.clipboard?.writeText){await navigator.clipboard.writeText(data.url)}
  else{const t=document.createElement('textarea');t.value=data.url;t.style.position='fixed';t.style.left='-2000px';document.body.append(t);t.select();if(!document.execCommand('copy'))throw Error('copy_failed');t.remove()}
  showToast('ページのリンクをコピーしました');
 }catch(e){showToast('ブラウザのURLをコピーして共有してください')}
});
})();
