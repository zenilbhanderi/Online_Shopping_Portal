<?php include 'partials_header.php';
if(!is_logged_in()){ header('Location: login.php?next=checkout.php'); exit; }
$placed = false;
if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_SESSION['cart'])){
  $ids = array_keys($_SESSION['cart']);
  if($ids){
    $idlist = implode(',', array_map('intval',$ids));
    $res = $conn->query("SELECT id, price FROM products WHERE id IN ($idlist)");
    $total=0; $prices=[];
    while($r=$res->fetch_assoc()){ $prices[$r['id']]=$r['price']; $total += $_SESSION['cart'][$r['id']]*$r['price']; }
    $uid = (int)user_id();
    $conn->query("INSERT INTO orders (user_id,total) VALUES ($uid,$total)");
    $oid = $conn->insert_id; $_SESSION['last_order_id']=$oid;
    foreach($_SESSION['cart'] as $pid=>$q){
      $pid=(int)$pid; $q=(int)$q;
      $conn->query("INSERT INTO order_items (order_id,product_id,quantity) VALUES ($oid,$pid,$q)");
    }
    $_SESSION['cart']=[]; $placed=true;
  }
}
?>
<h1 class="text-3xl font-bold text-black">Checkout</h1>
<?php if($placed): ?>
  <div class="mt-6 p-6 bg-white border border-amber-200 rounded-xl text-center">
    <h2 class="text-2xl font-bold text-black">✅ Order Confirmed!</h2>
    <p class="text-gray-800 mt-2">Thank you for shopping with us. Your order has been placed.</p>
    <a href="index.php" class="mt-4 inline-block relative btn-ripple bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg shadow-neon transition">Back to Home</a>
  </div>
<?php else: ?>
  <div class="mt-4 p-6 bg-white border border-amber-200 rounded-xl">
    <form method="post" class="grid md:grid-cols-2 gap-4">
      <div><label class="block text-black font-semibold">Full Name</label><input required class="w-full p-2 border rounded" placeholder="Your Name"></div>
      <div><label class="block text-black font-semibold">Address</label><input required class="w-full p-2 border rounded" placeholder="Street, City"></div>
      <div><label class="block text-black font-semibold">Phone</label><input required class="w-full p-2 border rounded" placeholder="+91 ..."></div>
      <div><label class="block text-black font-semibold">Email</label><input required class="w-full p-2 border rounded" value="<?php echo e($_SESSION['user']['email'] ?? ''); ?>"></div>
      <div class="md:col-span-2"><button class="relative btn-ripple bg-black text-white px-6 py-3 rounded-lg hover:shadow-neon transition">Place Order ✅</button></div>
    </form>
  </div>
<?php endif; ?>

<?php
// Inline order summary after successful placement
if(isset($_SESSION['last_order_id'])){
  $oid = (int)$_SESSION['last_order_id'];
  $o = $conn->query("SELECT * FROM orders WHERE id=$oid")->fetch_assoc();
  $its = $conn->query("SELECT oi.*, p.name pname, p.price FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=$oid");
  echo '<div class="mt-6 p-6 bg-white border border-amber-200 rounded-xl">';
  echo '<h2 class="text-xl font-bold text-black">Order Summary</h2>';
  echo '<p class="text-gray-700 mt-1">Order #'.(int)$o['id'].' • '.e($o['created_at'] ?? '').'</p>';
  echo '<table class="w-full mt-3"><tr class="text-left text-black border-b"><th class="p-2">Product</th><th class="p-2">Qty</th><th class="p-2">Price</th><th class="p-2">Line Total</th></tr>';
  $sum=0;
  while($it = $its->fetch_assoc()){
    $lt = $it['quantity']*$it['price']; $sum += $lt;
    echo '<tr class="border-b"><td class="p-2 text-black">'.e($it['pname']).'</td><td class="p-2">'.(int)$it['quantity'].'</td><td class="p-2">'.format_money($it['price']).'</td><td class="p-2">'.format_money($lt).'</td></tr>';
  }
  echo '</table>';
  echo '<div class="text-right text-black font-semibold mt-3">Total: '.format_money($sum).'</div>';
  echo '<a href="my_orders.php" class="mt-4 inline-block relative btn-ripple bg-black text-white px-5 py-3 rounded-lg hover:shadow-neon transition">View My Orders</a>';
  echo '</div>';
  unset($_SESSION['last_order_id']);
}
?>

<?php include 'partials_footer.php'; ?>
