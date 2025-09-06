<?php 
include 'config.php'; 
include 'partials_header.php';
$cats = $conn->query("SELECT * FROM categories ORDER BY id LIMIT 4");
$featured = $conn->query("SELECT p.*, c.name AS cat FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC LIMIT 8");
?>

<style>
:root{--primary:#f59e0b;--primary-dark:#d97706;--glass:rgba(255,255,255,.95);--shadow:0 25px 50px -12px rgba(0,0,0,.25);--glow:0 0 30px rgba(245,158,11,.3)}
*{scroll-behavior:smooth}
body{background:linear-gradient(135deg,#f8fafc,#f1f5f9,#e2e8f0);overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(circle at 20% 80%,rgba(59,130,246,.05) 0%,transparent 50%),radial-gradient(circle at 80% 20%,rgba(168,85,247,.05) 0%,transparent 50%),radial-gradient(circle at 50% 50%,rgba(245,158,11,.02) 0%,transparent 70%);z-index:-1;animation:breathe 25s infinite}
@keyframes breathe{0%,100%{opacity:.6}50%{opacity:1}}
@keyframes float{0%,100%{transform:translate(0,0) rotate(0deg)}33%{transform:translate(-15px,-20px) rotate(120deg)}66%{transform:translate(15px,-25px) rotate(240deg)}}
@keyframes floatReverse{0%,100%{transform:translate(0,0) rotate(0deg)}33%{transform:translate(20px,15px) rotate(-120deg)}66%{transform:translate(-25px,20px) rotate(-240deg)}}
@keyframes fade{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideLeft{from{opacity:0;transform:translateX(-60px)}to{opacity:1;transform:translateX(0)}}
@keyframes slideRight{from{opacity:0;transform:translateX(60px)}to{opacity:1;transform:translateX(0)}}
@keyframes scale{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}
@keyframes bounce{0%,20%,50%,80%,100%{transform:translateY(0)}40%{transform:translateY(-8px)}60%{transform:translateY(-4px)}}
.float{position:fixed;border-radius:50%;filter:blur(12px);z-index:-1}
.float:nth-child(1){top:15%;left:8%;width:80px;height:80px;background:linear-gradient(135deg,rgba(59,130,246,.12),rgba(147,51,234,.12));animation:float 15s infinite}
.float:nth-child(2){top:70%;right:15%;width:60px;height:60px;background:linear-gradient(135deg,rgba(168,85,247,.1),rgba(236,72,153,.1));animation:floatReverse 12s infinite}
.float:nth-child(3){bottom:30%;left:25%;width:100px;height:100px;background:linear-gradient(135deg,rgba(16,185,129,.08),rgba(245,158,11,.08));animation:float 18s infinite}
.float:nth-child(4){top:40%;right:35%;width:40px;height:40px;background:linear-gradient(135deg,rgba(245,158,11,.15),rgba(239,68,68,.15));animation:floatReverse 10s infinite}
.fade{animation:fade 1s cubic-bezier(.25,.8,.25,1)}
.slide-left{animation:slideLeft 1s cubic-bezier(.25,.8,.25,1)}
.slide-right{animation:slideRight 1s cubic-bezier(.25,.8,.25,1)}
.scale{animation:scale .8s cubic-bezier(.25,.8,.25,1)}
.card{background:var(--glass);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.2);box-shadow:var(--shadow)}
.card:hover{transform:translateY(-8px);box-shadow:var(--shadow),var(--glow)}
.hero-gradient{background:linear-gradient(135deg,#0f0f23 0%,#1e1b4b 25%,#581c87 50%,#7c2d12 75%,#1f2937 100%);position:relative}
.hero-gradient::before{content:'';position:absolute;inset:0;background:linear-gradient(45deg,transparent 30%,rgba(245,158,11,.1) 50%,transparent 70%);animation:shimmer 4s linear infinite;background-size:200% 200%}
.text-gradient{background:linear-gradient(135deg,#ffffff 0%,#fbbf24 30%,#f59e0b 60%,#ea580c 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.stats-card{transform:perspective(1000px) rotateX(5deg);transition:all .4s cubic-bezier(.25,.8,.25,1)}
.stats-card:hover{transform:perspective(1000px) rotateX(0deg) scale(1.05);box-shadow:var(--shadow),0 0 40px rgba(245,158,11,.2)}
.category-card{position:relative;overflow:hidden}
.category-card::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent 0%,rgba(245,158,11,.05) 50%,transparent 100%);transform:translateX(-100%);transition:transform .6s ease}
.category-card:hover::before{transform:translateX(100%)}
.product-card{position:relative;overflow:hidden}
.product-card .quick-view{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(.8);opacity:0;transition:all .3s cubic-bezier(.25,.8,.25,1)}
.product-card:hover .quick-view{opacity:1;transform:translate(-50%,-50%) scale(1)}
.btn-primary{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);transition:all .3s cubic-bezier(.25,.8,.25,1);position:relative;overflow:hidden}
.btn-primary::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,transparent 0%,rgba(255,255,255,.2) 50%,transparent 100%);transform:translateX(-100%);transition:transform .6s ease}
.btn-primary:hover::before{transform:translateX(100%)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 20px 40px rgba(245,158,11,.3)}
</style>

<div class="float"></div>
<div class="float"></div>
<div class="float"></div>
<div class="float"></div>

<section class="mt-6 fade">
  <div class="hero-gradient rounded-3xl p-12 text-white shadow-2xl relative overflow-hidden">
    <div class="absolute inset-0 opacity-15">
      <div class="absolute top-10 left-10 w-40 h-40 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full blur-2xl animate-pulse"></div>
      <div class="absolute bottom-10 right-10 w-32 h-32 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full blur-2xl" style="animation:bounce 2s infinite"></div>
      <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full blur-xl" style="animation:pulse 3s infinite"></div>
    </div>
    <div class="relative z-10">
      <h1 class="text-5xl lg:text-7xl font-black text-gradient mb-6 slide-left">Discover Premium Finds</h1>
      <p class="text-slate-200 text-xl mb-8 max-w-2xl slide-right" style="animation-delay:.2s">Curated categories with elegant style ✨ Experience luxury at its finest with unmatched quality</p>
      <a href="#categories" class="inline-flex items-center px-10 py-5 btn-primary rounded-2xl font-bold text-lg hover:scale-105 transition-all duration-300 group scale" style="animation-delay:.4s">
        <span class="mr-2">Shop Collection</span>
        <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>
  </div>
</section>

<section class="mt-16 fade" style="animation-delay:.6s">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    <?php $stats=['10K+'=>'Happy Customers','99%'=>'Satisfaction Rate','24/7'=>'Customer Support','Free'=>'Fast Shipping'];$i=0;foreach($stats as $k=>$v):?>
    <div class="stats-card text-center p-8 card rounded-2xl" style="animation:scale .8s cubic-bezier(.25,.8,.25,1) <?=.8+$i*.1?>s both">
      <div class="text-4xl font-black bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent mb-3"><?=$k?></div>
      <div class="text-slate-700 font-semibold text-lg"><?=$v?></div>
    </div>
    <?php $i++;endforeach?>
  </div>
</section>

<section id="categories" class="mt-24">
  <div class="text-center mb-20 fade" style="animation-delay:1s">
    <h2 class="text-6xl font-black bg-gradient-to-r from-slate-900 via-amber-600 to-orange-600 bg-clip-text text-transparent mb-6">Explore by Categories</h2>
    <p class="text-slate-600 text-xl max-w-3xl mx-auto leading-relaxed">Discover our carefully curated selection of premium products, each category crafted to perfection</p>
    <div class="mt-6 w-24 h-1 bg-gradient-to-r from-amber-400 to-orange-500 mx-auto rounded-full"></div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    <?php $d=200;while($c=$cats->fetch_assoc()):?>
      <a href="category.php?id=<?=$c['id']?>" class="category-card group card rounded-3xl p-8 hover:shadow-2xl hover:-translate-y-6 transition-all duration-500" style="animation:fade 1s cubic-bezier(.25,.8,.25,1) <?=$d?>ms both">
        <div class="h-36 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl mb-6 flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-amber-400 group-hover:to-orange-500 transition-all duration-500 relative overflow-hidden">
          <span class="text-slate-700 group-hover:text-white font-bold text-2xl z-10 transition-colors duration-500"><?=e($c['name'])?></span>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent transform -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
        </div>
        <p class="text-slate-600 mb-6 leading-relaxed"><?=e($c['description'])?></p>
        <div class="flex items-center text-amber-600 font-bold group-hover:text-orange-600 transition-colors duration-300">
          <span>Explore Collection</span>
          <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>
    <?php $d+=100;endwhile?>
  </div>
</section>

<section class="mt-28">
  <div class="text-center mb-20 fade" style="animation-delay:1.2s">
    <h2 class="text-6xl font-black bg-gradient-to-r from-slate-900 via-amber-600 to-orange-600 bg-clip-text text-transparent mb-6">Featured Luxury</h2>
    <p class="text-slate-600 text-xl max-w-3xl mx-auto leading-relaxed">Handpicked premium products that define excellence and sophistication</p>
    <div class="mt-6 w-24 h-1 bg-gradient-to-r from-amber-400 to-orange-500 mx-auto rounded-full"></div>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
    <?php $d=300;while($p=$featured->fetch_assoc()):?>
      <div class="product-card card rounded-3xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-500 group" style="animation:fade 1s cubic-bezier(.25,.8,.25,1) <?=$d?>ms both">
        <a href="product.php?id=<?=$p['id']?>" class="block">
          <div class="relative overflow-hidden h-64">
            <img src="assets/images/<?=e($p['image'])?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?=e($p['name'])?>">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="quick-view">
              <button class="bg-white/95 text-slate-800 px-8 py-3 rounded-full font-bold backdrop-blur-sm hover:bg-white hover:scale-105 transition-all duration-300">Quick View</button>
            </div>
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
              <div class="w-10 h-10 bg-white/20 rounded-full backdrop-blur-sm flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
              </div>
            </div>
          </div>
          <div class="p-6">
            <span class="text-slate-500 text-sm bg-slate-100 px-3 py-1 rounded-full font-medium"><?=e($p['cat'])?></span>
            <h3 class="font-bold text-slate-800 text-lg mt-4 mb-3 group-hover:text-amber-700 transition-colors duration-300 line-clamp-2"><?=e($p['name'])?></h3>
            <div class="flex items-center justify-between mb-4">
              <div class="text-2xl font-black text-slate-800"><?=format_money($p['price'])?></div>
              <div class="text-slate-500 line-through text-sm"><?=format_money($p['price']*1.3)?></div>
            </div>
          </div>
        </a>
        <form method="post" action="cart.php" class="p-6 pt-0">
          <input type="hidden" name="add" value="<?=$p['id']?>"/>
          <button class="w-full btn-primary text-white py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300">Add to Cart</button>
        </form>
      </div>
    <?php $d+=80;endwhile?>
  </div>
</section>

<section class="mt-28 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-16 text-white relative overflow-hidden fade" style="animation-delay:1.8s">
  <div class="absolute inset-0 opacity-10">
    <div class="absolute top-8 right-8 w-40 h-40 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full blur-2xl animate-pulse"></div>
    <div class="absolute bottom-8 left-8 w-32 h-32 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full blur-xl" style="animation:pulse 4s infinite"></div>
  </div>
  <div class="relative z-10 text-center max-w-3xl mx-auto">
    <h3 class="text-4xl font-black mb-6 text-gradient">Stay Updated with Latest Arrivals</h3>
    <p class="text-slate-300 text-lg mb-10 leading-relaxed">Subscribe to our newsletter and be the first to know about new products, exclusive offers, and luxury collections delivered straight to your inbox.</p>
    <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
      <input type="email" placeholder="Enter your email address" class="flex-1 px-6 py-4 rounded-xl text-slate-800 bg-white/95 focus:ring-4 focus:ring-amber-500/50 outline-none transition-all duration-300 backdrop-blur-sm font-medium">
      <button type="submit" class="px-10 py-4 btn-primary rounded-xl font-bold hover:scale-105 transition-all duration-300 whitespace-nowrap">Subscribe Now</button>
    </form>
    <p class="text-slate-400 text-sm mt-6">Join 50,000+ subscribers. No spam, unsubscribe anytime.</p>
  </div>
</section>

<?php include 'partials_footer.php'; ?>