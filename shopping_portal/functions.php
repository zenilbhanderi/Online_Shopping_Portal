<?php
function is_logged_in(){ return isset($_SESSION['user']); }
function is_admin(){ return isset($_SESSION['user']) && $_SESSION['user']['role']==='admin'; }
function user_id(){ return $_SESSION['user']['id'] ?? null; }
function format_money($n){ return '₹ '.number_format((float)$n,2); }
function cart_count(){ $c=0; if(!empty($_SESSION['cart'])){ foreach($_SESSION['cart'] as $q){ $c+=$q; } } return $c; }
function ensure_upload_dir($path){ if(!is_dir($path)){ @mkdir($path,0777,true); } }
function handle_upload($file,$upload_dir){
  ensure_upload_dir($upload_dir);
  if(!isset($file) || $file['error']!==UPLOAD_ERR_OK) return null;
  $name = preg_replace('/[^A-Za-z0-9._-]/','_', $file['name']);
  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  $allowed = ['jpg','jpeg','png','gif','webp'];
  if(!in_array($ext,$allowed)) return null;
  $new = uniqid('img_', true).'.'.$ext;
  $dest = rtrim($upload_dir,'/').'/'.$new;
  if(move_uploaded_file($file['tmp_name'],$dest)){ return $new; }
  return null;
}
?>
