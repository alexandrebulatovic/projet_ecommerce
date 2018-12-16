<?php
	require_once "{$ROOT}{$DS}model{$DS}ModelProduit.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelUtilisateur.php";
	require_once "{$ROOT}{$DS}config{$DS}Session.php";
	 
	if(!empty($_GET['action']))
		$action = $_GET['action']; // recupère l'action passée dans l'URL
	else
		$action = "readAll"; // par défaut on affiche tous les produits
	
	switch ($action) {
		case "readAll" :
			$tab_p = ModelProduit::getAllProduits();
			$view = "all";
			$pagetitle = "Liste des produits";
			
			if (Session::isConnected())
				$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']);
			   
			require("{$ROOT}{$DS}view{$DS}view.php"); 	
			break;
					
		case "create":
			if(Session::isAdmin()){
				$pagetitle = "Création d'un nouveau produit";
				$view = "create";
				require("{$ROOT}{$DS}view{$DS}view.php"); 	
			} else {
				$pagetitle = "Erreur de création";
				$view = "error";
				$errorMessage = "Vous n'avez pas les droits de création de nouveaux produits.";
				require("{$ROOT}{$DS}view{$DS}view.php"); 
			}
			break;
					
		case "created":
			if(Session::isAdmin()){
				$produit = new ModelProduit(NULL, $_POST['nomP'], $_POST['prix'], $_POST['description'], $_POST['stock']);
				$produit->save();
				$tab_p = ModelProduit::getAllProduits();
				$pagetitle = "Produit créé";
				$view = "created";
				require("{$ROOT}{$DS}view{$DS}view.php");
			} else {
				$pagetitle = "Erreur de création";
				$view = "error";
				$errorMessage = "Vous n'avez pas les droits de création de nouveaux produits.";
				require("{$ROOT}{$DS}view{$DS}view.php"); 
			}
			break;
			
			
		case "delete":
			if(Session::isAdmin()){
				$numproduit = $_GET['numproduit'];
				$p = ModelProduit::getProduitByNum($numproduit); // on vérifie en même temps que le produit existe

				if (empty($p)){
					$pagetitle = "Erreur de produit";
					$view = "error";
					$errorMessage = "Le produit spécifié n'existe pas.";
					require("{$ROOT}{$DS}view{$DS}view.php");  		
				} else {
					$p->delete();
					$pagetitle = "Produit supprimé";
					$view = "deleted";
					require("{$ROOT}{$DS}view{$DS}view.php"); 			
					}		
			} else {
				$pagetitle = "Erreur de suppression";
				$view = "error";
				$errorMessage = "Vous n'avez pas les droits de suppression de produits.";
				require("{$ROOT}{$DS}view{$DS}view.php"); 
			}
			break;
			
		case "update":
			if(Session::isAdmin()){
				$numproduit = $_GET['numproduit'];
				$p = ModelProduit::getProduitByNum($numproduit);
				
				if(empty($p)){ 
					$pagetitle = "Erreur dans la table produit";
					$view = "Error";
					$errorMessage = "Le produit spécifié n'existe pas";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				} else {
					$nomproduit = $p->getNomProduit();
					$prix = $p->getprix();
					$description = $p->getDescription();
					$stock = $p->getStock();
					$pagetitle = "MAJ du produit";
					$view = "update";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			} else{
				$pagetitle = "Erreur de mise à jour";
				$view = "error";
				$errorMessage = "Vous n'avez pas les droits de mise à jour de produits.";
				require("{$ROOT}{$DS}view{$DS}view.php"); 
			}
			break;
			
		case "updated": 
			if(Session::isAdmin()){
				$data = array(
					"numP" => $_GET['numproduit'],
					"nomP" => $_POST['nomP'],
					"prix" => $_POST['prix'],
					"description" => $_POST['description'],
					"stock" => $_POST['stock']
				);
				ModelProduit::update($data);
				$pagetitle = "MAJ du produit";
				$view = "updated";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} else{
				$pagetitle = "Erreur de mise à jour";
				$view = "error";
				$errorMessage = "Vous n'avez pas les droits de mise à jour de produits.";
				require ("{$ROOT}{$DS}view{$DS}view.php"); 
			}
			break;
		}
?>