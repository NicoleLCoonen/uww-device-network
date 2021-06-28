<?php require_once('..\Private\DataProcessing\placeholder.php');

?>

<!doctype html>
<head>
	<title>Andersen Device Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
	<link rel='stylesheet' href='../Private/DNPStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/DNP.js'> </script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Andersen Library Device Network</h1>
		<h2 id='floor'>Third Floor</h2>
		<span>
			<button type='button' id='changeLibrary' data-url='<?php echo url_for("DeviceNetworkLenoxUpper.php");?>'>Lenox Maps</button>
			<select id='floorSelect'>
				<option value='' disabled selected hidden>Change Floor</option>
				<option value="<?php echo url_for("DNMapMain.php");?>">Main Floor</option>
				<option value="<?php echo url_for("DNMapFirst.php");?>">First Floor</option>
			</select>
			<button type="button" id="reports" data-url='<?php echo url_for("reports.php")?>'>Run Reports</button>
			<a href="<?php echo(url_for("DNLogIn.php")); ?>">Log In</a>
		</span>
	</header>

	
	<main>
		<div id="queries">
		<input id="userInput" type='text' name="userInput" value="" placeholder="Search">
		<button id="reset">Clear</button>
			</br>
		<label for="necromancer">Include withdrawn devices in results:</label>
		<input id="necromancer" type="checkbox" value="0" checked="">
		</div>
		
	<div id="container">
		<div class="graveyard">
			<table class="graveyard">
				<th>Name</th>
				<th>Noncap</th>
				<th>Model</th>
				<th>Vendor</th>
				<th>Vendor ID</th>
				<th>Date Removed</th>
				<th>Destination</th>
				<th>Notes</th>
				<?php if(isset($result_set)){
					confirm_result_set($result_set);
					
					while($result = mysqli_fetch_assoc($result_set)){
							foreach($result as $th => $td){
									if($td == null){
										$td = "N/A" ;
									};
								$result[$th] = $td;	
							};
							
							$date = explode("-", $result["Date_Removed"]) ;
							$year = $date[0] ;
							$month = $date[1] ;
							$day = $date[2] ;
							$result["Date_Removed"] = $month . "-" . $day . "-" . $year ;
				?>
				<tr>
					<td><?php echo($result["Device_Name"]) ;?></td>
					<td><?php echo($result["Noncap"]) ;?></td>
					<td><?php echo($result["Model"]) ;?></td>
					<td><?php echo($result["Vendor"]) ;?></td>
					<td><?php echo($result["Vendor_Name"]) ;?></td>
					<td><?php echo($result["Date_Removed"]) ;?></td>
					<td><?php echo($result["Sent_To"]) ;?></td>
					<td><?php echo($result["Notes"]) ;?></td>
				</tr>	

			<?php					
					};
				};
				
			?>
				
			</table>
		</div>
		
		<div id='image-wrapper' data-captions='<?php echo($thirdJson) ; ?>'>
			<img class="map" src="../Private/ThirdBlueprintDNP-100.jpg" alt="A map of the Andersen Library's third floor, with markers indicating the locations of Data Ports" max-width="100%" height="auto"/>
		</div>
		
	</main>
	
</body>
