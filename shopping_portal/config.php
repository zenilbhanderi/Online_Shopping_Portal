<?php
session_start();
$host='sql102.infinityfree.com'; $user='if0_39805195'; $pass='Zenil2005'; $db='if0_39805195_shopping_portal';
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error){ die('DB connection failed: '.$conn->connect_error); }
$conn->set_charset('utf8mb4');
function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
?>
