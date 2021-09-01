<?php require_once('..\Private\DataProcessing\placeholder.php');

?>

<!doctype html>
<head>
	<title>Andersen Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/DNPStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/DNP.js'> </script>
</head>


<body>
	<header class = 'masthead'> 
		<h1>Andersen Library Device Network</h1>
		<h2 id='floor'>Main Floor</h2>
		<span>
		<button type='button' class='norm' id='changeLibrary' data-url='<?php echo url_for("DNMapLenoxUpper.php");?>'>Lenox Maps</button>
			<select id='floorSelect' name="floorSelect">
				<option value="" disabled selected hidden>Change Floor</option>
				<option value="<?php echo url_for("DNMapThird.php") ;?>">Third Floor</option>
				<option value="<?php echo url_for("DNMapFirst.php") ;?>">First Floor</option>
			</select>
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
	<?php 
				require_once('graveyard.php');
				
			?>
			
			<div id='image-wrapper' data-captions='<?php echo($mainJson) ; ?>'>
				<img id='MainFloorBlueprint'class="map" src="../Private/MainBlueprintDNP-100.jpg" alt="A map of the Andersen Library's main floor, with markers indicating the locations of Data Ports" max-width="100%" height="auto" />
				<caption for='MainFloorBlueprint'>For visual clarity, some ports have been grouped together or adjusted slightly from their physical location.</br>
				Please consider marker loacations to be approximate.</caption>
			</div>
		</div>	
			
	</main>
	
</body>