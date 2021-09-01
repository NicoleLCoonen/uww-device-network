<?php require_once('../Private/DataProcessing/placeholder.php');
	 $thisFile = url_for('DeviceNetworkLenoxUpper.php'); 
?>
<!doctype html>
<head>
	<title>Lenox Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/LenoxStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src='../Private/DNP.js'> </script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Lenox Library Device Network</h1>
		<h2 id='floor'>Upper Level</h2>
		<span>
			<button  class='norm' type='button' id='changeLibrary' data-url='<?php echo url_for("DNMapMain.php");?>'>Andersen Maps</button>
			<a href='<?php echo url_for("DNMapLenoxLower.php");?>'>Lower Level</a>
			
		</span>
	</header>

	<main>
		
			<div id="queries">
				<input id="userInput" type='text' name="userInput" value="" placeholder="Search">
				<button id="reset">Clear</button>
				</br>
				<label for="necromancer">Include withdrawn devices in results:</label>
				<input id="necromancer" type="checkbox">
			</div>
		<div id="container">	

			
			<div id='image-wrapper' data-captions='<?php echo($lenoxUJson); ?>'>
				<img id='blueprint'class="map" src="../Private/LenoxBlueprintUpper-100.jpg" alt="A map of the Lenox Library's main floor, with markers indicating the locations of data ports" max-width="100%" height="auto" />
			</div>
		</div>	
		<?php 
				require_once('graveyard.php');
				
			?>
		
		
	</main>
	
</body>