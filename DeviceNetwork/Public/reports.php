<?php require_once("../Private/Connect/initialize.php") ;
	if(!isset($_POST['data-report'])){$reportType = '';};
?>

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
				<option value="<?php echo url_for("DeviceNetworkMain.php") ;?>">Main Floor</option>
				<option value="<?php echo url_for("DeviceNetworkThird.php") ;?>">Third Floor</option>
				<option value="<?php echo url_for("DeviceNetworkFirst.php") ;?>">First Floor</option>
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
		
	<?php require("../Private/DataProcessing/reportProcessing.php") ; 
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
							//print_r($results);
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
						<table >
							<th>Name</th>
							<th>Noncap</th>
							<th>Model</th>
							<th>Vendor</th>
							<th>Vendor ID</th>
							<th>Date Removed</th>
							<th>Destination</th>
							<th>Notes</th>
							<?php 
								// this section was copied in from another project. streamline w/ the create_table_ functions later
								if(isset($result_set) && $reportType === "withdrawn"){
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
	
	
</body>

</html>
