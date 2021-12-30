<?php

session_start();

$PartID_GET = $_GET['part'];
$partname = $_GET['name'];
$ColorID_GET = $_GET['color'];
$search_key = $_GET['sk'];
$file_GET = $_GET['file'];
$offset = $_GET['offset'];
$limit = 10;


// Create connection
include('conn.php');

//Question to database
$query_count = "SELECT COUNT(*) AS total
FROM inventory, sets, images, categories
WHERE sets.SetID=inventory.SetID 
AND sets.CatID = categories.CatID
AND inventory.ItemTypeID='P' 
AND images.ItemID=sets.SetID
AND inventory.ColorID='$ColorID_GET' 
AND inventory.ItemID='$PartID_GET' 
ORDER BY sets.Year DESC
";

//save result to variable
$data = array();
//run query to count all sets
$result = runQuery($query_count, $data);

//save sets found to variable
foreach ($result as $var_1) {
	foreach ($var_1 as $var_2) {
		$rows = $var_2;
	}
} 

//$rows = sizeof($result); // total sets found

//Question to database
$query = "SELECT inventory.SetID, sets.Year, sets.Setname, inventory.Quantity, 
images.has_largegif, images.has_largejpg, images.ItemID, categories.CatID, categories.Categoryname,
sets.CatID, inventory.ColorID
FROM inventory, sets, images, categories
WHERE sets.SetID=inventory.SetID 
AND sets.CatID = categories.CatID
AND inventory.ItemTypeID='P' 
AND images.ItemID=sets.SetID
AND inventory.ColorID='$ColorID_GET' 
AND inventory.ItemID='$PartID_GET' 
ORDER BY sets.Year DESC
LIMIT $limit
OFFSET $offset
";



//save result to variable
$data = array();

// run query
$result = runQuery($query, $data);

?>
<!doctype html>
<html lang="sv">
<head>
	<link href="style.css" media="screen" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Julius+Sans+One&display=swap" rel="stylesheet">
	<meta charset="utf-8">
	<title>Lego databas</title>
</head>
<body>
<img alt="" src="images/logo.gif" class="logo">

<div class="all">
	
	<div class="meny">
		<nav>			
			<a href="omlego.php">Om lego</a>
			<a href="index.php">Hem</a>
			<a href="omoss.php">Kontakt</a>
		</nav>
	</div>
	
	<?php 
	
	echo "<p><b>$rows SET</b> som tillhör biten <b>$partname</b></p>";
	$fileName = "http://weber.itn.liu.se/~stegu76/img.bricklink.com/$file_GET";
	echo "<img alt='' src='$fileName'/>";
	echo "<a id='a-res' href='result.php?offset=0&sk=" . urlencode($search_key) . "'>Tillbaka</a>";
	
	echo "<div class='lego-container'>";
	
	$found = false; //variable for checking if results were found	
	
	//	Skriv	ut	alla	poster	i	svaret		
	foreach($result as $row){
		$found = true;
		$img_found = false; //variable for checking if images were found
		//save data for each piece
		$setID = $row['SetID'];
		$year = $row['Year'];
		$set = $row['Setname'];
		$catName = $row['Categoryname'];
		$quantity = $row['Quantity'];
		$gif = $row['has_largegif'];		
		$jpg = $row['has_largejpg'];
		
		// image
		if(!empty($gif)){		
			$file = "SL/$setID.gif";
			$img_found = true;
		}
		if(!empty($jpg)){
			$file = "SL/$setID.jpg";
			$img_found = true;
			
		}
		$fileName = "http://weber.itn.liu.se/~stegu76/img.bricklink.com/$file";
		
		echo "<div class='card'>";
		echo "<p><b>$set</b></p>";
		echo "<p>Antal bitar: $quantity</p>";
		echo "<p>Kategori: $catName</p>";
		echo "<p>År: $year</p>";
		
		if($img_found){
			echo "<img alt='' src='$fileName'/>";
		}else{
			echo "<p>Ingen bild hittades</p>";
		}
		echo "</div>";
	}
	
	echo "</div>"; // CLOSE LEGO CONTAINER
	
	// PAGINATIONS 
	
	$next_offset = $offset + $limit;
	$prev_offset = $offset - $limit;
	$temp_page = ($offset/$limit) + 1;
	$pages = ceil($rows/$limit);
	
	//tell user if no results were found
	if(!$found){
		echo "<p>Inga resultat hittades</p>";
	}else{
		// print paginations
		echo "<div class='pagination'>";
		if($temp_page != 1){
			echo "<a href='?offset=$prev_offset&part=$PartID_GET&color=$ColorID_GET&file=$file_GET&name=" . urlencode($partname) . "&sk=" . urlencode($search_key) . "'>Föregående sida</a>";
			// echo "<a href='?sk=" . urlencode($search_key) . "&offset=$prev_offset'>Föregående sida</a>";		
		}

		echo "<p>Visar sida $temp_page av $pages</p>"; 

		if($temp_page != $pages){
			echo "<a href='?offset=$next_offset&part=$PartID_GET&color=$ColorID_GET&file=$file_GET&name=" . urlencode($partname) . "&sk=" . urlencode($search_key) . "'>Nästa sida</a>";
			// echo "<a href='?sk=" . urlencode($search_key) . "&offset=$next_offset'>Nästa sida</a>";
		}
		echo "</div>";
	}

	?>
	
</div>

</body>

</html>