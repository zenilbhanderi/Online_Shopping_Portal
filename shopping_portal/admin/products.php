<?php include 'guard.php';
$cats=$conn->query('SELECT * FROM categories ORDER BY id');
if(isset($_POST['add'])){
  $cid=(int)$_POST['category_id']; $name=$_POST['name']; $price=(float)$_POST['price']; $desc=$_POST['description'];
  $imgname = $_POST['image_text'] ?? '';
  if(!empty($_FILES['image']['name'])){
    $up = handle_upload($_FILES['image'], __DIR__.'/../assets/images');
    if($up){ $imgname=$up; }
  }
  $stmt=$conn->prepare('INSERT INTO products (category_id,name,price,image,description) VALUES (?,?,?,?,?)');
  $stmt->bind_param('isdss',$cid,$name,$price,$imgname,$desc); $stmt->execute();
}
if(isset($_GET['delete'])){ $id=(int)$_GET['delete']; $conn->query("DELETE FROM products WHERE id=$id"); }
$prods=$conn->query('SELECT p.*, c.name cat FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC');
?><!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<script src="https://cdn.tailwindcss.com"></script><title>Products</title></head>
<body class="bg-[linear-gradient(135deg,#ffffff,#f3e8e1)] text-gray-900">
  <div class="bg-black text-white p-4 flex justify-between">
    <a href="dashboard.php" class="hover:text-amber-300">← Back</a>
    <a href="product_edit.php" class="hover:text-amber-300 underline">+ Create New</a>
  </div>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-black">Products</h1>
    <form method="post" enctype="multipart/form-data" class="mt-4 grid md:grid-cols-6 gap-2 p-4 bg-white border border-amber-200 rounded-xl">
      <select name="category_id" class="p-2 border rounded">
        <?php mysqli_data_seek($cats,0); while($c=$cats->fetch_assoc()): ?>
          <option value="<?php echo $c['id']; ?>"><?php echo e($c['name']); ?></option>
        <?php endwhile; ?>
      </select>
      <input name="name" required class="p-2 border rounded" placeholder="Name">
      <input name="price" required class="p-2 border rounded" placeholder="Price" type="number" step="0.01">
      <input name="image" type="file" class="p-2 border rounded" accept=".jpg,.jpeg,.png,.gif,.webp">
      <input name="image_text" class="p-2 border rounded" placeholder="or filename (assets/images/...)">
      <input name="description" class="p-2 border rounded md:col-span-2" placeholder="Description">
      <button name="add" class="relative btn-ripple bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg md:col-span-4">Add Product</button>
    </form>
    <div class="grid md:grid-cols-3 gap-4 mt-6">
      <?php mysqli_data_seek($prods,0); while($p=$prods->fetch_assoc()): ?>
        <div class="border border-amber-200 rounded-xl p-4 bg-white card-tilt">
          <img src="../assets/images/<?php echo e($p['image']); ?>" class="w-full h-32 object-cover rounded bg-amber-50"/>
          <h3 class="mt-2 font-semibold text-black"><?php echo e($p['name']); ?> <span class="text-gray-600 text-sm">(<?php echo e($p['cat']); ?>)</span></h3>
          <p class="text-black"><?php echo format_money($p['price']); ?></p>
          <div class="flex justify-between mt-2">
            <a class="text-black underline hover:text-amber-700" href="product_edit.php?id=<?php echo $p['id']; ?>">Edit</a>
            <a class="text-red-600 hover:underline" href="?delete=<?php echo $p['id']; ?>">Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body></html>
