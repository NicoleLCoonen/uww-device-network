<?php require_once('..\Private\DataProcessing\placeholder.php');

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
		<span>
			<h2 id='floor'>Third Floor</h2>
			<select id='floorSelect'>
				<option value='' disabled selected hidden>Change Floor</option>
				<option value="<?php echo url_for("DeviceNetworkMain.php");?>">Main Floor</option>
				<option value="<?php echo url_for("DeviceNetworkFirst.php");?>">First Floor</option>
			</select>
			<button type="button" id="reports" data-url='<?php echo url_for("reports.php")?>'>Run Reports</button>
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
		
		<div id="updateForms" class="sidebar">
		<button type="button" id="closeForm">X</button>
			<form id="updateDB" method="post" action=" <?php echo url_for("DeviceNetworkThird.php");?>">
				<h4>Port:</h4>
				<input type="number" id="portID" name="portID" ></input>
				<label for="portName">Name:</label>
				<input type="text" id="portName" name="portName"></input>
				</br>
				<label for="status">Port Status:</label>
				</br>
				<label for="On">On</label>
				<input type="radio" id="On" name="status" value="1" checked="false"></input>
				</br>
				<label for="Off">Off</label>
				<input type="radio" id="Off" name="status" value="0" checked="false"></input>
				</br>
				<label for="broken">Broken:</label>
				<input type="checkbox" id="broken" name="broken" ></input>
				</br>
				<div class="error" id="connectionError">
					<p>Do not connect devices to ports that are turned off or broken.</br> 
					Please double-check the status or move the device.</p>
				</div>
				<div class="error" id="portInUse">
					<p>This port is being used by another device. Please select an available port or cancel this operation and move the device.</p>
				</div>

				<h4>Device:</h4>
				<input type="number" id="deviceID" name="deviceID"></input>
				<label for="deviceName">Name:</label>
				<input type="text" id="deviceName" name="deviceName" readonly></input>
				</br>
				<label for="model">Model:</label>
				<input type="text" id="model" name="model" readonly></input>
				</br>
				<label for="nonCap">NonCap:</label>
				<input type="text" id="nonCap" name="nonCap" readonly></input>
				</br>
				</br>
				<h5>Additional Info</h5>
				<caption>These fields only apply to printers and scanners.</caption>
				</br>
				<label for="vendor">Vendor:</label>
				<input type="text" id="vendor" name="vendor" readonly></input>
				</br>
				<label for="vendorName">Vendor Identifier:</label>
				<input type="text" id="vendorName" name="vendorName" ></input>
				</br>
				<caption for="vendorName"><small>This is how the vendor will refer to the device.</small></caption>
				</br>
				<div  id="morgue" >
					<span>
						<label for="dateRemoved">When was this device removed?</label>
						<input type="date" id="dateRemoved" name="dateRemoved" placeholder="MM/DD/YYYY"></input>
						</br>
						<label for="sentTo">Where did we send it?</label>
						<select id="sentTo" name="sentTo">
							<option value="iCIT">iCIT</option>
							<option value="Surplus">Surplus</option>
							<option value="Other">Other(Please Specify)</option>
						</select>
					</span>
					</br>
					<label for="notes">Notes:</label>
					</br>
					<textarea id="notes" name="notes" placeholder="Include other relavant info here.">
					</textarea>
				</div>
				<div class="buttons">
					<button type="submit" id="updateButton">Update</button>
					<button type="button" id="move">Move</button>
					<button type="button" id="delete">Remove</button>
					<button type="button" id="new">New Device</button>
					<input type="checkbox" id="phpDelete" name="phpDelete" checked="false"></input>
				</div>
				
				<div id="instructions">
					
				</div>
				
			</form>
		</div>
	
		<div id='image-wrapper' data-captions='<?php echo($thirdJson) ; ?>'>
			<img class="map" src="../Private/ThirdBlueprintDNP-100.jpg" alt="A map of the Andersen Library's third floor, with markers indicating the locations of Data Ports" max-width="100%" height="auto"/>
		</div>
		
	</div>
		<?php  
			require("..\Private\DataProcessing\updateDB.php"); 
		?>
	</main>
	
</body>
