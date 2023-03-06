<?php 
include "connexion.php";
$sql="select * from produits";
$produits=$cnx->query($sql)->fetchAll(PDO::FETCH_ASSOC);


?>