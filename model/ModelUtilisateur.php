<?php
	require_once 'Model.php';

	class ModelUtilisateur{
		
		private $numUtilisateur;
		private $nom;
		private $prenom;
		private $mdp;
		private $admin;
		private $email;
		private $nonce;
			
		public function __construct($n = NULL, $p = NULL, $m = NULL, $a = false, $e = NULL, $no = NULL, $num = NULL){
			if (!is_null($n) && !is_null($p) && !is_null($m) && !is_null($e) && !is_null($no)) {
				$this->numUtilisateur = $num;
				$this->nom = $n;
				$this->prenom = $p;
				$this->mdp = $m;
				$this->admin = $a;
				$this->email = $e;
				$this->nonce = $no;
			}
		}
		
		public function getNum() {return $this->numUtilisateur;}
		public function getNom() {return $this->nom;}
		public function getPrenom() {return $this->prenom;}
		public function getAdmin(){return $this->admin;}
		public function getMdp(){return $this->mdp;}
		public function getEmail(){return $this->email;}
		public function getNonce() {return $this->nonce;}
		
		public function save(){
			$this->insertUtilisateur($this->nom, $this->prenom, $this->mdp, $this->email, $this->nonce);
		}
		
		public static function insertUtilisateur($nom, $prenom, $mdp, $email, $nonce){
			try{
				$sql = "INSERT INTO utilisateur(nom, prenom, mdp, email, nonce) VALUES(:n, :p, :m, :e, :no)";
				$req_prep = Model::$pdo->prepare($sql);
				$values = array(
					"n" => $nom,
					"p" => $prenom,
					"m" => $mdp,
					"e" => $email,
					"no" => $nonce
					);
				$req_prep->execute($values);
			} catch(PDOException $e) {
				if (Conf::getDebug())
					echo $e->getMessage(); // affiche un message d'erreur
				else
					echo "Une erreur est survenue <a href='index.php'> Retour à la page d'accueil</a>";
			}
		}
		
		public static function getAllUtilisateurs(){
			try{
				$rep = Model::$pdo->query("SELECT * FROM utilisateur");
				$rep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
				$tab_obj = $rep->fetchAll();
				
				return $tab_obj;
			} catch(PDOException $e) {
				if (Conf::getDebug())
					echo $e->getMessage(); // affiche un message d'erreur
				else
					echo "Une erreur est survenue <a href='index.php'> Retour à la page d'accueil</a>";
			}
		}

		public static function getUtilisateurByNum($num) {
			$sql = "SELECT * from utilisateur WHERE numUtilisateur = :num";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array("num" => $num);	 
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
			return $req_prep->fetch();
		}	
		
		
		public static function userExist($email){ // renvoie vrai si l'utilisateur existe dans la BD, sinon renvoie faux
			// on vérifie la colonne e-mail qui a une contrainte SQL "UNIQUE" dans la base de données
			$sql = "SELECT * from utilisateur WHERE email = :email";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array("email" => $email);	 
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
			
			if(empty($req_prep->fetch()))
				return false;
			else
				return true;
		}

		public function delete () {
			try {
			$sql = "DELETE FROM utilisateur WHERE numUtilisateur = :num";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array ("num" => $this->numUtilisateur);
			$req_prep->execute($values);
			} catch(PDOException $e) {
				if (Conf::getDebug())
					echo $e->getMessage(); // affiche un message d'erreur
				else
					echo "Une erreur est survenue <a href='index.php'> Retour à la page d'accueil</a>";
			}
		}
		
		public static function update($data) {
			$sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, mdp = :mdp, admin= :admin, email = :email WHERE numUtilisateur = :num";
			$req_prep = Model::$pdo->prepare($sql);
			$req_prep->execute($data);
		}
		
		public static function checkPassword($email, $mot_de_passe_crypte){ // renvoie le "numUtilisateur" si l'utilisateur a rentré les bons identifiants, sinon renvoie faux
			$sql = "SELECT * from utilisateur WHERE email = :email AND mdp = :mdp";
			$req_prep = Model::$pdo->prepare($sql);

			$values = array(
				"email" => $email,
				"mdp" => $mot_de_passe_crypte
			);	 
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
			$result = $req_prep->fetch();
			
			if(empty($result))
				return false;
			else
				return $result->getNum();
		}
		
		public static function checkNonceEqualsNull($email){ // renvoie vrai si l'utilisateur a validé son e-mail, sinon renvoie faux
			$sql = "SELECT * from utilisateur WHERE email = :email AND nonce IS NULL";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array("email" => $email);
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
			$result = $req_prep->fetch();
			
			if(!empty($result))
				return true;
			else
				return false;
		}
		
		public static function checkNonceEquals($email, $nonce){ // renvoie vrai si l'email et son nonce existent dans la BD, sinon renvoie faux
			$sql = "SELECT * FROM utilisateur WHERE email = :email AND nonce = :nonce";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array(
				"email" => $email,
				"nonce" => $nonce
			);	 
			$req_prep->execute($values);
			$req_prep->setFetchMode(PDO::FETCH_CLASS, 'ModelUtilisateur');
			$result = $req_prep->fetch();
			
			if(!empty($result))
				return true;
			else
				return false;
		}
		
		public static function nonceToNull($email){
			$sql = "UPDATE utilisateur SET nonce = NULL WHERE email = :email";
			$req_prep = Model::$pdo->prepare($sql);
			$values = array("email" => $email);
			$req_prep->execute($values);
		}
		
		public function sendValidationMail(){
			// return true; // dé-commentez pour tester la création de compte
			
			$key = $this->nonce;
			$email = rawurlencode($this->email); // pour éviter des problèmes avec le query string (ou "injection HTML")
			
			$mail = "<p>Merci de votre inscription sur notre plateforme.</p>
			<p>Veuillez valider votre compte en cliquant sur le lien suivant : <a href='http://localhost/projet_eCommerce/index.php?controller=utilisateur&action=validate&email=$email&nonce=$key'>Valider</a></p>";
			
			if(!mail($email, "Validez votre inscription", $mail))
				return false; // en cas d'erreur
			else
				return true;
		}
	}
?>