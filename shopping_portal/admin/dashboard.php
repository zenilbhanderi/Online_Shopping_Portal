<?php include 'guard.php';
$pc = $conn->query('SELECT COUNT(*) c FROM products')->fetch_assoc()['c'];
$oc = $conn->query('SELECT COUNT(*) c FROM orders')->fetch_assoc()['c'];
$uc = $conn->query('SELECT COUNT(*) c FROM users')->fetch_assoc()['c'];
?><!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<script src="https://cdn.tailwindcss.com"></script><title>Admin Dashboard</title></head>
<body class="bg-[linear-gradient(135deg,#ffffff,#f3e8e1)] text-gray-900">
  <div class="bg-black text-white p-4 flex justify-between items-center sticky top-0">
    <h1 class="text-xl font-bold">Admin Dashboard</h1>
    <div class="space-x-3">
      <a href="categories.php" class="hover:text-amber-300">Categories</a>
      <a href="products.php" class="hover:text-amber-300">Products</a>
      <a href="orders.php" class="hover:text-amber-300">Orders</a>
      <a href="logout.php" class="hover:text-amber-300 underline">Logout</a>
    </div>
  </div>
  <div class="max-w-6xl mx-auto p-6 grid md:grid-cols-3 gap-4">
    <div class="p-6 bg-white border border-amber-200 rounded-xl shadow">
      <p class="text-black font-semibold">Products</p><p class="text-3xl font-extrabold text-black"><?php echo $pc; ?></p>
    </div>
    <div class="p-6 bg-white border border-amber-200 rounded-xl shadow">
      <p class="text-black font-semibold">Orders</p><p class="text-3xl font-extrabold text-black"><?php echo $oc; ?></p>
    </div>
    <div class="p-6 bg-white border border-amber-200 rounded-xl shadow">
      <p class="text-black font-semibold">Users</p><p class="text-3xl font-extrabold text-black"><?php echo $uc; ?></p>
    </div>
  </div>
</body></html>
