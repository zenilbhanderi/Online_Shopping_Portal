<?php include 'guard.php';
if(isset($_POST['add'])){ $name=$_POST['name']; $desc=$_POST['description'];
  $stmt=$conn->prepare('INSERT INTO categories (name,description) VALUES (?,?)');
  $stmt->bind_param('ss',$name,$desc); $stmt->execute();
}
if(isset($_POST['update'])){ $id=(int)$_POST['id']; $name=$_POST['name']; $desc=$_POST['description'];
  $stmt=$conn->prepare('UPDATE categories SET name=?, description=? WHERE id=?');
  $stmt->bind_param('ssi',$name,$desc,$id); $stmt->execute();
}
if(isset($_GET['delete'])){ $id=(int)$_GET['delete']; $conn->query("DELETE FROM categories WHERE id=$id"); }
$cats=$conn->query('SELECT * FROM categories ORDER BY id');
?><!doctype html><html><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<script src="https://cdn.tailwindcss.com"></script><title>Categories</title></head>
<body class="bg-[linear-gradient(135deg,#ffffff,#f3e8e1)] text-gray-900">
  <div class="bg-black text-white p-4"><a href="dashboard.php" class="hover:text-amber-300">← Back</a></div>
  <div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold text-black">Categories</h1>
    <form method="post" class="mt-4 flex gap-2">
      <input name="name" required class="p-2 border rounded w-48" placeholder="Name">
      <input name="description" class="p-2 border rounded flex-1" placeholder="Description">
      <button name="add" class="relative btn-ripple bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg">Add</button>
    </form>
    <table class="w-full mt-6 border">
      <tr class="bg-amber-50 text-black"><th class="p-2 text-left">ID</th><th class="p-2 text-left">Name</th><th class="p-2 text-left">Description</th><th class="p-2">Actions</th></tr>
      <?php while($c=$cats->fetch_assoc()): ?>
      <tr class="border-t tr-hover">
        <td class="p-2"><?php echo $c['id']; ?></td>
        <td class="p-2" colspan="3">
          <form method="post" class="grid md:grid-cols-4 gap-2 items-center">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>"/>
            <input name="name" value="<?php echo e($c['name']); ?>" class="p-1 border rounded w-full">
            <input name="description" value="<?php echo e($c['description']); ?>" class="p-1 border rounded w-full md:col-span-2">
            <div class="flex gap-3">
              <button name="update" class="btn-ripple px-3 py-1 bg-black text-white rounded">Save</button>
              <a class="text-red-600 hover:underline" href="?delete=<?php echo $c['id']; ?>">Delete</a>
            </div>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body></html>
