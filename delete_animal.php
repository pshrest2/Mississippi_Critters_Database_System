<?php 
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");
	new_header("Delete Animal");
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (($output = message()) !== null) {
		echo $output;
	}

	
  	if (isset($_GET["id"]) && $_GET["id"] !== "") {

		$stmt = $mysqli -> prepare("DELETE FROM animals where animal_ID = ?");
		$stmt -> execute([$_GET["id"]]);
	  
		if ($stmt) {
			$_SESSION["message"] = "Animal removed from system successfully";
			redirect("read_animals.php");
			
		}
		else {
			$_SESSION["message"] = "Error! Could not delete ".$_POST["title"];
			redirect("read_animals.php");
			
		}
			
	}
	else {
		$_SESSION["message"] = "Movie could not be found!";
		redirect("readS21.php");
	}
new_footer("February 2020 Movies");
Database::dbDisconnect($mysqli);
			
?>