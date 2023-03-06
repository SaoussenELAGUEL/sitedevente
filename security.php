<?php session_start();
if(!isset($_SESSION['login']) || !isset($_SESSION['password'])){
header("location:index-new.php");
exit;
}
?>