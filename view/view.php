<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $pagetitle; ?></title>
    </head>
    <body>
		<header style ="text-align:center;">
			<h1>Vente de téléphones</h1>
		</header>
		
		<section>
            <aside style="display:inline-block; margin-right:15%; vertical-align:top;">
                <?php
					if (Session::isConnected()){
						$obj_utilisateur = ModelUtilisateur::getUtilisateurByNum($_SESSION['numUtilisateur']);
						$prenom = $obj_utilisateur->getPrenom();
						$prenom_esc = htmlspecialchars($prenom);
						echo "Bienvenue sur notre site $prenom_esc";
						echo "<p><a href='index.php?controller=utilisateur&action=update'>Modifier son profil</a></p>";
						echo "<p><a href='index.php?controller=utilisateur&action=logout'>Se déconnecter</a></p>";
					} else {
						echo "Bienvenue sur notre site";
						echo "<p><a href='index.php?controller=utilisateur&action=login'>Se connecter</a></p>";
						echo "<p><a href='index.php?controller=utilisateur&action=create'>S'inscrire sur le site</a></p>";
					}
				?>
				<p><a href='index.php?controller=panier'>Voir le panier</a></p>
				<?php
					if (Session::isAdmin())
						echo "<p><a href='index.php?controller=produit&action=create'>Créer un produit</a></p>";
				?>
			</aside>
            <article style="display:inline-block; margin:auto; width:50%;">
                <?php
                    $filepath = "{$ROOT}{$DS}view{$DS}{$controller}{$DS}";
                    $filename = 'view' . ucfirst($view) . ucfirst($controller) . '.php';
                    require "{$filepath}{$filename}";
                ?>
			</article>
		</section>
	
		<footer style="border: 1px solid black; text-align:center;">
			Site e-commerce de vente de téléphones par BARRAL Jordan et BULATOVIC Alexandre.
		</footer>
	</body>
</html>
