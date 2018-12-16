<?php
	require_once "{$ROOT}{$DS}model{$DS}ModelUtilisateur.php"; // chargement du modèle
	require_once "{$ROOT}{$DS}config{$DS}Security.php";
	require_once "{$ROOT}{$DS}config{$DS}Session.php";
	
	if (!empty($_GET['action']))
		$action = $_GET['action']; // recupère l'action passée dans l'URL
	else
		$action = 'login';

	switch ($action){
		case "create" :
			$pagetitle = "Création de compte";
			$view = "create";
			require("{$ROOT}{$DS}view{$DS}view.php");
			break;
			
		case "created" :
			if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['mdp']) || empty($_POST['mdpC']) ||  empty($_POST['email'])) {
				$pagetitle = "Erreur d'inscription";
				$view = "error";
				$errorMessage = "Les informations sont incomplètes.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} elseif (ModelUtilisateur::userExist($_POST['email'])) {
				$pagetitle = "Erreur d'inscription";
				$view = "error";
				$errorMessage = "Vous êtes déjà inscrit sur notre site.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} elseif ($_POST['mdp']=== $_POST['mdpC'] && !(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false)) {
				$nonce = Security::generateRandomHex();
				$utilisateur = new ModelUtilisateur($_POST['nom'], $_POST ['prenom'], Security::chiffrer($_POST['mdp']), 0, $_POST['email'], $nonce);
				
				if (!$utilisateur->sendValidationMail()) { // à des fins de test on peut mettre le retour de la fonction sendValidationMail() à vrai dans tous les cas
					$pagetitle = "Erreur d'inscription";
					$view = "error";
					$errorMessage = "Il y a un problème pour envoyer l'e-mail de validation de compte.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				} else {
					$utilisateur->save();
					$email = $utilisateur->getEmail();
					$pagetitle = "Utilisateur créé";
					$view = "created";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			} elseif ($_POST ['mdp'] != $_POST ['mdpC']) {
				$pagetitle = "Erreur d'inscription";
				$view = "error";
				$errorMessage = "Le mots de passe de confirmation ne correspond pas au mot de passe renseigné.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
				$pagetitle = "Erreur d'inscription";
				$view = "error";
				$errorMessage = "Veuillez entrer une adresse e-mail valide.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			}
			break;
			
		case "login" :
			$pagetitle = "Connexion";
			$view = "login";
			require ("{$ROOT}{$DS}view{$DS}view.php");
			break;
			
		case "loggedin" :
			if (empty($_POST['email']) || empty($_POST['mdp'])) {
				$pagetitle = "Erreur de connexion";
				$view = "error";
				$errorMessage = "Les identifiants sont incomplets.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} else {
				$mdp = Security::chiffrer($_POST['mdp']);
				$email = $_POST['email'];
				// si l'e-mail est validé et que les identifiants sont bons, on connecte la personne à son compte
				$essai_connexion = ModelUtilisateur::checkPassword($email, $mdp); // la fonction renvoie faux en cas d'échec de connexion, sinon elle renvoie le "numUtilisateur"
				
				if (ModelUtilisateur::checkNonceEqualsNull($email) && !($essai_connexion === false)){ // on vérifie si le compte a été validé
					$utilisateur = ModelUtilisateur::getUtilisateurByNum($essai_connexion);
					$_SESSION['numUtilisateur'] = $essai_connexion;
					$_SESSION ['admin'] = $utilisateur->getAdmin();
					
					header('Location: index.php');
				} else {
					$pagetitle = "Erreur de connexion";
					$view = "error";
					$errorMessage = "Ce compte n'existe pas ou n'a pas encore été validé.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			}
		break;
		
		case "logout" :
			if (Session::isConnected()) {
				session_unset();
				session_destroy();	
			}
			header('Location: index.php');
			break;
			
		case "validate" :
			if (empty($_GET['email']) || empty($_GET['nonce'])) {
				$pagetitle = "Erreur de validation";
				$view = "error";
				$errorMessage = "Il y a une erreur dans le lien de validation.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			} else {
				$email = $_GET['email'];
				$nonce = $_GET['nonce'];
				
				if (ModelUtilisateur::checkNonceEqualsNull($email)) { // on vérifie si le compte a déjà été validé par e-mail
					$pagetitle = "Erreur de validation";
					$view = "error";
					$errorMessage = "Ce compte a déjà été validé.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				} elseif (!ModelUtilisateur::checkNonceEquals($email, $nonce)) {
					$pagetitle = "Erreur de validation";
					$view = "error";
					$errorMessage = "Il y a une erreur dans le lien de validation.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				} elseif (ModelUtilisateur::checkNonceEquals($email, $nonce)){
					ModelUtilisateur::nonceToNull($email);
					$pagetitle = "Validation";
					$view = "validate";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			}
			break;
			
		case "update" :
			if (Session::isConnected()){
				$num_utilisateur = $_SESSION['numUtilisateur'];
				$utilisateur = ModelUtilisateur::getUtilisateurByNum($num_utilisateur);
				$email = $utilisateur->getEmail();
				$nom = $utilisateur->getNom();
				$prenom = $utilisateur->getPrenom();
				$admin = $utilisateur->getAdmin();

				$pagetitle = "MAJ de l'utilisateur";
				$view = "update";
				require("{$ROOT}{$DS}view{$DS}view.php");
			} else {
				$pagetitle = "Erreur de modification";
				$view = "error";
				$errorMessage = "Vous n'avez pas accès à cette page.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			}
			break;
			
		case "updated" :
			if (Session::isConnected()){
				$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']); // on récupère les valeurs du compte actuelles
				// cette grosse condition détecte s'il y a une modification du compte via le formulaire, si c'est le cas on va passer en revue chaque possibilité dans les "if" consécutifs
				if (!(!empty($_POST['email']) || (!empty($_POST['mdp']) && !empty($_POST['mdpC'])) || (!empty($_POST['nom']) && ($_POST['nom'] !== $utilisateur->getNom())) || (!empty($_POST['prenom']) && ($_POST['prenom'] !== $utilisateur->getPrenom())) || (empty($_POST['admin']) && Session::isAdmin()))) {
					$pagetitle = "Erreur de modification";
					$view = "error";
					$errorMessage = "Vous n'avez effectué aucune modification.";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
				else { // à chaque fois qu'une valeur a été renseignée dans le formulaire, on va envoyer une requête UPDATE indépendante
				
					$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']); // on récupère les valeurs du compte actuelles
					if (!empty($_POST['email'])){
							if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){ // si l'e-mail n'est pas valide (via bypass du formulaire html)
								$pagetitle = "Erreur de modification";
								$view = "error";
								$errorMessage = "Veuillez entrer une adresse e-mail valide.";
								require ("{$ROOT}{$DS}view{$DS}view.php");
								break; // on arrête l'exécution du code qui suit pour ne pas afficher plusieurs "view"
							} else {
								if (ModelUtilisateur::userExist($_POST['email'])){ // si le nouvel e-mail existe déjà
									$pagetitle = "Erreur de modification";
									$view = "error";
									$errorMessage = "Cet e-mail est déjà enregistré.";
									require ("{$ROOT}{$DS}view{$DS}view.php");
									break;
								} else {
									$email = $_POST['email'];
									$data = array(
										"num" => $_SESSION['numUtilisateur'],
										"nom" => $utilisateur->getNom(),
										"prenom" => $utilisateur->getPrenom(),
										"mdp" => $utilisateur->getMdp(),
										"admin" => $utilisateur->getAdmin(),
										"email" => $email // on met à jour seulement l'email et on ne change pas le reste dans ce "if"
										);
									ModelUtilisateur::update($data);
								}
							}
					}
					
					$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']); // on récupère les valeurs du compte actuelles
					if (!empty($_POST['mdp']) && !empty($_POST['mdpC'])){
						$mdp = $_POST['mdp'];
						$mdpC = $_POST['mdpC'];
						
						if ($mdp !== $mdpC){
							$pagetitle = "Erreur de modification";
							$view = "error";
							$errorMessage = "Les mots de passe renseignés sont différents.";
							require ("{$ROOT}{$DS}view{$DS}view.php");
							break;
						} else {
							$data = array(
							"num" => $_SESSION['numUtilisateur'],
							"nom" => $utilisateur->getNom(),
							"prenom" => $utilisateur->getPrenom(),
							"mdp" => Security::chiffrer($mdp), // on met à jour seulement le mot de passe et on ne change pas le reste dans ce "if"
							"admin" => $utilisateur->getAdmin(),
							"email" => $utilisateur->getEmail()
							);
						ModelUtilisateur::update($data);
						}
					}
					
					$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']); // on récupère les valeurs du compte actuelles
					if (!empty($_POST['nom']) && ($_POST['nom'] !== $utilisateur->getNom())){
						$nom = $_POST['nom'];
						$data = array(
							"num" => $_SESSION['numUtilisateur'],
							"nom" => $nom, // on met à jour seulement le nom et on ne change pas le reste dans ce "if"
							"prenom" => $utilisateur->getPrenom(),
							"mdp" => $utilisateur->getMdp(),
							"admin" => $utilisateur->getAdmin(),
							"email" => $utilisateur->getEmail()
							);
					ModelUtilisateur::update($data);
					}
					
					$utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']); // on récupère les valeurs du compte actuelles
					if (!empty($_POST['prenom']) && ($_POST['prenom'] !== $utilisateur->getPrenom())){
						$prenom = $_POST['prenom'];
						$data = array(
							"num" => $_SESSION['numUtilisateur'],
							"nom" => $utilisateur->getNom(), 
							"prenom" => $prenom, // on met à jour seulement le prénom et on ne change pas le reste dans ce "if"
							"mdp" => $utilisateur->getMdp(),
							"admin" => $utilisateur->getAdmin(),
							"email" => $utilisateur->getEmail()
							);
					ModelUtilisateur::update($data);
					}	

					if (Session::isAdmin()){ // un admin peut s'enlever les droits d'admin s'il le souhaite, en décochant la case "administrateur"
						if (empty($_POST['admin'])){ // ce qui revient à ne donner aucune valeur à la variable "admin"
							$admin = 0; // on évite "false" car la méthode "Pdo->Prepare()" rajoute des guillemets à notre valeur booléenne et MySQL lève une erreur ensuite
							$data = array(
								"num" => $_SESSION['numUtilisateur'],
								"nom" => $utilisateur->getNom(), 
								"prenom" => $utilisateur->getPrenom(), 
								"mdp" => $utilisateur->getMdp(),
								"admin" => $admin, // on met à jour seulement le booléen admin et on ne change pas le reste dans ce "if"
								"email" => $utilisateur->getEmail()
								);
						ModelUtilisateur::update($data);
						$_SESSION['admin'] = false; // on met également à jour la variable "SESSION"
						}
					}
					$pagetitle = "MAJ de l'utilisateur";
					$view = "updated";
					require ("{$ROOT}{$DS}view{$DS}view.php");
				}
			} else {
				$pagetitle = "Erreur de modification";
				$view = "error";
				$errorMessage = "Vous n'avez pas accès à cette page.";
				require ("{$ROOT}{$DS}view{$DS}view.php");
			}
			break;
	}
?>