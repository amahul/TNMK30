<?php

session_start();

$search_key = $_POST['search_key'];
$limit = 10;
$offset = $_GET['offset'];

if(empty($_POST['search_key'])){
	$search_key = $_GET['sk'];
}

// Create connection
include('conn.php');

//Question COUNT to database
$query_count = "SELECT COUNT(DISTINCT inventory.ItemTypeID, 
inventory.ColorID, inventory.ItemID, 
colors.Colorname, parts.Partname, images.has_gif, images.has_jpg, 
images.ItemID, images.ItemTypeID, images.ColorID, parts.PartID)
FROM inventory, colors, parts, images
WHERE inventory.ItemtypeID='P' 
AND colors.ColorID=inventory.ColorID 
AND parts.PartID=inventory.ItemID
AND images.ItemID=inventory.ItemID
AND images.ColorID=inventory.ColorID
AND images.ItemtypeID='P' 
AND parts.Partname LIKE '$search_key'
";

//save result to variable
$data = array();

//run query to count all pieces
$result = runQuery($query_count, $data);

//save pieces found to variable
foreach ($result as $var_1) {
	foreach ($var_1 as $var_2) {
		$pieces = $var_2;
	}
} 


$query = "SELECT DISTINCT inventory.ItemTypeID, 
inventory.ColorID, inventory.ItemID, 
colors.Colorname, parts.Partname, images.has_gif, images.has_jpg, 
images.ItemID, images.ItemTypeID, images.ColorID, parts.PartID
FROM inventory, colors, parts, images
WHERE inventory.ItemtypeID='P' 
AND colors.ColorID=inventory.ColorID 
AND parts.PartID=inventory.ItemID
AND images.ItemID=inventory.ItemID
AND images.ColorID=inventory.ColorID
AND images.ItemtypeID='P' 
AND parts.Partname LIKE '$search_key'
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
	
	// print row if pieces found
	if($pieces != 0){
		echo "<p>Din sökning på <b>$search_key</b> gav <b>$pieces träffar</b></p>";
	}
	echo "<a id='a-res' href='index.php'>Sök igen?</a>";
	echo "<div class='lego-container'>";
	
	$found = false; //variable for checking if results were found

	//	Skriv	ut	alla	poster	i	svaret		
	foreach($result as $row){
		$found = true; // check if at least 1 piece was found
		$img_found = false; //variable for checking if images were found
		
		//save data for each piece
		$color	=	$row['Colorname'];				
		$partname	=	$row['Partname'];		
		$ItemTypeID	=	$row['ItemTypeID'];		
		$ColorID	=	$row['ColorID'];		
		$ItemID	=	$row['ItemID'];		
		$gif	=	$row['has_gif'];		
		$jpg	=	$row['has_jpg'];
		$PartID	=	$row['PartID'];
		
		
		// check for image paths
		if(!empty($jpg)){
			$file = "$ItemTypeID/$ColorID/$ItemID.jpg";
			$img_found = true;
			
		}else if(!empty($gif)){		
			$file = "$ItemTypeID/$ColorID/$ItemID.gif";
			$img_found = true;
		}
		// full filename for images
		$fileName = "http://weber.itn.liu.se/~stegu76/img.bricklink.com/$file";
		
		// print the pieces		
		// open card div
		echo "<div class='card'>";
		echo $partID;
		
		echo "<p>$partname</p><br>"; // print partname
		
		echo "<p>$color</p>"; // print color
		echo "<div class='img-div'>"; // open img div
		echo "<img alt='' src='$fileName'/>";
		echo "</div>"; //close img-div
		
		//print button
		echo "<a class='set-btn' href='sets.php?offset=0&part=$PartID&color=$ColorID&file=$file&name=" . urlencode($partname) . "&sk=" . urlencode($search_key) . "'>Vilka sets?</a>";
		echo "</div>"; 
		// close card-div		

		<a href='test.php?set='
		
	}	//	end for		
	
	echo "</div>"; // close lego-container
	
	// PAGINATIONS
	$next_offset = $offset + $limit;
	$prev_offset = $offset - $limit;
	$temp_page = ($offset/$limit) + 1;
	$pages = ceil($pieces/$limit);
	//tell user if no results were found
	if(!$found){
		echo "<p>Inga resultat hittades</p>";
	}else{
		// print paginations
		echo "<div class='pagination'>";
		if($temp_page != 1){
			echo "<a href='?sk=" . urlencode($search_key) . "&offset=$prev_offset'>Föregående sida</a>";		
		}

		echo "<p>Visar sida $temp_page av $pages</p>";

		if($temp_page != $pages){
			echo "<a href='?sk=" . urlencode($search_key) . "&offset=$next_offset'>Nästa sida</a>";
		}
		echo "</div>";
		
	}

	?>

</div>

</body>

</html>