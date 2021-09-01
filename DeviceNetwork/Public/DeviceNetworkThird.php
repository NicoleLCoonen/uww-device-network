<?php require_once('../Private/DataProcessing/placeholder.php');
	  $thisFile = url_for('DeviceNetworkThird.php'); 
?>
<!doctype html>
<head>
	<title>Andersen Device Network</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
	<link rel='stylesheet' href='../Private/DNPStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/DNPsecured.js'> </script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Andersen Library Device Network</h1>
		<h2 id='floor'>Third Floor</h2>
		<span>	
			<button  class='norm' type='button' id='changeLibrary' data-url='<?php echo url_for("DeviceNetworkLenoxUpper.php");?>'>Lenox Maps</button>
			<select id='floorSelect'>
				<option value="" disabled selected hidden>Change Floor</option>
				<option value='<?php echo url_for("DeviceNetworkThird.php") ;?>'>Third Floor</option>
				<option value='<?php echo url_for("DeviceNetworkFirst.php") ;?>'>First Floor</option>
			</select>
			<button class='norm' type="button" id="reports" data-url='<?php echo url_for("reports.php")?>'>Run Reports</button>
			<!--<button type="button" id="admin" data-url='<?php //echo url_for("admin.php");?>'>Admin</button>-->
			<a href='<?php echo(url_for("FAQ.php"))?>'>FAQ</a>
		</span>
	</header>

	
	<main>
		<div id="queries">
		<input id="userInput" type='text' name="userInput" value="" placeholder="Search">
		<button id="reset">Clear</button>
			</br>
		<label for="necromancer">Include withdrawn devices in results:</label>
		<input id="necromancer" type="checkbox" value="0" checked="">
		<!--For future functionality<button type='button' id='deviceView'>Switch to device view</button>-->
		</div>
		
			<?php 
				require_once('graveyard.php');
				require_once('updateForms.php');
			?>
	
		<div id='image-wrapper' data-captions='<?php echo($thirdJson) ; ?>'>
			<img class="map" src="../Private/ThirdBlueprintDNP-100.jpg" alt="A map of the Andersen Library's third floor, with markers indicating the locations of Data Ports" max-width="100%" height="auto"/>
		</div>
		
	</div>
		<?php require("../Private/DataProcessing/updateDB.php"); ?>
	</main>
	
</body>

