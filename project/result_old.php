<?php

session_start();

$search_key = $_POST['search_key'];

if(empty($_POST['search_key'])){
	$search_key = $_GET['sk'];
}

$page = 0;
$page = $_GET['page']; //which pagination page

$start = 0;
$start = $_GET['start']; // how many offsets in database query

include('conn.php');
// Create connection
//$conn	=	mysqli_connect("mysql.itn.liu.se","lego", "", "lego");

//Question to database
$query = "SELECT inventory.SetID, sets.Setname, sets.SetID, inventory.ItemTypeID, 
inventory.ColorID, inventory.ItemID, inventory.Quantity, 
colors.Colorname, parts.Partname, images.has_gif, images.has_jpg, 
images.ItemID, images.ItemTypeID, images.ColorID, parts.PartID
FROM inventory, colors, parts, images, sets
WHERE inventory.ItemtypeID='P' 
AND colors.ColorID=inventory.ColorID 
AND parts.PartID=inventory.ItemID
AND images.ItemID=inventory.ItemID
AND images.ColorID=inventory.ColorID
AND images.ItemtypeID='P' 
AND inventory.SetID = sets.SetID 
AND parts.Partname LIKE '%$search_key%' 
LIMIT 1000
";


$query = "SELECT DISTINCT ItemID, ColorID 
FROM inventory 
WHERE inventory.ItemtypeID='P'



";


if($start){
	$query = $query." OFFSET $start";
}

//save result to variable
$data = array();
//$result	=	mysqli_query($conn,	$query);
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
	
	<h2 class="h2-res">Resultat</h2>
	<a id="a-res" href="index.php">Sök igen?</a>
	<br><br>
	
	<div class="lego-container">
	<?php 
	
	$found = false; //variable for checking if results were found
	$unique = 0; //amount of unique pieces found
	$show = 0;
	
	
	$amount = $start;
	$breaks = []; //array with page breaks
	$sets = []; //array with sets for each unique piece
	//	Skriv	ut	alla	poster	i	svaret		
	foreach($result as $row){
		$found = true;
		$amount++;
		$img_found = false; //variable for checking if images were found
		
		//save data for each piece
		$quantity	=	$row['Quantity'];		
		$color	=	$row['Colorname'];		
		$set	=	$row['Setname'];		
		$partname	=	$row['Partname'];		
		$ItemTypeID	=	$row['ItemTypeID'];		
		$ColorID	=	$row['ColorID'];		
		$ItemID	=	$row['ItemID'];		
		$gif	=	$row['has_gif'];		
		$jpg	=	$row['has_jpg'];
		$PartID	=	$row['PartID'];
		
				
		//save each set in array
		array_push($sets, $set);		
		
		//image 
		if(!empty($jpg)){
			$file = "$ItemTypeID/$ColorID/$ItemID.jpg";
			$img_found = true;
			
		}else if(!empty($gif)){		
			$file = "$ItemTypeID/$ColorID/$ItemID.gif";
			$img_found = true;
		}
		
		$fileName = "http://weber.itn.liu.se/~stegu76/img.bricklink.com/$file";
		
				
		//check for unique pieces
		if($temp_ColorID != $ColorID){
			//close div when new unique piece found
			//and print div with sets
		
			if($show != 0 && $show <= 10){
				if(!$img_found){
					echo "<p>Bild saknas</p>";
				}
				
				$img_found = false; // reset
				echo "</div>"; //close img-div
				
				//print button
				echo "<a class='set-btn' href='sets.php?part=$PartID&color=$ColorID&file=$file&name=" . urlencode($partname) . "&sk=" . urlencode($search_key) . "'>Vilka sets?</a>";
				// echo "<input type='button' class='set-btn' onclick='sets($unique)' value='Vilka sets?' id='btn-$unique'>";
				echo "</div>"; // close card-div			
				
				
			}
			
			$unique++;	
			$show++;
		}
		
		//show new piece with name when unique
		if($show <= 10 && $temp_ColorID != $ColorID){					
			echo "<div class='card'>";
			
			echo "<p>$partname</p><br>";
			
			echo "<p>$color</p><div class='img-div'>";
			echo "<img alt='' src='$fileName'/>";
		}
				
		//save page break when found each 10 unique
		if($unique == 10){
			
			$unique = 0;
			$breaks[] = $amount; //save each page break in array
		}

		//save current color as temp
		$temp_ColorID = $ColorID;
	}	//	end for		

	//tell user if no results were found
	if(!$found){
		echo "<p>Inga resultat hittades</p>";
	}
	
	if($start == 0){//save array breaks to a session array when first page
		$_SESSION['breaks'] = $breaks; 
	}
	
	//set value for pages
	$next_page = $page + 1;
	$prev_page = $page - 1;
	
	//catch start numbers for database from array
	$next_start = $_SESSION['breaks'][$page];
	$prev_start = $_SESSION['breaks'][$prev_page-1];
	
	//paginations
	$pages = count($_SESSION['breaks']);
	$temp_page = $page+1;
	
	echo "</div>"; // close lego-container
	
	if($show > 10){
		
		echo "<div class='pagination'>";
		if($page != 0){
			echo "<a href='?sk=" . urlencode($search_key) . "&page=$prev_page&start=$prev_start'>Föregående sida</a>";		
		}
		
		echo "<p>Visar sida $temp_page av $pages</p>";
		
		
		if($page != $pages -1){
			echo "<a href='?sk=" . urlencode($search_key) . "&page=$next_page&start=$next_start'>Nästa sida</a>";
		}
		echo "</div>";
	}
	
	?>
	
	

</div>

</body>

</html>