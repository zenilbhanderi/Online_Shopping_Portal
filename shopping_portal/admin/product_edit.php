<?php include 'guard.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cats=$conn->query('SELECT * FROM categories ORDER BY id');
$prod = null;
if($id){ $prod = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc(); }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $cid=(int)$_POST['category_id']; $name=$_POST['name']; $price=(float)$_POST['price']; $desc=$_POST['description'];
  $imgname = $_POST['image_text'] ?? ($prod['image'] ?? '');
  if(!empty($_FILES['image']['name'])){
    $up = handle_upload($_FILES['image'], __DIR__.'/../assets/images');
    if($up){ $imgname=$up; }
  }
  if($id){
    $stmt=$conn->prepare('UPDATE products SET category_id=?, name=?, price=?, image=?, description=? WHERE id=?');
    $stmt->bind_param('isdssi',$cid,$name,$price,$imgname,$desc,$id); $stmt->execute();
  } else {
    $stmt=$conn->prepare('INSERT INTO products (category_id,name,price,image,description) VALUES (?,?,?,?,?)');
    $stmt->bind_param('isdss',$cid,$name,$price,$imgname,$desc); $stmt->execute();
    $id = $conn->insert_id;
  }
  header('Location: products.php'); exit;
}
?><!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<script src="https://cdn.tailwindcss.com"></script><title><?php echo $id?'Edit':'Create'; ?> Product</title></head>
<body class="bg-[linear-gradient(135deg,#ffffff,#f3e8e1)] text-gray-900">
  <div class="bg-black text-white p-4"><a href="products.php" class="hover:text-amber-300">← Back</a></div>
  <div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-black"><?php echo $id?'Edit':'Create'; ?> Product</h1>
    <form method="post" enctype="multipart/form-data" class="mt-4 grid gap-3 bg-white border border-amber-200 rounded-xl p-4">
      <label class="text-black">Category</label>
      <select name="category_id" class="p-2 border rounded">
        <?php mysqli_data_seek($cats,0); while($c=$cats->fetch_assoc()): ?>
          <option value="<?php echo $c['id']; ?>" <?php echo ($prod && $prod['category_id']==$c['id'])?'selected':''; ?>><?php echo e($c['name']); ?></option>
        <?php endwhile; ?>
      </select>
      <label class="text-black">Name</label>
      <input name="name" value="<?php echo e($prod['name'] ?? ''); ?>" required class="p-2 border rounded">
      <label class="text-black">Price</label>
      <input name="price" type="number" step="0.01" value="<?php echo e($prod['price'] ?? ''); ?>" required class="p-2 border rounded">
      <label class="text-black">Description</label>
      <textarea name="description" class="p-2 border rounded" rows="3"><?php echo e($prod['description'] ?? ''); ?></textarea>
      <div class="grid md:grid-cols-2 gap-3">
        <div>
          <label class="text-black">Upload New Image</label>
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp" class="p-2 border rounded w-full">
        </div>
        <div>
          <label class="text-black">Or use existing filename</label>
          <input name="image_text" value="<?php echo e($prod['image'] ?? ''); ?>" class="p-2 border rounded w-full" placeholder="placeholder.png">
        </div>
      </div>
      <?php if($prod): ?>
        <div>
          <p class="text-black font-semibold mb-2">Current Image:</p>
          <img src="../assets/images/<?php echo e($prod['image']); ?>" class="w-56 h-36 object-cover rounded border bg-amber-50">
        </div>
      <?php endif; ?>
      <button class="relative btn-ripple bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg transition"><?php echo $id?'Save Changes':'Create Product'; ?></button>
    </form>
  </div>
</body></html>
