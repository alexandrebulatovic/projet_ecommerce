<?php
	require_once 'Model.php';

	class ModelProduit {
		
		private $numProduit;
		private $nomProduit;
		private $prix;
		private $description;
		private $stock;
		
		public function __construct($numP = NULL, $nomP = NULL, $p = NULL, $d = NULL, $s = NULL){
			if (!is_null($nomP) && !is_null($p) && !is_null($d) && !is_null($s)) {
				$this->numProduit = $numP;
				$this->nomProduit = $nomP;
				$this->prix = $p;
				$this->description = $d;
				$this->stock = $s;
			}
		}
		
		public function getNumProduit(){return $this->numProduit;}
		public function getNomProduit(){return $this->nomProduit;}
		public function getPrix(){return $this->prix;}
		public function getDescription(){return $this->description;}
		public function getStock() {return $this->stock;}
		public function setStock($s) { $this->stock = $s; }
		
		public function save(){
			$this->insertProduit($this->nomProduit, $this->prix, $this->description, $this->stock);
		}
		
		public static function insertProduit($nomP, $prix, $description, $stock){
			try{
				$sql = "INSERT INTO produit(nomProduit, prix, description, stock) VALUES(:n, :p, :d, :s)";
				$req_prep = Model::$pdo->prepare($sql);
				$values = array(
					"n" => $nomP,
					"p" => $prix,
					"d" => $description,
					"s" => $stock
				);
				$req_prep->execute($values);
			} catch(PDOException $e) {
				if (Conf::getDebug()){
					echo $e->getMessage(); // affiche un message d'erreur
					die();
				}
				else{
					echo "<p>Une erreur est survenue.</p>
					<p><a href='index.php'> Retour à la page d'accueil</a></p>";
					die();
				}
			}
		}

		public static function update($data) {
			try {
				$sql = "UPDATE produit SET nomProduit = :nomP, prix = :prix, description = :description, stock = :stock WHERE numProduit = :numP";
				$req_prep = Model::$pdo->prepare($sql);
				$req_prep->execute($data);
			} catch(PDOException $e) {
				if (Conf::getDebug()){
					echo $e->getMessage(); // affiche un message d'erreur
					die();
				}
				else{
					echo "<p>Une erreur est survenue.</p>
					<p><a href='index.php'> Retour à la page d'accueil</a></p>";
					die();
				}
			}
		}

		public function delete() {
			try {
				$sql = "DELETE FROM produit WHERE numProduit = :numP";
				$req_prep = Model::$pdo->prepare($sql);
				$values = array("numP" => $this->numProduit);
				$req_prep->execute($values);
			} catch(PDOException $e) {
				if (Conf::getDebug()){
					echo $e->getMessage(); // affiche un message d'erreur
					die();
				}
				else{
					echo "<p>Une erreur est survenue.</p>
					<p><a href='index.php'> Retour à la page d'accueil</a></p>";
					die();
				}
			}
		}
		
		public static function getAllProduits(){
			try {
				$sql = "SELECT * FROM produit";
				$rep = Model::$pdo->query($sql);
				$rep->setFetchMode(PDO::FETCH_CLASS, 'ModelProduit');
				$tab_obj = $rep->fetchAll();

				return $tab_obj;
			} catch(PDOException $e) {
				if (Conf::getDebug()){
					echo $e->getMessage(); // affiche un message d'erreur
					die();
				}
				else{
					echo "<p>Une erreur est survenue.</p>
					<p><a href='index.php'> Retour à la page d'accueil</a></p>";
					die();
				}
			}
		}
		
		public static function getProduitByNum($numProduit) {
			$sql = "SELECT * FROM produit WHERE numProduit = :numP";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array("numP" => $numProduit);
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelProduit');
			return $req_prep->fetch();
		}
	}
?>