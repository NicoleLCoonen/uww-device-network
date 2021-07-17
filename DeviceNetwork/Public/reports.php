<?php require_once("../Private/Connect/initialize.php") ;
	if(!isset($_POST['data-report'])){$reportType = '';};
?>

<!doctype html>
<html>
<head>
	<title>Andersen Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/reportStyling.css' type='text/css'>
	<link rel='stylesheet' media='print' href='../Private/print.css' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/reports.js'> </script>
</head>



<body>
	<header class='masthead'>
		<h1>Andersen Library Device Network</h1>
		<h2>Report Center</h2>
		<span>
		<button type='button' class='norm' id='changeLibrary' data-url='<?php echo url_for("DeviceNetworkLenoxUpper.php");?>'>Lenox Maps</button>
			<select  id="floorSelect">
				<option value="" disabled selected hidden>Andersen Maps</option>
				<option value="<?php echo url_for("DeviceNetworkMain.php") ;?>">Main Floor</option>
				<option value="<?php echo url_for("DeviceNetworkThird.php") ;?>">Third Floor</option>
				<option value="<?php echo url_for("DeviceNetworkFirst.php") ;?>">First Floor</option>
			</select>
			<a href='<?php echo(url_for("FAQ.php"))?>'>FAQ</a>
		</span>
		
	</header>
	
	<main>
		<div id='container'>
			<form method="post" action="<?php echo(url_for("reports.php")); ?>" id='reportForm'>
				<div id="reports">
				
					<div class='reportType'>
						<button type='submit' name="data-report" value="general" >General</button>
						<p>Generates an overview of ports and devices in the libraries.</p>
					</div>
				
					<div class='reportType'>
						<button type='submit' name="data-report" value="withdrawn">Withdrawn</button>
						<p>Provides records of devices withdrawn from the Libraries. Includes information about where the device was sent after withdrawl and the reason(s) it was pulled. Show this report to recall or surplus devices currently in storage.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="staff" >Staff Computers</button>
						<p>Generates a list of staff and office computers (desktops and laptops only). Run this report to assign or reasign these devices. </p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="models">Models</button>
						<p>A breakdown of Library devices by Model. Public, non-circulating computers only.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="thirdParty">Third-Party Devices</button>
						<p>Generates a list of all Third-Party devices in the Libraries. This may include devices that we have a maintenance contract on as well as devices not provided through iCIT.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="otherDevices">Tablets and Peripheries</button>
						<p>Generates a list of all staff and office tablets, monitors, and other peripheries. Run this report to assign or reassign this equipment.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="available">Available Ports</button>
						<p>Generates a list of ports that are on but not in use. Includes a general location; please see map for details</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="broken" >Broken Ports</button>
						<p>Generates a list of all ports marked as being broken. May indicate visible damage or that a port has been taped over and presumed broken.</p>
					</div>
					
				</div>	
			</form>
		
	<?php require_once("../Private/DataProcessing/reportProcessing.php") ; 
			if(is_post_request() && isset($_POST["data-report"])){
	?>
			<button type='print' id='print'> &#128438; Print Report</button>
			<div id='display' data-report-type="<?php if(isset($reportType)){echo($reportType);};?>" hidden>
				
				
				<div id="general" hidden="true">
					<div class='heading'>
						<h2>Library Equipment and Network</h2>
						<h3>General Report</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					<?php if(isset($results)&& $reportType === "general"){
							$display = '';
							foreach($results as $r){
								$display .= create_overview($r);
							};
						
							if(isset($floors)){
								$display .= create_breakdown($floors);
							};
								
							echo($display);
							
						};
					?>
					
				</div>
			
				<div id="withdrawn" hidden="true">
					<div class='heading'>
						<h2>Library Public and Staff Computers</h2>
						<h3>Withdrawal Records</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					
					<div class='overview'>	
						<?php require_once('graveyard.php');  ?>
						
						<form id='recallForm' name='recall' value='' method='post' action="<?php echo(url_for("reports.php")); ?>">
						<button type="button" id="closeForm">X</button>
							<div id='deviceInfo'>
								<input id='deviceID' name='deviceID' type='number'></input>
								<label for='deviceName'>Device: </label>
								<input id='deviceName' name='deviceName' type='text' readonly></input>
								<input id='deviceNC' name='deviceNC' type='text' readonly></input>
								<input id='deviceModel' name='deviceModel' type='text' readonly></input>
							</div>
							<label for='portFloor'>Select a floor to see available ports for this device.</label></br>
							<select id='portFloor' name='portFloor'>
								<option value="" disabled selected hidden>Select Floor</option>
								<option value='<?php if(isset($portJSONfirst)){echo($portJSONfirst);}; ?>'>First Floor</option>
								<option value='<?php if(isset($portJSONfmain)){echo($portJSONfmain);}; ?>'>Main Floor</option>
								<option value='<?php if(isset($portJSONthird)){echo($portJSONthird);}; ?>'>Third Floor</option>
							</select>
							
							<div id='portSelection'>
								<p>Please select a port:</p></br>
							</div>	
							<div id='officeSelection' data-caption='<?php if(isset($staffJSON)){echo($staffJSON);}; ?>'>
								<p>This device is office equipment.</br>Please select an idividual or department to allocate the device to.</p>
							</div>
							
							<button type='submit' id='recallSubmit'>Submit</button>
						</form>
						
						<form name='edit' method='post' action="<?php echo(url_for("reports.php")); ?>">
							<input id='deviceID' name='deviceID' type='number'></input>
							<label for='deviceName'>Device: </label>
							<input id='deviceName' name='deviceName' type='text' readonly></input>
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
						</form>
						
					</div>	
				</div>
				
				<div id="models" hidden="true">
					<div class='heading'>
						<h2>Library Public and Staff Computers</h2>
						<h3>Model Report</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					
					<?php if(isset($result) && $reportType === "models" ){

							$display = create_overview($result);
								
								
							if(isset($floors)){
								$display .= create_breakdown($floors);
							};
								
							echo($display);
						};
					?>
					
				</div>
					<div id="thirdParty" hidden="true">
						<div class='heading'>
							<h2>Library Equipment</h2>
							<h3>Third-Party Vendors</h3>
							<p><?php echo date("m/d/y")?></p>
						</div>
						
						<?php if(isset($result) && $reportType === "thirdParty" ){

								$display = create_overview($result);
								
								
								if(isset($floors)){
									$display .= create_breakdown($floors);
								};
								
								echo($display);
							};

						?>
					</div>
				
				<div id="available" hidden="true">
					<div class='heading'>
						<h2>Library Data Ports</h2>
						<h3>Currently Available</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					
					<?php if(isset($result) && $reportType === "available"){
								$display = create_overview($result);
								if(isset($floors)){
									$keys = array_keys($floors);
									$k = 0;
									$display .= "<div class='breakdown'>";
									foreach($floors as $floor){
										
										$display .= "<div class='floor'><h4>" . $keys[$k] . "</h4><table>";
										
										$display .= create_table_head($floor[0]);
									
										foreach($floor as $port){
											$display .= create_table_body($port);
										};
										$display .= "</table></div>";
										$k++;
									};
									
									$display .= "</div>";
								};
								
								print_r($display);
						};
					?>
				</div>
				
				<div id="broken" hidden="true">
					<div class='heading'>
						<h2>Library Data Ports</h2>
						<h3>Broken</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					<?php if(isset($result) && $reportType === "broken"){
							$display = create_overview($result);
							if(isset($floors)){
								$keys = array_keys($floors);
								$k = 0;
								$display .= "<div class='breakdown'>";
								foreach($floors as $floor){
									
									$display .= "<div class='floor'><h4>" . $keys[$k] . "</h4><table>";
											
									$display .= create_table_head($floor[0]);
								
									foreach($floor as $port){
										$display .= create_table_body($port);
									};
									
									$display .= "</table></div>";
										$k++;
								};
								
								$display .= "</div>";
							};
									
							echo($display);
						};
						
					};
					?>
				</div>
				
			</div>
	
		</div>
			
	</main>
	
	<?php  require_once('../Private/DataProcessing/recall_or_edit.php'); ?>
</body>

</html>
