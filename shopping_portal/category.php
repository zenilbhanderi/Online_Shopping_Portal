<?php include 'partials_header.php';
$id = (int)($_GET['id'] ?? 0);
$cat = $conn->query("SELECT * FROM categories WHERE id=$id")->fetch_assoc();
$prods = $conn->query("SELECT * FROM products WHERE category_id=$id LIMIT 4");
?>
<a href="index.php" class="text-black underline hover:text-amber-700">← Back</a>
<h1 class="text-3xl font-bold text-black mt-2"><?php echo e($cat['name'] ?? 'Category'); ?></h1>
<p class="text-gray-800"><?php echo e($cat['description'] ?? ''); ?></p>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-6">
  <?php while($p=$prods->fetch_assoc()): ?>
    <div class="group bg-white border border-amber-200 rounded-xl overflow-hidden card-tilt hover:shadow-neon">
      <a href="product.php?id=<?php echo $p['id']; ?>">
        <img src="assets/images/<?php echo e($p['image']); ?>" class="w-full h-40 object-cover bg-amber-50" alt="">
        <div class="p-4">
          <h3 class="font-semibold text-black group-hover:underline"><?php echo e($p['name']); ?></h3>
          <p class="mt-2 font-bold text-black"><?php echo format_money($p['price']); ?></p>
        </div>
      </a>
      <form method="post" action="cart.php" class="p-4 pt-0">
        <input type="hidden" name="add" value="<?php echo $p['id']; ?>"/>
        <button class="relative btn-ripple w-full bg-black text-white py-2 rounded-lg hover:shadow-neon transition">Add to Cart 🛒</button>
      </form>
    </div>
  <?php endwhile; ?>
</div>
<?php include 'partials_footer.php'; ?>
