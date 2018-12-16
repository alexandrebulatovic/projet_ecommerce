<?php
	class Session {

		public static function isAdmin() {
			return (isset($_SESSION['admin']) && ($_SESSION['admin'] == true));
		}
		
		public static function isConnected() {
			return (isset($_SESSION['numUtilisateur']));
		}
	}
?>