<?php require_once('../Private/DataProcessing/dataAssembly.php');
	 $thisFile = url_for('DeviceNetworkMain.php'); 
	 if(isset($_COOKIE["view"])){
		$view = $_COOKIE["view"];
	 }else{
		echo "<script>window.alert(Cookies are disabled; some features may not work as expected.)</script>" ;
	 };	
	 
?> 

<!doctype html>
<head>
	<title>Andersen Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='<?php echo $style[$view] ;?>'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script id='setView' src='<?php echo $views[$view] ;?>'></script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Andersen Library Device Network</h1>
		<h2 id='floor'>Main Floor</h2>
		<span>
			<button  class='norm' type='button' id='changeLibrary' data-url='<?php echo url_for("DeviceNetworkLenoxUpper.php");?>'>Lenox Maps</button>
			<select id='floorSelect' name="floorSelect">
				<option value="" disabled selected hidden>Change Floor</option>
				<option value='<?php echo url_for("DeviceNetworkThird.php") ;?>'>Third Floor</option>
				<option value='<?php echo url_for("DeviceNetworkFirst.php") ;?>'>First Floor</option>
			</select>
			<button  class='norm' type="button" id="reports" data-url='<?php echo url_for("reports.php");?>'>Run Reports</button>
			<!--<button type="button" id="admin" data-url='<?php //echo url_for("admin.php");?>'>Admin</button>-->
			<a href='<?php echo(url_for("FAQ.php"))?>'>FAQ</a>
		</span>
		
	</header>

	
	<main>

			<div id="queries">
				<input id="userInput" type='text' name="userInput" value="" placeholder="Search">
				<button id="reset">Clear</button>
				</br>
				<label for="includeWithdrawn">Include withdrawn devices in results:</label>
				<input id="includeWithdrawn" type="checkbox">
				<!--<button type='button' id='changeView' data-view='<?php //echo $view ;?>'><?php //echo $buttonText[$view] ;?></button>-->
			</div>
		<div id="container">	

			<?php 
				
				require_once('updateForms.php');
			?>
		<!-- THIS IS FOR A LOWER PRIORITY FUNCTIONALITY THAT IS NOT COMPLETE 
			<button type='button' id='editMarkers'>Add/Edit Markers</button>
			<button type='submit' id='saveMarkers'>Done</button>
		-->
			<div id='image-wrapper' data-captions-port='<?php echo($mainJsonP) ; ?>' data-captions-device='<?php echo($mainJsonD) ; ?>'>
				<img id='MainFloorBlueprint'class="map" src="../Private/Styling/MainBlueprintDNP-100.jpg" alt="A map of the Andersen Library's main floor, with markers indicating the locations of data ports" max-width="100%" height="auto" />
				<caption for='MainFloorBlueprint'>For visual clarity, some ports have been grouped together or adjusted slightly from their physical location.</br>
				Please consider marker loacations to be approximate.</caption>
			</div>
		</div>	
			<?php 
				require_once('graveyard.php');
				require("../Private/DataProcessing/updateDB.php");
			?>
			<p id='output'><?php echo($result) ?></p>
		
	</main>
	
</body>

