<?php include 'partials_header.php';
if(!isset($_SESSION['cart'])) $_SESSION['cart']=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['add'])){
    $pid=(int)$_POST['add'];
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    echo "<script>cartBounce()</script>";
  }
  if(isset($_POST['inc'])){
    $pid=(int)$_POST['inc']; $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
  }
  if(isset($_POST['dec'])){
    $pid=(int)$_POST['dec']; $q=($_SESSION['cart'][$pid] ?? 0)-1; if($q<=0){ unset($_SESSION['cart'][$pid]); } else { $_SESSION['cart'][$pid]=$q; }
  }
  if(isset($_POST['remove'])){
    $pid=(int)$_POST['remove']; unset($_SESSION['cart'][$pid]);
  }
}
$ids = array_keys($_SESSION['cart']);
$items = []; $total = 0;
if($ids){
  $idlist = implode(',', array_map('intval',$ids));
  $res = $conn->query("SELECT * FROM products WHERE id IN ($idlist)");
  while($r=$res->fetch_assoc()){
    $q = $_SESSION['cart'][$r['id']];
    $r['qty']=$q; $r['line']=$q*$r['price']; $total += $r['line']; $items[]=$r;
  }
}
?>
<h1 class="text-3xl font-bold text-black">Your Cart 🛍️</h1>
<?php if(!$items): ?>
  <p class="mt-4 text-gray-800">Your cart is empty. Add something nice ✨</p>
<?php else: ?>
  <div class="mt-6 bg-white border border-amber-200 rounded-xl overflow-hidden">
    <table class="w-full text-left">
      <thead class="bg-amber-50 text-black"><tr><th class="p-3">Product</th><th class="p-3">Price</th><th class="p-3">Qty</th><th class="p-3">Line</th><th class="p-3">Action</th></tr></thead>
      <tbody>
      <?php foreach($items as $it): ?>
        <tr class="border-t tr-hover">
          <td class="p-3 flex items-center gap-3">
            <img src="assets/images/<?php echo e($it['image']); ?>" class="w-16 h-12 object-cover rounded border"/>
            <span class="text-black font-medium"><?php echo e($it['name']); ?></span>
          </td>
          <td class="p-3 text-black"><?php echo format_money($it['price']); ?></td>
          <td class="p-3">
            <form method="post" class="inline">
              <button name="dec" value="<?php echo $it['id']; ?>" class="btn-ripple px-3 py-1 rounded bg-amber-100 hover:bg-amber-200">−</button>
            </form>
            <span class="px-3 font-semibold text-black"><?php echo (int)$it['qty']; ?></span>
            <form method="post" class="inline">
              <button name="inc" value="<?php echo $it['id']; ?>" class="btn-ripple px-3 py-1 rounded bg-amber-100 hover:bg-amber-200">+</button>
            </form>
          </td>
          <td class="p-3 text-black"><?php echo format_money($it['line']); ?></td>
          <td class="p-3">
            <form method="post">
              <button name="remove" value="<?php echo $it['id']; ?>" class="text-red-600 hover:underline">❌ Remove</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="mt-4 flex items-center justify-between">
    <p class="text-xl font-bold text-black">Total: <span class="text-amber-700"><?php echo format_money($total); ?> 💵</span></p>
    <a href="checkout.php" class="relative btn-ripple bg-black text-white px-6 py-3 rounded-lg hover:shadow-neon transition">Proceed to Checkout ✅</a>
  </div>
<?php endif; ?>

<?php include 'partials_footer.php'; ?>
