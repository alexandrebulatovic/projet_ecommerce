<?php
	session_start ();

	if (isset ($_SESSION ['LAST_ACTIVITY']) && (time() - $_SESSION ['LAST_ACTIVITY'] > 1800)) {
		// on déconnecte l'utilisateur automatiquement par sécurité au bout de 30 minutes
		header('Location: index.php?controller=utilisateur&action=logout');
	}
	
	$_SESSION ['LAST_ACTIVITY'] = time(); // on met à jour le timestamp de dernière activité de l'utilisateur
	
	// pour la portabilité du site on utilise des chemins de fichiers relatifs
	$ROOT = __DIR__; 
	$DS = DIRECTORY_SEPARATOR;
	
	if (isset ($_GET['controller']))
		$controller = $_GET['controller']; // recupère l'action passée dans l'URL
	else
		$controller = "produit";
	
	switch ($controller){
	case "utilisateur" :
		require "{$ROOT}{$DS}controller{$DS}controllerUtilisateur.php";
		break;
	case "produit" :
		require "{$ROOT}{$DS}controller{$DS}controllerProduit.php";
		break;
	case "panier" :
		require "{$ROOT}{$DS}controller{$DS}controllerPanier.php";
		break;
	case "commande" :
		require "{$ROOT}{$DS}controller{$DS}controllerCommande.php";
		break;
	}
?>