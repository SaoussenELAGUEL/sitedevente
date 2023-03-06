<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de vente </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <style>
    @media print {
      form,#button {
        display:none;
      }
        button, a{
          display:none;
        } 
        .supp{
          display:none;
        }
    }
  </style>
</head>
<body>
<?php
include "security.php";
include "menu.php";
?>
  <div class="container mt-5">

<?php
include "config.php";
?>
<form action="" method="post">
  <div class="row">
<div class="mb-3 col-sm-12 col-md-6 col-lg-5">
<label for="produit" class="form-label">Produit</label>
<select  class="form-control" name="produit" id="produit" >
<option value="">--Choisir un produit--</option>
    <?php
    foreach($produits as $produit){
   echo  "<option value='".$produit['nom_produit']."'>".$produit['nom_produit']."</option>";
}
   ?>
</select>
</div>
<div class="mb-3 col-sm-6 col-lg-2">
<label for="qte" class="form-label">Quantité </label>
<input class="form-control" type="number" name="qte" id="qte" min="1" required>
</div>
<div class="col-sm-6 col-lg-3">
<label for="prix" class="form-label">Prix</label>
<input class="form-control" type="number" name="prix"  id="prix" onkeyup="if(this.value<0){alert('La valeur du prix est négative'); this.value=''}" required >
</div>
<div class="col-sm-12 col-md-6 col-lg-2">
<label for="" class="form-label">&nbsp;</label>
<button name='ajouter' class="btn btn-primary form-control">Ajouter <i class="bi bi-plus-circle-fill"></i></button>
</div>
</div>
</form> 

<?php 



if(!isset($_SESSION['panier']))
$_SESSION['panier']=[];


if(isset($_GET['sauv'])){

    $sqlmax="select max(num_commande) as max from commandes";
    $tab_max=$cnx->query($sqlmax)->fetch(PDO::FETCH_ASSOC);
    if($tab_max['max']>0){
    $num_max=$tab_max['max'];
    $num_commande=$num_max+1;
    }else{
        $num_commande=date("y")."00001";
    }
    $date_commande=date("Y-m-d H:i:s");
    $id_client=1;
    $total=0;
    foreach($_SESSION['panier'] as $indice=>$vente){
      $total+=$vente['qte']*$vente['prix'];
    }
    $sql="insert into commandes(num_commande,date_commande,id_client,totalcmd) values('$num_commande','$date_commande','$id_client','$total')";
    $cnx->exec($sql);
    $id_commande = $cnx->lastInsertId();
   
    foreach($_SESSION['panier'] as $indice=>$vente){
        $id_produit=$vente['id_produit'];
        $qte=$vente['qte'];
        $prix=$vente['prix'];
        $sqlligne="insert into lignecommandes(id_produit,id_commande,qte,prix) values('$id_produit','$id_commande','$qte','$prix')";
        $cnx->exec($sqlligne);
    }
header("location:index.php?vider=1");
}


if(isset($_GET['vider'])){

    unset($_SESSION['panier']);
    header("location:index.php");
}


if(isset($_GET['indice'])){
    unset($_SESSION['vente'][$_GET['indice']]);
    header("location:index.php");
}
if(isset($_POST['supprimer'])){
  $indice=$_POST['indice'];
  unset($_SESSION['panier'][$indice]); 
  }
if(isset($_POST['ajouter']))
{
    foreach($produits as $produit){
        if($produit['nom_produit']==$_POST['produit']){
            $id_produit=$produit['id_produit'];
            break;
        }
    }
    $_SESSION['panier'][]=["id_produit"=>$id_produit,"nom"=>$_POST['produit'],"qte"=>$_POST['qte'],"prix"=>$_POST['prix']];

    header("location:index.php");
}
echo "<h1 class='pt-5 pb-3 '>Tableau des ventes</h1> 
<button id='button' onclick='window.print()' class='btn btn-info '><i class='bi bi-printer-fill text-light fs-2 ps-2 pe-2'></i></button>
<a href='index.php?vider=1' ><button class='btn btn-success'><i class='bi bi-cart-plus-fill fs-2  ps-2 pe-2'></i></button></a>
<br> <br>";
echo "<table class='table table-striped table-bordered' style='text-align:center; border:black 2px solid;'>
<tr><th>Produit</th>
<th>Prix unitaire</th>
<th>Quantité</th>
<th>Prix total</th>
<th class='supp'>Action</th>

</tr>";
$s=0;
foreach($_SESSION['panier'] as $indice=>$vente)
{
echo "<tr>
<td>".$vente['nom']."</td>
<td id='prix$indice'>".$vente['prix']."</td>
<td id='qte$indice'>".$vente['qte']."</td>
<td id='total$indice'>".$vente['prix']*$vente['qte']."</td>
<td class='supp'><form method='post' action=''>
   <button class='btn btn-primary' id='modifier$indice' type='button' onclick='modifier($indice)'>Modifier</button>
   <input type='hidden' name='indice' value='$indice'>
   <button class='btn btn-danger' name='supprimer'>Supprimer</button>
</form></td>";
$s+=$vente['prix']*$vente['qte'];}
echo "<tr>
<th colspan=3>Net à payer</th>
<td id='net'>$s</td>
<td class='supp'></td>
</tr>";
echo "</table>";
echo "<a href='index.php?sauv=1'><button class='btn btn-primary mt-3 pt-1 pb-2' type='button'>Sauvegarder la vente<i class='bi bi-save2-fill fs-2 ps-2 pe-2'></i> </button></a>"
?>
</div>

<script>
    function modifier(ind){
      var prix=parseFloat(document.getElementById('prix'+ind).innerHTML);
      var qte=parseFloat(document.getElementById('qte'+ind).innerHTML);
      document.getElementById('prix'+ind).innerHTML="<input style='width:70px;' type='number' value='"+prix+"' id='valprix"+ind+"' min='1'>" ;
      document.getElementById('qte'+ind).innerHTML="<input style='width:70px;' type='number' value='"+qte+"' id='valqte"+ind+"' min='1'>" ;
      document.getElementById('modifier'+ind).innerHTML="Valider";
      document.getElementById('modifier'+ind).setAttribute("onclick","validermodif("+ind+")");
   }
   function validermodif(ind){ 
    var prix=document.getElementById('valprix'+ind).value;
    var qte=document.getElementById('valqte'+ind).value;

  $.ajax(
    {
      type:"POST",
      url:"modifiervente.php",
      data:{ind:ind,qte:qte,prix:prix},
      beforeSend:function(){
      },
      success:function(data){
        console.log(data);
       obj=JSON.parse(data);
   
       document.getElementById('prix'+ind).innerHTML= obj.prix;
      document.getElementById('qte'+ind).innerHTML= obj.qte;
      var oldtotal=parseFloat(document.getElementById('total'+ind).innerHTML);
      document.getElementById('total'+ind).innerHTML= obj.qte*obj.prix;
       var diff=oldtotal-obj.qte*obj.prix;
       var oldnet=parseFloat(document.getElementById('net').innerHTML);
       document.getElementById('net').innerHTML= oldtotal-diff;


       document.getElementById('modifier'+ind).innerHTML="Modifier";
       document.getElementById('modifier'+ind).setAttribute("onclick","modifier("+ind+")");

    }
    }
  );
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>

