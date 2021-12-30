<?php

//include(conn.php);

$search_key = $_POST['search_key'];
echo "search action";
echo $search_key;
// Create connection
$conn	=	mysqli_connect("mysql.itn.liu.se","lego", "", "lego");

//Question to database
$query = "SELECT inventory.SetID, sets.Setname, sets.SetID, inventory.ItemTypeID, 
inventory.ColorID, inventory.ItemID, inventory.Quantity, 
colors.Colorname, parts.Partname, images.has_gif, images.has_jpg, 
images.ItemID, images.ItemTypeID, images.ColorID
FROM inventory, colors, parts, images, sets
WHERE inventory.ItemtypeID='P' 
AND colors.ColorID=inventory.ColorID 
AND parts.PartID=inventory.ItemID
AND images.ItemID=inventory.ItemID
AND images.ColorID=inventory.ColorID
AND images.ItemtypeID='P' 
AND inventory.SetID = sets.SetID 
AND parts.Partname LIKE  '%$search_key%'
";

//save result to variable
$result	=	mysqli_query($conn,	$query);


//header('Location: result.php');

?>

<table>
		<tr>
			<th>Quantity</th>
			<th>File name</th>
			<th>Picture</th>
			<th>Color</th>
			<th>Part name</th>
			<th>Set</th>
		</tr>
	
	<?php 
	
	//	Skriv	ut	alla	poster	i	svaret																																		
	while	($row	=	mysqli_fetch_array($result))	{																						
		$quantity	=	$row['Quantity'];		
		$color	=	$row['Colorname'];		
		$set	=	$row['Setname'];		
		$partname	=	$row['Partname'];		
		$ItemTypeID	=	$row['ItemTypeID'];		
		$ColorID	=	$row['ColorID'];		
		$ItemID	=	$row['ItemID'];		
		$gif	=	$row['has_gif'];		
		$jpg	=	$row['has_jpg'];

		
		
		
		
		
		if(!empty($jpg)){
			$file = "$ItemTypeID/$ColorID/$ItemID.jpg";
			
		}else if(!empty($gif)){		
			$file = "$ItemTypeID/$ColorID/$ItemID.gif";
		}else{
			$file ="No picture found!";
		}
		
		$fileName = "http://weber.itn.liu.se/~stegu76/img.bricklink.com/$file";
		if(empty($fileName)){
			$fileName = "No image found";
			
		}
		
		if($temp_partname == $partname){
			//samma
			
		}else{
			
			echo "<tr>";
			echo "<td>$quantity</td><td>$file</td><td><img alt='' src='$fileName' /></td><td>$color</td><td>$partname</td>";
			echo "</tr>";
		}
		
		$temp_partname = $partname;
		
																																																		
	}	//	end	while				
	
	?>
	</table>

