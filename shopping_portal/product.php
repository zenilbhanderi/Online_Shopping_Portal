<?php include 'partials_header.php';
$id = (int)($_GET['id'] ?? 0);
$p = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if(!$p){ echo "<p class='text-red-600'>Product not found.</p>"; include 'partials_footer.php'; exit; }
?>
<a href="index.php" class="text-black underline hover:text-amber-700">← Back</a>
<div class="grid md:grid-cols-2 gap-6 mt-4">
  <img src="assets/images/<?php echo e($p['image']); ?>" class="w-full rounded-xl border border-amber-200 bg-amber-50" alt="">
  <div>
    <h1 class="text-3xl font-extrabold text-black"><?php echo e($p['name']); ?></h1>
    <p class="text-gray-800 mt-2"><?php echo e($p['description']); ?></p>
    <p class="mt-4 text-2xl font-bold text-black"><?php echo format_money($p['price']); ?></p>
    <form method="post" action="cart.php" class="mt-6">
      <input type="hidden" name="add" value="<?php echo $p['id']; ?>"/>
      <button class="relative btn-ripple bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg shadow-neon transition">Add to Cart 🛒</button>
    </form>
  </div>
</div>
<?php include 'partials_footer.php'; ?>
