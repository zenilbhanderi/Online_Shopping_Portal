<?php
include 'partials_header.php';
if(!is_logged_in()){ header('Location: login.php?next=my_orders.php'); exit; }
$uid = (int)user_id();

$orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY id DESC");
?>

<style>
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes shimmer {
  0% { background-position: -200px 0; }
  100% { background-position: calc(200px + 100%) 0; }
}

.order-card {
  animation: slideUp 0.6s ease-out;
  animation-fill-mode: both;
  backdrop-filter: blur(10px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.order-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.order-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: hidden;
}

.order-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: left 0.5s;
}

.order-header:hover::before {
  left: 100%;
}

.order-table {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.table-row {
  transition: all 0.2s ease;
  position: relative;
}

.table-row:hover {
  background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 50%, #e2e8f0 100%);
  transform: scale(1.01);
}

.btn-modern {
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.btn-modern::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  transition: left 0.3s ease;
}

.btn-modern:hover::before {
  left: 0;
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.btn-modern span {
  position: relative;
  z-index: 1;
}

.price-badge {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
  animation: fadeIn 0.8s ease-out;
}

.order-id-badge {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: white;
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 0.875rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.no-orders {
  text-align: center;
  padding: 60px 20px;
  color: #64748b;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 16px;
  border: 2px dashed #cbd5e1;
  animation: fadeIn 1s ease-out;
}

.main-container {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
  animation: fadeIn 1s ease-out;
}

.page-title {
  background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  position: relative;
}

.subtotal-highlight {
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  padding: 12px 16px;
  border-radius: 10px;
  border-left: 4px solid #3b82f6;
  margin-top: 16px;
}

/* Staggered animation delays */
.order-card:nth-child(1) { animation-delay: 0.1s; }
.order-card:nth-child(2) { animation-delay: 0.2s; }
.order-card:nth-child(3) { animation-delay: 0.3s; }
.order-card:nth-child(4) { animation-delay: 0.4s; }
.order-card:nth-child(5) { animation-delay: 0.5s; }
</style>

<div class="rounded-2xl p-8 main-container">
  <h1 class="text-3xl font-bold page-title mb-6">My Orders</h1>
  <?php if($orders && $orders->num_rows>0): ?>
    <div class="mt-6 space-y-8">
      <?php while($o = $orders->fetch_assoc()): ?>
        <?php
          $oid = (int)$o['id'];
          $items = $conn->query("SELECT oi.*, p.name pname, p.image, p.price FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=$oid");
          $sum = 0;
        ?>
        <div class="order-card border-0 rounded-2xl overflow-hidden shadow-lg">
          <div class="order-header p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <span class="order-id-badge">Order id #<?php echo $o['id']; ?></span>
                <p class="text-white/80 text-sm mt-2 font-medium"><?php echo e($o['created_at'] ?? ''); ?></p>
              </div>
              <div class="price-badge"><?php echo format_money($o['total']); ?></div>
            </div>
          </div>
          <div class="order-table p-6">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="text-left text-slate-700 border-b-2 border-slate-300">
                    <th class="p-3 font-semibold">Product</th>
                    <th class="p-3 font-semibold">Qty</th>
                    <th class="p-3 font-semibold">Price</th>
                    <th class="p-3 font-semibold">Line Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($it=$items->fetch_assoc()): $lt=$it['quantity']*$it['price']; $sum+=$lt; ?>
                    <tr class="table-row border-b border-slate-200">
                      <td class="p-3 text-slate-800 font-medium"><?php echo e($it['pname']); ?></td>
                      <td class="p-3 text-slate-600"><?php echo (int)$it['quantity']; ?></td>
                      <td class="p-3 text-slate-600"><?php echo format_money($it['price']); ?></td>
                      <td class="p-3 text-slate-800 font-semibold"><?php echo format_money($lt); ?></td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
            <div class="subtotal-highlight text-right">
              <span class="text-slate-700 font-bold text-lg">Subtotal: <?php echo format_money($sum); ?></span>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="no-orders">
      <div class="text-6xl mb-4">📦</div>
      <p class="text-xl font-medium">You have no orders yet.</p>
      <p class="text-sm mt-2">Start shopping to see your orders here!</p>
    </div>
  <?php endif; ?>
  <a href="index.php" class="mt-8 inline-block btn-modern text-white px-8 py-4 rounded-xl font-semibold text-lg">
    <span>← Continue Shopping</span>
  </a>
</div>
<?php include 'partials_footer.php'; ?>