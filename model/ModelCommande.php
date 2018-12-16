<?php
	require_once 'Model.php';
	require_once "{$ROOT}{$DS}model{$DS}ModelProduit.php";
	require_once "{$ROOT}{$DS}model{$DS}ModelPanier.php";
	
	class ModelCommande {
		
		private $numCommande;
		private $numUtilisateur;
		private $prix;
		private $date;
		
		public function __construct($numC = NULL, $numU = NULL, $p = NULL, $d = NULL){
			if (!is_null($numC) && !is_null($numU) && !is_null($p) && !is_null($d)) {
				$this->numCommande = $numC;
				$this->numUtilisateur = $numU;
				$this->prix = $p;
				$this->date = $d;
			}
		}
		
		public function getNumCommande(){return $this->numCommande;}
		public function getNumUtilisateur(){return $this->numUtilisateur;}
		public function getPrix(){return $this->prix;}
		public function getDate() {return $this->date;}
		
	    public static function insertCommande($numU, $prix){ // retourne le numéro de la nouvelle commande
			try{
				$sql = "INSERT INTO commande(numUtilisateur, prix) VALUES(:numU, :prix)";
				$req_prep = Model::$pdo->prepare($sql);
				$values = array(
					"numU"=>$numU,
					"prix"=>$prix
				);
				$req_prep->execute($values);
				// on récupère ensuite le numéro de commande pour insérer la liste des produits du panier dans la table "contenir"
				$sql = "SELECT numCommande FROM commande WHERE numUtilisateur = :numU ORDER BY numCommande DESC LIMIT 1";
				$req_prep = Model::$pdo->prepare($sql);	
				$value = array("numU"=>$numU);
				$req_prep->execute($value);
				$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelCommande');
				return $req_prep->fetch();
			} catch(PDOException $e) {
				if (Conf::getDebug()) {
					echo $e->getMessage(); // affiche un message d'erreur
				} else {
					echo "<p>Une erreur est survenue.</p>
					<p><a href='index.php'> Retour à la page d'accueil</a></p>";
					die();
				}
			}
		}
		
		public static function processCart($produits, $numU, $prix){
			$commande = ModelCommande::insertCommande($numU, $prix);
			
			foreach($produits as $p){
				try{
					$sql = "UPDATE produit SET stock = stock-:s WHERE numProduit = :numP";
					$req_prep = Model::$pdo->prepare($sql);
					$values = array(
						"s" => $p->getStock(), // correspond à la quantité achetée
						"numP" => $p->getNumProduit()
					);
					$req_prep->execute($values);
					
					$sql = "INSERT INTO contenir(numCommande, numProduit, quantite) VALUES(:numC, :numP, :q)";
					$req_prep = Model::$pdo->prepare($sql);
					$values = array(
						"numC" => $commande->getNumCommande(),
						"numP" => $p->getNumProduit(),
						"q" => $p->getStock()
					);
					$req_prep->execute($values);
				} catch(PDOException $e) {
					if (Conf::getDebug()) {
						echo $e->getMessage(); // affiche un message d'erreur
					} else {
						echo "<p>Une erreur est survenue.</p>
						<p><a href='index.php'> Retour à la page d'accueil</a></p>";
						die();
					}
				}
			}
		}
	}
?>