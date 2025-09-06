<?php include 'partials_header.php';
$next = e($_GET['next'] ?? 'index.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=$_POST['email']??''; $pass=$_POST['password']??'';
  $stmt=$conn->prepare('SELECT * FROM users WHERE email=?');
  $stmt->bind_param('s',$email); $stmt->execute(); $res=$stmt->get_result();
  if($u=$res->fetch_assoc()){
    if(password_verify($pass,$u['password']) || md5($pass)===$u['password']){
      $_SESSION['user']=$u;
      if($u['role']==='admin'){ header('Location: admin/dashboard.php'); exit; }
      header("Location: $next"); exit;
    }
  }
  $err='Invalid credentials';
}
?>
<div class="max-w-md mx-auto mt-8 bg-white border border-amber-200 rounded-xl p-6 shadow">
  <h1 class="text-2xl font-bold text-black">Login</h1>
  <?php if(isset($err)) echo "<p class='text-red-600 mt-2'>$err</p>"; ?>
  <form method="post" class="mt-4 space-y-4">
    <input name="email" required placeholder="Email" class="w-full p-2 border rounded">
    <input type="password" name="password" required placeholder="Password" class="w-full p-2 border rounded">
    <button class="relative btn-ripple w-full bg-amber-600 hover:bg-amber-500 text-white py-2 rounded-lg shadow-neon transition">Login</button>
  </form>
  <p class="mt-3 text-gray-800">No account? <a class="text-black underline hover:text-amber-700" href="register.php">Register</a></p>
</div>
<?php include 'partials_footer.php'; ?>
