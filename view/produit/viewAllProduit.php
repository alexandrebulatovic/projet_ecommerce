<?php
	foreach ($tab_p as $p){
		$numproduit = $p->getNumproduit();
		$nomproduit = $p->getNomProduit();
		$prix = $p->getPrix();
		$description = $p->getDescription();
		$stock = $p->getStock();
		
		echo "<p style='overflow:auto;'><img style='float:left; margin-right:15px;' src='img{$DS}{$numproduit}.jpg' alt='image de $nomproduit'>";
		echo "$nomproduit au prix de <b>$prix €</b>" . " ";
		if ($stock > 0)
			echo "<a href='index.php?controller=panier&action=ajouterPanier&numproduit=$numproduit'>Ajouter au panier</a>";
		else
			echo "Ce produit n'est plus en stock.";
		
		if(Session::isAdmin()){
			echo "<br/>";
			echo "<a href='index.php?controller=produit&action=delete&numproduit=$numproduit'>Supprimer le produit</a>" . " " . "<a href='index.php?controller=produit&action=update&numproduit=$numproduit'>Modifier le produit</a>";
			echo "<br/>";
		}
		else
			echo "<br/><br/>";
		
		echo "$description</p>";
	}
?>