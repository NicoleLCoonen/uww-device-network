<?php require_once("../Private/Connect/initialize.php") ;?>

<!doctype html>
<html>
<head>
	<title>Andersen Device Network</title>
	<meta  name="viewport" content="width=device-width, initial-scale=1.0" >
	<meta charset="utf-8" lang ="en-us">
	<link rel='stylesheet' href='../Private/reportStyling.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src='../Private/reports.js'> </script>
</head>
</head>


<body>
	<header> 
		<h1>Andersen Library Device Network</h1>
		<h2>Report Center</h2>
		<span>
			<select  name="floorSelect">
				<option value="" disabled selected hidden>Back to Maps</option>
				<option value="<?php echo url_for("DNPtesting.php") ;?>">Main Floor</option>
				<option value="<?php echo url_for("DNPthirdFloor.php") ;?>">Third Floor</option>
				<option value="<?php echo url_for("DNPfirstFloor.php") ;?>">First Floor</option>
			</select>
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
						<p>Provides records of devices withdrawn from the Libraries. Includes information about where the device was sent after withdrawl and the reason(s) it was pulled.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="staff" >Staff Computers</button>
						<p>Generates a list of staff computers and equipment, including docks and monitors.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="models">Models</button>
						<p>A breakdown of Library devices by Model. Public and Staff computers only.</p>
					</div>
					
					<div class='reportType'>
						<button type='submit' name="data-report" value="thirdParty">Third-Party Devices</button>
						<p>Generates a list of all Third-Party devices in the Libraries. This may include devices that we have a maintenance contract on as well as devices not provided through iCIT.</p>
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
		
	<?php require("../Private/DataProcessing/reportProcessing.php") ; ?>

			<div id='display' data-report-type="<?php if(isset($reportType) && $reportType !== ""){echo($reportType);};?>" hidden>
				
				
				<div id="general" hidden>
					<p>This is where the general report will go</p>
				</div>
			
				<div id="withdrawn" hidden>
					<div class='heading'>
						<h2>Library Public and Staff Computers</h2>
						<h3>Withdrawal Records</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					
					<div class='overview'>	
						<table >
							<th>Name</th>
							<th>Noncap</th>
							<th>Model</th>
							<th>Vendor</th>
							<th>Vendor ID</th>
							<th>Date Removed</th>
							<th>Destination</th>
							<th>Notes</th>
							<?php if(isset($result_set) && $reportType === "withdrawn"){
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
				</div>
				
				<div id="models" hidden>
					<div class='heading'>
						<h2>Library Public and Staff Computers</h2>
						<h3>Model Report</h3>
						<p><?php echo date("m/d/y")?></p>
					</div>
					
					<?php if(isset($result) && $reportType === "models" ){
							
							$display = "<div class='overview'><h3>Total: </h3>";
							
							foreach($result as $model => $number){
								 $display .= ("<div class='generic'><h5>" . $model . ":</h5><p>". $number . "</p></div>");
							};
							
							$display .= "</div>";
							
							if(isset($floors)){
								$display .= "<div class='breakdown'>";
									foreach($floors as $title => $content){
										$class = "";
										$substr = explode(" ",$title);
										
										foreach($substr as $str){
											$str = trim($str);
											$class .= $str; 
										};
									
										$display .= "<div class='floor " . $class . "'>";
										$display .= "<h3>" . $title . ":</h3>";
											
											foreach($content as $mdl => $no){
												$display .= "<div class='generic'><h5>" . $mdl . ":</h5><p>". $no . "</p></div>";
											};
											
										$display .= "</div>";
									};
								
								$display .= "</div>";
							};
							
							echo($display);
					};
					
					
					?>
					
				</div>
				
				<div id="thirdParty" hidden>
					<p>This is where the Third Party report will go</p>
				</div>
				
				<div id="available" hidden>
					<p>This is where the Available Ports report will go</p>
				</div>
				
				<div id="broken" hidden>
					<p>This is where the Broken Ports report will go</p>
				</div>
				
			</div>
	
		</div>
	
	</main>
	
	
</body>

</html>