document.addEventListener('click', function(e){
  const t = e.target.closest('.btn-ripple');
  if(!t) return;
  const r = document.createElement('span');
  r.className='pointer-events-none absolute inset-0 rounded-lg animate-ripple bg-amber-400/40';
  t.appendChild(r);
  setTimeout(()=>r.remove(), 650);
});
function cartBounce(){
  const el = document.querySelector('.cart-count');
  if(!el) return;
  el.classList.add('animate-pop');
  setTimeout(()=>{ el.classList.remove('animate-pop'); }, 260);
}
