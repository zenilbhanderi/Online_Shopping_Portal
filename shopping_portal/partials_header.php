<?php require_once __DIR__.'/config.php'; require_once __DIR__.'/functions.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>ShopEasy</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: { light:'#ead9c9', DEFAULT:'#b08968', dark:'#7f5539' }
          },
          boxShadow: { neon: '0 0 10px rgba(245, 158, 11, .7), 0 0 20px rgba(245, 158, 11, .5)' },
          keyframes: {
            ripple:{'0%':{transform:'scale(0)',opacity:1},'100%':{transform:'scale(4)',opacity:0}},
            pop:{'0%':{transform:'scale(1)'},'50%':{transform:'scale(1.08)'},'100%':{transform:'scale(1)'}}
          },
          animation: { ripple:'ripple .6s ease-out', pop:'pop .25s ease-out' }
        }
      }
    }
  </script>
  <link rel="stylesheet" href="/shopping_portal/assets/css/style.css"/>
  <script defer src="/shopping_portal/assets/js/animations.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50 text-gray-800">
  <header class="bg-gradient-to-r from-slate-900 via-gray-900 to-slate-900 text-white sticky top-0 z-40 shadow-2xl backdrop-blur border-b border-amber-500/20">
    <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
      <a href="/index.php" class="text-3xl font-black bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent hover:scale-105 transition-transform duration-300">ShopEasy</a>
      <nav class="space-x-6 hidden sm:flex items-center">
        <a href="/index.php" class="px-4 py-2 rounded-full hover:bg-white/10 transition-all duration-300 font-medium">Home</a>
        <a href="/cart.php" class="px-4 py-2 rounded-full hover:bg-white/10 transition-all duration-300 font-medium flex items-center gap-2">Cart <span class='cart-count px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-bold shadow-lg animate-pulse'><?php echo cart_count(); ?></span> 🛒</a>
        <?php if(is_logged_in()): ?>
          <span class="px-4 py-2 bg-gradient-to-r from-emerald-600/20 to-green-600/20 rounded-full border border-emerald-400/30 text-emerald-300 font-medium">Hi, <?php echo e($_SESSION['user']['name']); ?> 👋</span>
          <?php if(!is_admin()): ?><a href="/my_orders.php" class="px-4 py-2 rounded-full hover:bg-blue-600/20 border border-blue-400/30 transition-all duration-300 text-blue-300 font-medium">My Orders</a><?php endif; ?>
          <?php if(is_admin()): ?><a href="/admin/dashboard.php" class="px-4 py-2 rounded-full hover:bg-purple-600/20 border border-purple-400/30 transition-all duration-300 text-purple-300 font-bold">Admin ⚡</a><?php endif; ?>
          <a href="/logout.php" class="px-4 py-2 rounded-full hover:bg-red-600/20 border border-red-400/30 transition-all duration-300 text-red-300 font-medium">Logout</a>
        <?php else: ?>
          <a href="/login.php" class="px-6 py-2 rounded-full bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold transition-all duration-300 shadow-lg hover:shadow-neon">Login</a>
          <a href="/register.php" class="px-6 py-2 rounded-full border-2 border-amber-400 hover:bg-amber-400/10 text-amber-300 font-bold transition-all duration-300">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>
  <main class="max-w-6xl mx-auto p-4">