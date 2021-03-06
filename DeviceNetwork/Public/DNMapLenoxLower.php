<?php require_once('../Private/DataProcessing/dataAssembly.php');
	 $thisFile = url_for('DeviceNetworkLenoxLower.php'); 
?>
<!doctype html>
<head>
	<title>Lenox Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/Styling/LenoxStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src='../Private/JavaScript/generalFunction.js'></script>
	<script id='setView' src='../Private/JavaScript/portOrientation.js'> </script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Lenox Library Device Network</h1>
		<h2 id='floor'>Lower Level</h2>
		<span>
			<button  class='norm' type='button' id='changeLibrary' data-url='<?php echo url_for("DNMapMain.php");?>'>Andersen Maps</button>
			<a href='<?php echo url_for("DNMapLenoxUpper.php");?>'>Upper Level</a>
			
		</span>
	</header>

	<main>
		
			<div id="queries">
				<input id="userInput" type='text' name="userInput" value="" placeholder="Search">
				<button id="reset">Clear</button>
				</br>
				<label for="includeWithdrawn">Include withdrawn devices in results:</label>
				<input id="includeWithdrawn" type="checkbox">
			</div>
		<div id="container">	

			<div id='image-wrapper' data-captions-port='<?php echo($lenoxLJsonP) ; ?>' data-captions-device='<?php echo($lenoxLJsonD) ; ?>'>
				<img id='blueprint'class="map" src="../Private/Styling/LenoxBlueprintLower-100.jpg" alt="A map of the Lenox Library's lower floor, with markers indicating the locations of data ports" max-width="100%" height="auto" />
			</div>
		</div>	
		<?php 
				require_once('graveyard.php');
				
			?>
			
		
	</main>
	
</body>