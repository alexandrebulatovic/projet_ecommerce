<?php
	require_once "{$ROOT}{$DS}config{$DS}Session.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelPanier.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelCommande.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelUtilisateur.php"; // chargement du modèle

	if(!empty($_GET['action'])){
		$action = $_GET['action']; // recupère l'action passée dans l'URL
	}
	
	switch ($action) {
		case "checkout" :
			if (Session::isConnected()){
				if(isset($_COOKIE['panier'])){
					$prix = ModelPanier::totalPanier();
					$pagetitle = "Payer le panier";
					$view = "checkout";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
				else{
					$pagetitle = "Erreur de panier";
					$view = "error";
					$errorMessage = "Vous n'avez pas accès à cette page.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			}
			else{
				$pagetitle = "Erreur de paiement";
				$view = "error";
				$errorMessage = "Vous devez être connecté pour régler votre commande. Merci de vous inscrire ou de vous connecter.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			}
			break;
		case "paid" :
			if (Session::isConnected()){
				if (isset($_COOKIE['panier'])){
					
					$panier = unserialize($_COOKIE['panier']);
					$tab_p = ModelPanier::getProduitsPanier($panier);
					$prix = ModelPanier::totalPanier();
					
					ModelCommande::processCart($tab_p, $_SESSION['numUtilisateur'], $prix);
					ModelPanier::viderPanier();
		
					$pagetitle = "Paiement effectué";
					$view = "paid";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				} else{
					$pagetitle = "Erreur de panier";
					$view = "error";
					$errorMessage = "Vous n'avez pas accès à cette page.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			} else{
				$pagetitle = "Erreur de panier";
				$view = "error";
				$errorMessage = "Vous n'avez pas accès à cette page.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			}
			break;			
	}
?>