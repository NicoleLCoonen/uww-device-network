<?php
	if(is_post_request()){
		//print_r($_POST);
		// setting all variables from $_POST at the top for later use
		$result = "";
		$postKeys = array_keys($_POST);	
		
		$portID = $_POST['portID'];
		$portName = trim($_POST['portName']);
		$status = $_POST['status'];
		$deviceID = $_POST['deviceID'];
		$deviceName = trim($_POST['deviceName']);
		$model = trim($_POST['model']);
		$nonCap = trim($_POST['nonCap']);
		
		$tables = array("computers", "staff_computers", "printers_and_scanners", "accessories");
		
		if(isset($_POST["phpDelete"])){
			$delete = 1;
		} else {
			$delete = 0 ;
		};
				
		if(isset($_POST['broken'])){
			$broken = 1;
		} else {
			$broken = 0 ;
		};
		
		// Begin data processing
		// Start by updating port info.
		
		// If the form was submitted with a port ID, then the port name will also be set.
		if($portID !== ''){
			$sql = "UPDATE data_ports";
			$sql .= " SET Port_Name='" . $portName . "'";
			$sql .= ", Port_Status=" . $status;
			$sql .= ", Damaged=" . $broken;
			$sql .= " WHERE ID=" . $portID;
					
			if(mysqli_query($db, $sql)) {
				$result .= "Port information successfully updated. " ;
			} else {
				$result .= "Port information could not be updated. " . $sql ;
				$result .= mysqli_error($db) ;	
			};
				
		/* If the form was submitted with a port name but no ID#, find the port based on its name and grab the ID# that way.
		   The only way this happens is if the original port info is cleared with the "move" button.
		   Allows moving devices between floors by typing (or copy+pasting) the port name in the required input field.
		   Still check that the port name isn't empty so the code doesn't break. If no name is provided, nothing will happen.*/
		} else if($portName !== '' && $portID === ''){
			$sql = "SELECT * FROM data_ports WHERE Port_Name='" . $portName . "'";
					
			$portSearch = mysqli_query($db, $sql);
						
			if(!$portSearch){
				exit("We couldn't find that port. Please make sure you've entered the name correctly.");
			};
						
			while($port = mysqli_fetch_assoc($portSearch)){
				$portID = $port['ID'];
			};
						
			unset($sql);	
		};

		// Move onto device info, if any was submitted. 
		/* If a device ID was submitted, device connecion info is being updated, not added.
		Device name, model and noncap info is not editable,  */
		if($deviceID !== '') {
			// Check for an indicators of the type of device. 
			// First check for 3rd-Party info
			if($_POST['vendor'] !== '' && $_POST['vendorName'] !== ''){
				$vendor = $_POST['vendor'];
				$vendorName = $_POST['vendorName'];
						
				$sql = "UPDATE printers_and_scanners";
				$sql .= " SET Vendor='" . $vendor . "'";
				$sql .= ", Vendor_Name='" . $vendorName . "', ";
				$sql .= " Device_Name='" . $deviceName . "', ";
					
			// Then check whether it's office equipment based on the suffix (-O for "Office"  vs. -L for "Lab")		
			} else if(strpos($deviceName, "-O")) {
				$sql = "UPDATE staff_computers SET";
				$sql .= " Device_Name='" . $deviceName . "', ";
			} else {
				$sql = "UPDATE computers SET";
				$sql .= " Computer_Name='" . $deviceName . "', ";
			};
						
			$sql .= " Connection=" . $portID ;
			$sql .= " WHERE ID=" . $deviceID;
		
		// if the device ID was empty, a new device may be being added 	
		} else if($deviceID === '' && $deviceName !== ''){
				// Run the same checks and build sql accordingly	
				if($_POST['vendor'] !== '' && $_POST['vendorName'] !== ''){
					$vendor = $_POST['vendor'];
					$vendorName = $_POST['vendorName'];
						
					$sql = "INSERT INTO printers_and_scanners " ;				
					$sql .= " SET Vendor='" . $vendor . "'";
					$sql .= ", Vendor_Name='" . $vendorName ."'";
					$sql .= ", Device_Name='" . h($deviceName);
					
				} else if(strpos($deviceName, "-O")) {
					$sql = "INSERT INTO staff_computers SET";
			
				} else {
					$sql = "INSERT INTO computers" ;
					$sql .= " SET Computer_Name='" . h($deviceName) . "'";
				};
						
					
				$sql .= ", Model='" . $model . "'";
				$sql .= ", Connection=" . $portID ;
				$sql .=  ", Noncap='"  .$nonCap . "'" ;	
				
		};
		
		if(mysqli_query($db, $sql)) {
				$result .= "Device information successfully updated. " ;
			} else {
				$result .= "Device information could not be updated. " . $sql ;
				$result .= mysqli_error($db) ;	
			};
		
		foreach($postKeys as $key){
			if(preg_match("/accessory\d\z/", $key)){
				$accessoryID = substr($key, 9);
				$sql .= " UPDATE accessories SET Device_Connection=0 WHERE ID=" . $accessoryID ;
				/*if(mysqli_query($db, $sql)) {
					$result .= "Accessory information successfully updated." ;
				} else {
					$result .= "Accessory information could not be updated. " . $sql ;
					$result .= mysqli_error($db);	
				};*/
			};
		};	
	
		
		if($delete == 1){
			foreach($tables as $t){
				if(strpos($sql, $t)){
					$table = $t ;
				};
			};	
		unset($sql);
			
			$sql = " SELECT * FROM " . $table . " WHERE ID=" . $deviceID;
			$result_set = mysqli_query($db, $sql);
			
			confirm_result_set($result_set);
			unset($sql);
			
			$sql = "INSERT INTO graveyard SET ";
			
			while($result = mysqli_fetch_assoc($result_set)){
				
				foreach($result as $key => $data){
					if($key === "ID" || $key === "Connection"){
						continue;
					}else if(strpos($key, "_Name") !== false){
						$sql .= "Device_Name='" . $data . "', ";
					}else {
						$sql .= $key . "='" . $data . "', ";
					};
				};
			};
							
			$format1 = strpos($_POST['dateRemoved'], '-');
			$format2 = strpos($_POST['dateRemoved'], '/');
						
			if($format1 !== false){
				$date = explode("-", $_POST['dateRemoved']);
				$format3 = strlen($date[0]);
						
				if($format3 === 2){
					$month = $date[0] ;
					$day = $date[1] ;
					$year = $date[2];
				}else if($format3 === 4){
					$year = $date[0] ;
					$month = $date[1] ;
					$day = $date[2];
				};
								
			} else if($format2 !== false){
				$date = explode("/", $_POST['dateRemoved']);
				print_r($date);
				$format3 = strlen($date[0]);
				
				if($format3 === 2){
					$month = $date[0] ;
					$day = $date[1] ;
					$year = $date[2];
				}else if($format3 === 4){
					$year = $date[0] ;
					$month = $date[1] ;
					$day = $date[2];
				};
			};
			$sql .= " Date_Removed='" . $year . '-' . $month . '-' . $day . "'" ;	
			$sql .= ", Sent_To='" . $_POST['sentTo'] . "'" ;
			$sql .= ", Notes='" . $_POST['notes'] . "'" ;
			$sql .= ", Origin_Table='" . $table . "'" ;
			$sql .= ", Recallable=" ;
			if(isset($_POST['Recallable'])){
				$sql .= $_POST['Recallable'] ;
			}else{
				$sql .= '0';
			};
			echo $sql . "</br>";
		
			
		if(mysqli_query($db, $sql)) {
			unset($sql);
			$sql = "DELETE FROM " . $table . " WHERE ID=" . $deviceID ;
			$sql .= " AND Noncap='" . $nonCap . "'"; 
			echo $sql . "</br>";
			
			if(mysqli_query($db, $sql)){
				$result .= "Device information successfully updated." ;
			};
			
		} else {
			$result .= "Device could not be removed. " . $sql ;
			$result .= mysqli_error($db);	
		};			
			
			
	};
	
		if(strpos($result, "not") === false){
			echo  '<meta http-equiv="refresh" content="0.25">';
		}else {
			echo "<script> window.alert('" . $result . "')</script>";
		};
											
	};
			
?>