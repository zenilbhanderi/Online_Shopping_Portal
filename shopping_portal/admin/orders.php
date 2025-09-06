<?php include 'guard.php';
$orders=$conn->query('SELECT o.*, u.name uname FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC');
$itemsStmt=$conn->prepare('SELECT oi.*, p.name pname FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?');
?><!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<script src="https://cdn.tailwindcss.com"></script><title>Orders</title></head>
<body class="bg-[linear-gradient(135deg,#ffffff,#f3e8e1)] text-gray-900">
  <div class="bg-black text-white p-4"><a href="dashboard.php" class="hover:text-amber-300">← Back</a></div>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-black">Orders</h1>
    <table class="w-full mt-4 border">
      <tr class="bg-amber-50 text-black">
        <th class="p-2 text-left">Order #</th><th class="p-2 text-left">Customer</th><th class="p-2 text-left">Total</th><th class="p-2 text-left">Date</th><th class="p-2">Items</th>
      </tr>
      <?php while($o=$orders->fetch_assoc()): ?>
      <tr class="border-t tr-hover">
        <td class="p-2"><?php echo $o['id']; ?></td>
        <td class="p-2"><?php echo e($o['uname']); ?></td>
        <td class="p-2 text-black"><?php echo format_money($o['total']); ?></td>
        <td class="p-2"><?php echo e($o['created_at']); ?></td>
        <td class="p-2">
          <?php
            $itemsStmt->bind_param('i',$o['id']); $itemsStmt->execute(); $itres=$itemsStmt->get_result();
            while($it=$itres->fetch_assoc()){ echo '<div class="text-sm">×'.(int)$it['quantity'].' — '.e($it['pname']).'</div>'; }
          ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body></html>
