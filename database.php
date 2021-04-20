<?php
class Database {
  public function __construct() {
    die('Init function error');
  }

  public static function dbConnect() {
  	$mysqli = null;

  	//try connecting to your database
  	require_once("/home/pshrest2/DBshrestha.php");

  	//catch a potential error, if unable to connect
    try {
      $mysqli = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME,USERNAME,PASSWORD);
      echo "Successful Connection";
    } catch (Exception $e) {
      echo "Could not connect";
      die();
    }
    return $mysqli;
  }

  public static function dbDisconnect() {
    $mysqli = null;
  }
}
?>
