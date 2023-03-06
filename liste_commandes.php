<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des commandes </title>
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
?>
<?php
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

echo "
<button id='button' onclick='window.print()' class='btn btn-info '><i class='bi bi-printer-fill text-light fs-2 ps-2 pe-2'></i></button>
<br> <br>";
echo "<table class='table table-striped table-bordered' style='text-align:center; border:black 2px solid;'>
<tr><th>Num commande</th>
<th>Date</th>
<th>Client </th>
<th>Total</th>
<th class='supp'>Action</th>
</tr>";
$sql="select * from commandes cmd,clients c where cmd.id_client=c.id_client";
$commandes=$cnx->query($sql)->fetchAll(PDO::FETCH_OBJ);
$s=0;
foreach($commandes as $commande)
{
echo "<tr>
<td>".$commande->num_commande."</td>
<td >".$commande->date_commande."</td>
<td >".$commande->nom_client."-".$commande->tel."</td>
<td >".$commande->totalcmd."</td>
<td class='supp'>
   <button class='btn btn-danger' name='supprimer'>Supprimer</button>
</td>";
$s+=$commande->totalcmd;
}
echo "<tr>
<th colspan=3>Chiffre d'affaire</th>
<td id='net'>$s</td>
<td class='supp'></td>
</tr>";
echo "</table>";
echo "<a href='index.php?sauv=1'><button class='btn btn-primary mt-3 pt-1 pb-2' type='button'>Sauvegarder la vente<i class='bi bi-save2-fill fs-2 ps-2 pe-2'></i> </button></a>"
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>
</html>

