<?php session_start();
if (!empty($_POST)){
$ind=$_POST['ind'];
$prix=$_POST['prix'];
$qte=$_POST['qte'];
$_SESSION['panier'][$ind]['qte']=$qte;
$_SESSION['panier'][$ind]['prix']=$prix;
$tab=['qte'=>$qte,'prix'=>$prix];
echo json_encode($tab);
}
else 
exit;

?>