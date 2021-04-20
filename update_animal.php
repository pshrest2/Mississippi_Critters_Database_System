<?php 
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");
	new_header("Update Animal Information");
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (($output = message()) !== null) {
		echo $output;
	}

	echo "<div class='row'>";
	echo "<label for='left-label' class='left inline'>";

	if (isset($_POST["submit"])) {
		
		if( (isset($_POST["Name"]) && $_POST["Name"] !== "") && (isset($_POST["Sex"]) && $_POST["Sex"] !== "") &&(isset($_POST["Age"]) && $_POST["Age"] !== "") &&(isset($_POST["Species"]) && $_POST["Species"] !== "") &&(isset($_POST["Breed"]) && $_POST["Breed"] !== "") &&(isset($_POST["Color"]) && $_POST["Color"] !== "") &&(isset($_POST["Weight"]) && $_POST["Weight"] !== "") && (isset($_POST["ShelterID"]) && $_POST["ShelterID"] !== ""))  {
		
			$Name = $_POST['Name'];
			$Sex = $_POST['Sex'];
			$Age = $_POST['Age'];
			$Species = $_POST['Species'];
			$Breed = $_POST['Breed'];
			$Color = $_POST['Color'];
			$Weight = $_POST['Weight'];
			$ShelterID = $_POST['ShelterID'];
			$ID = $_POST['ID'];
			
			
			$stmt = $mysqli -> prepare("UPDATE animals SET animal_name=?, animal_sex =?, animal_age=?, animal_species=?,  animal_breed=?, animal_color=?, animal_weight_lbs=?, shelter_ID=? where animal_ID=?");
			$stmt -> execute([$Name, $Sex, $Age, $Species, $Breed, $Color, $Weight, $ShelterID, $ID]);
			
			
		
			if($stmt) {
				$_SESSION["message"] = $_POST["Name"]." has been updated";
				redirect("read_animals.php");
			}
			else {
				$_SESSION["message"] = "Error! Could not update ".$_POST["Name"];
				redirect("read_animals.php");
			}
			
		} else {
				$_SESSION["message"] = "Unable to change animal details. Fill in all information!";
				redirect("read_animals.php");
		}
	
	}
	else {

	  if (isset($_GET["id"]) && $_GET["id"] !== "") {

		$stmt = $mysqli -> prepare("SELECT a.animal_ID AS ID, a.animal_name AS Name, a.animal_sex AS Sex, a.animal_age AS Age, a.animal_species AS Species, a.animal_breed AS Breed, a.animal_color AS Color, a.animal_weight_lbs AS Weight, s.shelter_name AS ShelterName FROM animals a NATURAL JOIN shelters s WHERE a.animal_ID=?");
		$stmt -> execute([$_GET["id"]]);
	  
		
		if ($stmt)  {
			//Fetch associative array from executed prepared statement
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<h3>".$row["Name"]." Information</h3>";
			//Output the movie we are updating
			//UNCOMMENT ONCE YOU'VE COMPLETED THE FILE
			echo "<form method='POST' action='update_animal.php'>";
			echo "<p>Name: <input type=text name='Name' value = '".$row['Name']."' /></p>";
			echo "<p>Sex:<input type='text' name='Sex' placeholder = 'M/F' value = '".$row['Sex']."' /></p>";
			echo "<p>Age:<input type='number' name='Age' min='0' value = '".$row['Age']."' /></p>";
			echo "<p>Species:<input type='text' name='Species' value = '".$row['Species']."' /></p>";
			echo "<p>Breed:<input type='text' name='Breed' value = '".$row['Breed']."' /></p>";
			echo "<p>Color:<input type='text' name='Color' value = '".$row['Color']."' /></p>";
			echo "<p>Weight(lbs):<input type='number' name='Weight' value = '".$row['Weight']."' /></p>";
			echo "<input type = 'hidden' name = 'ID' value = ' ".$row['ID']." ' />";
			
			$query = "SELECT shelter_ID AS ShelterID, shelter_name AS ShelterName FROM shelters ORDER BY ShelterName";
			$stmt2 = $mysqli -> prepare($query);
			$stmt2 -> execute();

			echo "<p>Shelter:<br /></p><select name='ShelterID'>";
			echo "<option value=''></option>";
			while($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				echo "<option value='".$row['ShelterID']."'>".$row['ShelterName']."</option>";
			}
			echo "</select>";
			echo "<input type='submit' name='submit' class='button tiny round' value='Update' />";
			echo "</form>";
		}

			echo "<br /><p>&laquo:<a href='read_animals.php'>Back to Main Page</a>";
			echo "</label>";
			echo "</div>";
					

		}
		else {
			$_SESSION["message"] = "Animal could not be found!";
			redirect("read_animals.php");
		}
	  }
    }
		
new_footer("February 2020 Movies");
Database::dbDisconnect($mysqli);		
?>