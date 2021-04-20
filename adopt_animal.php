<?php 
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");

	new_header("Add animal for adoption"); 
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (($output = message()) !== null) {
		echo $output;
	}

	echo "<div class='row'>";
	echo "<label for='left-label' class='left inline'>";
	if (isset($_POST["submit"])) {
		if( (isset($_POST["Name"]) && $_POST["Name"] !== "") && (isset($_POST["familyID"]) && $_POST["familyID"] !== "")  && (isset($_POST["ID"]) && $_POST["ID"] !== "") ) {
			
			//STEP 3.
			//Create, prepare and execute query to insert movie information that was posted to the form.  Use $stmt3
			$query = "INSERT INTO adoption (animal_ID, family_ID, adoption_date) VALUES (?,?, CURDATE())";

			$stmt = $mysqli->prepare($query);
			$stmt->execute([$_POST["ID"], $_POST["familyID"]]);
					

			//Verify $stmt3 executed
			if($stmt){
				$_SESSION["message"] = "$_POST[Name] has been adopted";
				redirect("read_animals.php");
			}else{

				$_SESSION["message"] = "Error! animal could not be adopted";
				redirect("read_animals.php");
			}		
		}
		else {
				$_SESSION["message"] = "Unable to adopt animal. Fill in all information!";
				redirect("adopt_animal.php");
		}
	}
	else {
		if (isset($_GET["id"]) && $_GET["id"] !== "") {

			$stmt = $mysqli -> prepare("SELECT a.animal_ID AS ID, a.animal_name AS Name FROM animals a WHERE a.animal_ID=?");
			$stmt -> execute([$_GET["id"]]);
		  
			
			if ($stmt)  {
				//Fetch associative array from executed prepared statement

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					echo "<h3>Adopt ".$row["Name"]."</h3>";
				
					echo "<form method='POST' action='adopt_animal.php'>";
					echo "<p>Name:<input type='text' name='Name' value='".$row['Name']."' readonly></p> ";
					echo "<input type = 'hidden' name = 'ID' value = ' ".$row['ID']." ' />";

					$query = "SELECT family_ID AS familyID, family_fname AS FirstName, family_lname AS LastName FROM family ORDER BY familyID";
					$stmt = $mysqli -> prepare($query);
					$stmt -> execute();

					echo "<p>Family:<br /></p><select name='familyID'>";
					echo "<option value=''></option>";
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						echo "<option value='".$row['familyID']."'>".$row['FirstName']." ".$row['LastName']." : ".$row['familyID']."</option>";
					}
					echo "</select>";


					echo "<input type='submit' name='submit' class='button tiny round' value='Adopt Animal'/>";
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
	echo "</label>";
	echo "</div>";


	new_footer("Animal List ");
	Database::dbDisconnect($mysqli);
 ?>