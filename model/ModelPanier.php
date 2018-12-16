<?php
	require_once 'Model.php';
	require_once 'ModelProduit.php';

	class ModelPanier{
		
		public static function ajouterAuPanier($numproduit, $quantite){
			$panier = array();
			
			if(isset($_COOKIE['panier']))
				$panier = unserialize($_COOKIE['panier']);
			
			$panier[$numproduit] = $quantite;
			setcookie("panier", serialize($panier), time()+3600); //expire dans 1 heure
		}
		
		public static function supprimerDuPanier($numproduit){
			
			if(isset($_COOKIE['panier'])){
				
				$panier = unserialize($_COOKIE['panier']); //on récupère la variable au format "tableau"
				unset($panier[$numproduit]);
				setcookie("panier", serialize($panier), time()+3600);
			} 
		}
		
		public static function getProduitsPanier($panier){
			$tab_p = array();
			
			foreach ($panier as $key => $value){
				$obj =  ModelProduit::getProduitByNum($key);
				$obj->setStock($value); // on "détourne" un objet 'produit' et on utilise sa variable 'stock' pour enregistrer la quantité que le client veut acheter
				array_push($tab_p, $obj); // on a donc ici les produits avec leurs caractéristiques et la quantité à acheter à la place du stock
			}
			return $tab_p;
		}
		
		public static function totalPanier(){
			
			$total = 0;
			
			if(isset($_COOKIE['panier'])){
				$panier = unserialize($_COOKIE['panier']);
				$tab_p = self::getProduitsPanier($panier);
				
				foreach($tab_p as $p){
					$total = $total + ($p->getStock() * $p->getPrix());
				}
			}
			return $total;
		}
		
		public static function viderPanier(){
			setcookie("panier", "");
		}
	}
?>