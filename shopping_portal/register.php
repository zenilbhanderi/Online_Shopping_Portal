<?php include 'partials_header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=$_POST['name']??''; $email=$_POST['email']??''; $pass=$_POST['password']??'';
  if($name && $email && $pass){
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $stmt=$conn->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
    $stmt->bind_param('sss',$name,$email,$hash);
    if($stmt->execute()){
      $_SESSION['user']=['id'=>$stmt->insert_id,'name'=>$name,'email'=>$email,'role'=>'user'];
      header('Location: index.php'); exit;
    } else { $err='Email already exists'; }
  } else { $err='All fields required'; }
}
?>
<div class="max-w-md mx-auto mt-8 bg-white border border-amber-200 rounded-xl p-6 shadow">
  <h1 class="text-2xl font-bold text-black">Register</h1>
  <?php if(isset($err)) echo "<p class='text-red-600 mt-2'>$err</p>"; ?>
  <form method="post" class="mt-4 space-y-4">
    <input name="name" required placeholder="Name" class="w-full p-2 border rounded">
    <input name="email" required placeholder="Email" class="w-full p-2 border rounded">
    <input type="password" name="password" required placeholder="Password" class="w-full p-2 border rounded">
    <button class="relative btn-ripple w-full bg-black text-white py-2 rounded-lg hover:shadow-neon transition">Create Account</button>
  </form>
</div>
<?php include 'partials_footer.php'; ?>
