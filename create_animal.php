<?php 
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");

	new_header("Add Animal"); 
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (($output = message()) !== null) {
		echo $output;
	}

	echo "<div class='row'>";
	echo "<label for='left-label' class='left inline'>";
	echo "<h3>Add an animal</h3>";
	if (isset($_POST["submit"])) {
		if( (isset($_POST["Name"]) && $_POST["Name"] !== "") && (isset($_POST["Sex"]) && $_POST["Sex"] !== "") &&(isset($_POST["Age"]) && $_POST["Age"] !== "") &&(isset($_POST["Species"]) && $_POST["Species"] !== "") &&(isset($_POST["Breed"]) && $_POST["Breed"] !== "") &&(isset($_POST["Color"]) && $_POST["Color"] !== "") &&(isset($_POST["Weight"]) && $_POST["Weight"] !== "") && (isset($_POST["ShelterID"]) && $_POST["ShelterID"] !== "") ) {
			
			//STEP 3.
			//Create, prepare and execute query to insert movie information that was posted to the form.  Use $stmt3
			$query = "INSERT INTO animals (shelter_ID, animal_name, animal_sex, animal_age, animal_species, animal_breed, animal_color, animal_weight_lbs) VALUES (?,?,?,?,?,?,?,?)";

			$stmt = $mysqli->prepare($query);
			$stmt->execute([$_POST["ShelterID"], $_POST["Name"], $_POST["Sex"], $_POST["Age"], $_POST["Species"], $_POST["Breed"], $_POST["Color"], $_POST["Weight"]]);
					

			//Verify $stmt3 executed
			if($stmt){
				$_SESSION["message"] = "$_POST[Name] has been added";
				redirect("read_animals.php");
			}else{

				$_SESSION["message"] = "Error! Movie could not be added";
				redirect("read_animals.php");
			}		
		}
		else {
				$_SESSION["message"] = "Unable to add animal. Fill in all information!";
				redirect("create_animal.php");
		}
	}
	else {
		echo "<form method='POST' action='create_animal.php'>";
		echo "<p>Name:<input type='text' name='Name'></p> ";
		echo "<p>Sex:<input type='text' name='Sex' placeholder = 'M/F'></p> ";
		echo "<p>Age:<input type='number' name='Age' min='0'></p> ";
		echo "<p>Species:<input type='text' name='Species'></p> ";
		echo "<p>Breed:<input type='text' name='Breed'></p> "; 
		echo "<p>Color:<input type='text' name='Color'></p> "; 
		echo "<p>Weight(lbs):<input type='number' name='Weight'></p> "; 

		$query = "SELECT shelter_ID AS ShelterID, shelter_name AS ShelterName FROM shelters ORDER BY ShelterName";
		$stmt = $mysqli -> prepare($query);
		$stmt -> execute();

		echo "<p>Shelter:<br /></p><select name='ShelterID'>";
		echo "<option value=''></option>";
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<option value='".$row['ShelterID']."'>".$row['ShelterName']."</option>";
		}
		echo "</select>";


		echo "<input type='submit' name='submit' class='button tiny round' value='Add Animal'/>";
		echo "</form>";						
	}
	echo "</label>";
	echo "</div>";
	echo "<br /><p>&laquo:<a href='read_animals.php'>Back to Main Page</a>";

	new_footer("Animal List");
	Database::dbDisconnect($mysqli);
 ?>