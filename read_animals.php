<?php 
	require_once("session.php"); 
	require_once("included_functions.php");
	require_once("database.php");

	new_header("List of All Animals"); 
	$mysqli = Database::dbConnect();
	$mysqli -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (($output = message()) !== null) {
		echo $output;
	}

	
	//****************  Add Query
	//Select from movie and genre tables - you need all the movie attributes and only the genre type of the highest ranking genre (i.e., rank is 1)
	// $query = "SELECT a.animal_ID AS ID, a.animal_name AS Name, a.animal_sex AS Sex, a.animal_age AS Age, a.animal_species AS Species, a.animal_breed AS Breed, a.animal_color AS Color, a.animal_weight_lbs AS Weight, s.shelter_name AS ShelterName,  FROM animals a NATURAL JOIN shelters s NATURAL JOIN adoption";

	$query = "SELECT foo.animal_ID AS ID, a.animal_name AS Name, a.animal_sex AS Sex, a.animal_age AS Age, a.animal_species AS Species, a.animal_breed AS Breed, a.animal_color AS Color, a.animal_weight_lbs AS Weight, s.shelter_name AS ShelterName FROM (SELECT animal_ID FROM (SELECT animal_ID FROM animals UNION ALL SELECT animal_ID FROM adoption)test GROUP BY animal_ID HAVING COUNT(*) = 1)foo NATURAL JOIN animals a NATURAL JOIN shelters s ORDER BY ID";

	//  Prepare and execute query
	$stmt = $mysqli -> prepare($query);
	$stmt -> execute();		
 
	if ($stmt) {
		echo "<div class='row'>";
		echo "<center>";
		echo "<h2>List of All Animals</h2>";
		echo "<table>";
		echo "  <thead>";
		echo "    <tr><th></th><th>Name</th><th>Sex</th><th>Age</th><th>Species</th><th>Breed</th><th>Color</th><th>Weight(lbs)</th><th>Shelter Name</th><th></th><th></th></tr>";
		echo "  </thead>";
		echo "  <tbody>";
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//////////// ADD CODE HERE
			// Retrieve information from query results
			echo "<tr>";
			echo "<td><a style='color:red' href='delete_animal.php?id=".urlencode($row['ID'])."' onclick = 'return confirm(\"Are you sure you want to delete?\");'>X</a></td>";
			echo "<td>".$row['Name']."</td>";
			echo "<td>".$row['Sex']."</td>";
			echo "<td>".$row['Age']."</td>";
			echo "<td>".$row['Species']."</td>";
			echo "<td>".$row['Breed']."</td>";
			echo "<td>".$row['Color']."</td>";
			echo "<td>".$row['Weight']."</td>";
			echo "<td>".$row['ShelterName']."</td>";
			echo "<td><a href='update_animal.php?id=".urlencode($row['ID'])."'>Edit</a></td>";	
			echo "<td><a href='adopt_animal.php?id=".urlencode($row['ID'])."'>Adopt</a></td>";
			echo "</tr>";
		}
		echo "  </tbody>";
		echo "</table>";
		/////////////////  ADD CODE HERE
		// Create a link to create.php and call the link "Add a movie"(without the quotes)

		echo "<br /><br /><a href='create_animal.php'>Add an animal</a>";
		echo "</center>";
		echo "</div>";
	}

	new_footer("Animal List");
	Database::dbDisconnect($mysqli);
 ?>