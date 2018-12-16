<?php
	if(empty($tab_p_panier)){
		echo 'Le panier est vide<br>';
	} else {
		foreach($tab_p_panier as $p){
			$nomproduit = $p->getNomProduit();
			$prix = $p->getPrix();
			$numproduit = $p->getNumProduit();
			$quantite = $p->getStock(); // correspond à la quantité de produit voulue par l'acheteur

			echo "$nomproduit au prix de <b>$prix €</b>";
?>
			<form style="display:inline;" method="post" action="index.php?controller=panier&action=majPanier&numproduit=<?php echo $numproduit;?>">
			<input style="width:50px;" type="number" min="1" max="100" name="quantite" value=<?php echo $quantite;?> required />
			<input type="submit" value="Mettre à jour le total" />
			</form>
<?php
			echo "<a href='index.php?controller=panier&action=supprimerProduitPanier&numproduit=$numproduit'>Supprimer du panier</a>";
			echo "<br/><br/>";
		}
		echo "<p><a href='index.php?controller=commande&action=checkout'>Payer la commande</a></p>";
	}
	echo "<p><b>Total : $total_panier €</b></p>";
	echo "<p><a href='index.php'>Retour sur la page d'accueil</a></p>";
?>
