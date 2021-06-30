<?php
	if(is_post_request()){
		$result = "";
				
		$portID = $_POST['portID'];
		$portName = $_POST['portName'];
		$status = $_POST['status'];
		$deviceName = $_POST['deviceName'];
		$model = $_POST['model'];
		$nonCap = $_POST['nonCap'];
			
		// If the form was submitted with a name but no ID#, find the port based on its name and grab the ID# that way.
		// The main way this happens is if the port info is cleared with the "move" button.
		// Allows moving devices between floors by typing (or copy+pasting) the port name in the required input field.
		if($portName !== '' && !$portID){
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
				
		if(isset($_POST["phpDelete"])){
			$delete = 1;
		} else {
			$delete = 0 ;
		};
				
		if(isset($_POST['broken'])){
			$broken = $_POST['broken'];
		} else {
			$broken = 0 ;
		};
				
		if($delete == 0 && (isset($portID) && isset($portName))){
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
					
			if($_POST['deviceID'] !== '') {
				$deviceID = $_POST['deviceID'];
				
				if($_POST['vendor'] !== '' && $_POST['vendorName'] !== ''){
					$vendor = $_POST['vendor'];
					$vendorName = $_POST['vendorName'];
							
					$sql = "UPDATE printers_and_scanners";
					$sql .= " SET Vendor='" . $vendor . "'";
					$sql .= ", Vendor_Name='" . $vendorName . "', ";
					
				} else {
					$sql = "UPDATE computers SET";
				};
						
				$sql .= " Connection=" . $portID ;
				$sql .= " WHERE ID=" . $deviceID;
					
			} else {
					
				if($_POST['vendor'] !== '' && $_POST['vendorName'] !== ''){
					$vendor = $_POST['vendor'];
					$vendorName = $_POST['vendorName'];
						
					$sql = "INSERT INTO printers_and_scanners " ;				
					$sql .= " SET Vendor='" . $vendor . "'";
					$sql .= ", Vendor_Name='" . $vendorName ."'";
					$sql .= ", Device_Name='" . h($deviceName);
			
				} else {
					$sql = "INSERT INTO computers" ;
					$sql .= "SET Computer_Name='" . h($deviceName) . "'";
				};
						
					
				$sql .= ", Model='" . $model . "'";
				$sql .= ", Connection=" . $portID ;
				$sql .=  ", Noncap='"  .$nonCap . "'" ;
			};
				
				
		} else {
				$sql = "INSERT INTO graveyard" ;
				$sql .= " SET Device_Name='" . $deviceName . "'";
				$sql .= ", Model='" . $model . "'";	
				$sql .=  ", Noncap='"  . $nonCap . "'" ;
					
					if($_POST['vendor'] !== ''){
						$vendor = $_POST['vendor'];
						$vendorName = $_POST['vendorName'];
							
						$sql .= ", Vendor='" . $vendor. "'";
						$sql .= ", Vendor_Name='" . $vendorName . "'" ;
					};
							
					$format1 = strpos($_POST['dateRemoved'], '-');
					$format2 = strpos($_POST['dateRemoved'], '/');
							
					if($format1 === true){
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
								
					}else if($format2 === true){
						$date = explode("/", $_POST['dateRemoved']);
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
				$sql .= ", Date_Removed='" . $year . '-' . $month . '-' . $day . "'" ;	
				$sql .= ", Sent_To='" . $_POST['sentTo'] . "'" ;
				$sql .= ", Notes='" . $_POST['notes'] . "'" ;					
		};
					
		if(mysqli_query($db, $sql)) {
			$result .= "Device information successfully updated." ;
		} else {
			$result .= "Device information could not be updated. " . $sql ;
			$result .= mysqli_error($db);	
		};			
					
		if($delete === 1 ) {
			unset($sql);
			$deviceID = $_POST['deviceID'];
					
			if(isset($vendor)) {
				$sql = "DELETE FROM printers_and_scanners";
				$sql .= " WHERE Device_Name='" . $deviceName . "'";
			} else {
				$sql = "DELETE FROM computers";	
				$sql .= " WHERE Computer_Name='" . $deviceName . "'";
			};
						
			$sql .= " AND ID=". $deviceID ;
					
					
			if(mysqli_query($db, $sql)) {
				$result .= "Device removed from map." ;
			} else {
				$result .= "Device could not be removed." . $sql ;
				$result .= mysqli_error($db);	
			};
					
		};
											
	};
			
?>
