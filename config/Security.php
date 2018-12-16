<?php

	class Security {
		
		private static $seed = 'dA47VjskI4';
	
		static public function getSeed() {
			return self::$seed;
		}
		
		static public function chiffrer($texte_en_clair) {
			$texte_chiffre = hash('sha256', $texte_en_clair . self::getSeed());
			return $texte_chiffre;
		}
		
		static public function generateRandomHex() { // pour créer un nonce de validation d'e-mail
			// Generate a 32 digits hexadecimal number
			$numbytes = 16; // Because 32 digits hexadecimal = 16 bytes
			$bytes = openssl_random_pseudo_bytes($numbytes); 
			$hex = bin2hex($bytes);
			return $hex;
		}	
	}
?>
