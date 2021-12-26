<?php
	class DbHelper {
		private static $_dbUsername = "root";
		private static $_dbPassword = "";
		private static $_dbHost = "localhost";
		private static $_dbName = "testdb";
		private static $_dbOptions = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );
		
		public static function GetConnection() {
			$conn = null;
			try {
				$conn = new PDO(
					"mysql:host=" . self::$_dbHost . ";dbname=" . self::$_dbName,
					self::$_dbUsername,
					self::$_dbPassword,
					self::$_dbOptions);
			} catch(PDOException $ex) {
				die("Възникна грешка при опит за осъществяване на връзка към базата данни: " . $ex->getMessage());
			}
			return $conn;
		}
	}
?>