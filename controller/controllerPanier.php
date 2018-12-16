<?php
	require_once "{$ROOT}{$DS}model{$DS}ModelPanier.php"; // chargement du modèle
	require_once "{$ROOT}{$DS}model{$DS}ModelProduit.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelUtilisateur.php";
	require_once "{$ROOT}{$DS}config{$DS}Session.php";
		
	if(!empty($_GET['action']))
		$action = $_GET['action']; // recupère l'action passée dans l'URL
	else
		$action = "consulterPanier"; // par défaut on affiche le panier
	
	switch ($action) {
		case "ajouterPanier" :
			$numproduit = $_GET['numproduit'];
			ModelPanier::ajouterAuPanier($numproduit, 1);
			$pagetitle = "Ajouter au panier";
			$view = "ajouter";
			require ("{$ROOT}{$DS}view{$DS}view.php");
			break;

		case "majPanier" :
			$numproduit = $_GET['numproduit'];
			$quantite = $_POST['quantite'];
			
			//on vérifie que la quantité est suffisante en stock
			$produit = ModelProduit::getProduitByNum($numproduit);
			$qte_max_produit = $produit->getStock();
			
			if ($quantite > $qte_max_produit){
				$pagetitle = "Quantité supérieure au stock";
				$view = "error";
				$errorMessage = "La quantité demandée est supérieure au stock disponible. Nous disposons seulement de $qte_max_produit unités en stock.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} else {
				ModelPanier::ajouterAuPanier($numproduit, $quantite);
				
				// on ré-affiche le panier après la maj
				header('Location: index.php?controller=panier');
			}
			break;

		case "consulterPanier" :
			$panier = array();
			
			if(isset($_COOKIE['panier']))
				$panier = unserialize($_COOKIE['panier']);

			$tab_p_panier = ModelPanier::getProduitsPanier($panier);
			$total_panier = ModelPanier::totalPanier();
			$pagetitle = "Contenu du panier";
			$view = "consulter";
			require ("{$ROOT}{$DS}view{$DS}view.php");
			break;
			
		case "supprimerProduitPanier" :
			$numproduit = $_GET['numproduit'];
			ModelPanier::supprimerDuPanier($numproduit);
			
			// on ré-affiche le panier après la suppression
			header('Location: index.php?controller=panier');
			break;
			
		case "viderPanier" :
			ModelPanier::viderPanier();
			
			$pagetitle = "Vider panier";
			$view = "vider";
			require ("{$ROOT}{$DS}view{$DS}view.php");
			break;
	}
?>