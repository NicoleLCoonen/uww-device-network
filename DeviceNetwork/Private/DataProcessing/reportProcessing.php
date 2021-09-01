<?php
require_once('queryFunctions.php');

$compTotal = 0;
	if(is_post_request() && isset($_POST["data-report"])){

		$reportType = $_POST["data-report"];
		
		if($reportType === "general"){
			
			$result = count_ports_by_status();
			$modelArr = get_models();
			$models = count_models($modelArr);
			$vendorArr = get_vendors();
				 
			foreach($vendorArr as $vendor){
				 $sql = "SELECT * FROM printers_and_scanners WHERE Vendor='" . db_escape($db, $vendor) . "'";
				 $deviceResult = mysqli_query($db, $sql);
				 confirm_result_set($deviceResult);
				 $vendors[$vendor] = mysqli_num_rows($deviceResult);
			};
			
			$results[] = $result; 
			$results[] = $models;
			$results[] = $vendors;
			
			
		}else if($reportType === "withdrawn"){
			
			$sql = "SELECT * FROM graveyard";
			
			$result_set = mysqli_query($db, $sql);
			
			unset($sql);
			$floors = array();
			$firstFloor = array();
			$mainFloor = array();
			$thirdFloor = array();
			$lenoxUpper = array();
			$lenoxLower = array();
			$sql = "SELECT * FROM data_ports WHERE Port_Status=1";
			
			$resultSet = mysqli_query($db, $sql);
			$locationArr = array(); 
			
			
			while($port = mysqli_fetch_assoc($resultSet)){
			
				//Separates available ports from those in use.
				//Looks for a computer connection first, then staff/office equipment, then printer/scanner connections
				$sql = "SELECT * FROM computers WHERE Connection=" . $port['ID'];

				$deviceResult = mysqli_query($db, $sql);
				$dr = mysqli_fetch_row($deviceResult);
				if($dr === null){
					$staffSql = "SELECT * FROM staff_computers WHERE Connection=" . $port['ID'];
					
					$staffDevices = mysqli_query($db, $staffSql);
					$sr = mysqli_fetch_row($staffDevices);
					if($sr === null){
						$auxSql = 'SELECT * FROM printers_and_scanners WHERE Connection=' . $port['ID'];
						
						$auxResult = mysqli_query($db, $auxSql);
						$ar = mysqli_fetch_row($auxResult);
						if($ar === null){
							$location = locate_port($port);
							array_push($locationArr, $location);	
							$port = array_slice($port, 0, 2,true);
							
							if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
								array_push($firstFloor, $port);
							}else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
								array_push($thirdFloor, $port);
							}else if($location == 25){
								array_push($lenoxLower, $port);
							}else if($location == 24 || $location > 25){
								array_push($lenoxUpper, $port);
							}else{
								array_push($mainFloor, $port);
							};
						};
					};
				};
			};
			
			unset($sql);
			
			$sql = 'SELECT * FROM library_staff ORDER BY Last_Name, First_Name ASC';
			
			$staffSet = mysqli_query($db, $sql);
			$staffPerson = array();
			$staff = array();
			while($staffResult = mysqli_fetch_assoc($staffSet)){
				$staffPerson['ID'] = $staffResult['ID'];
				$staffPerson['Name'] = full_name($staffResult);
				array_push($staff, $staffPerson);
			};
			
		}else if($reportType === "models"){
			$floors = array();
			$first = array();
			$main = array();
			$third = array();
			
			$modelArr = get_models();
			
			foreach($modelArr as $model){
				$sql = "SELECT * FROM computers WHERE Model='" . $model . "'";
				$resultSet = mysqli_query($db, $sql);
				$result[$model] = mysqli_num_rows($resultSet);
				$locationArr = array();
				// f = first floor count; m = main floor count; t = third floor count;
				$f = 0;
				$m = 0;
				$t = 0;	
				
				while($device = mysqli_fetch_assoc($resultSet)){
					$location = locate_device($device);
					array_push($locationArr, $location);	
							
				};
				$count = count_by_floor($locationArr, $f, $m, $t);
				$first[$model] = $count[0]; //corresponds to $f
				$main[$model] = $count[1]; //corresponds to $m
				$third[$model] = $count[2]; //corresponds to $t
				
			};
			
			$floors["First Floor"] = $first;
			$floors["Main Floor"] = $main;
			$floors["Third Floor"] = $third;
			
			
		}else if($reportType === "thirdParty"){
			
			$vendorArr = get_vendors();
				 
				 foreach($vendorArr as $vendor){
					 $sql = "SELECT * FROM printers_and_scanners WHERE Vendor='" . db_escape($db, $vendor) . "'";
					 $deviceResult = mysqli_query($db, $sql);
					 confirm_result_set($deviceResult);
					 $result[$vendor] = mysqli_num_rows($deviceResult);
					 $locationArr = array(); 
					 // f = first floor count; m = main floor count; t = third floor count;
					 $f = 0;
					 $m = 0;
					 $t = 0;	
					
					while($device = mysqli_fetch_assoc($deviceResult)){
						$location = locate_device($device);
						array_push($locationArr, $location);		
					};
					$count = count_by_floor($locationArr, $f, $m, $t);
					$first[$vendor] = $count[0]; //corresponds to $f
					$main[$vendor] = $count[1]; //corresponds to $m
					$third[$vendor] = $count[2]; //corresponds to $t
				};
			
				$floors["First Floor"] = $first;
				$floors["Main Floor"] = $main;
				$floors["Third Floor"] = $third;
			
				
		}else if($reportType === "available"){
			$floors = array();
			$firstFloor = array();
			$mainFloor = array();
			$thirdFloor = array();
			$sql = "SELECT * FROM data_ports WHERE Port_Status=1";
			
			$resultSet = mysqli_query($db, $sql);
			$locationArr = array(); 
			
			
			while($port = mysqli_fetch_assoc($resultSet)){
			
				//Separates available ports from those in use.
				//Looks for a computer connection first, then printer/scanner connections
				$sql = "SELECT * FROM computers WHERE Connection=" . $port['ID'];

				$deviceResult = mysqli_query($db, $sql);
				$dr = mysqli_fetch_row($deviceResult);
				if($dr === null){
					$auxSql = 'SELECT * FROM printers_and_scanners WHERE Connection=' . $port['ID'];
					
					$auxResult = mysqli_query($db, $auxSql);
					$ar = mysqli_fetch_row($auxResult);
					if($ar === null){
						$location = locate_port($port);
						array_push($locationArr, $location);	
									
						if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
							array_push($firstFloor, $port);
						} else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
							array_push($thirdFloor, $port);
						} else{
							array_push($mainFloor, $port);
						};
						
					};
				};
			};
			// f = first floor count; m = main floor count; t = third floor count;
			$f = 0;
			$m = 0;
			$t = 0;	
			$count = count_by_floor($locationArr, $f, $m, $t);
			$result["Total"] = $count[0] + $count[1] + $count[2];
			$result["First Floor"] = $count[0]; //corresponds to $f;
			$result["Main Floor"] = $count[1]; //corresponds to $m
			$result["Third Floor"] = $count[2]; //corresponds to $t 
			
			$floors["First Floor"] = $firstFloor;
			$floors["Main Floor"] = $mainFloor;
			$floors["Third Floor"] = $thirdFloor;
			
		}else if($reportType === "broken"){
			$floors = array();
			$firstFloor = array();
			$mainFloor = array();
			$thirdFloor = array();
			$sql = "SELECT * FROM data_ports WHERE Port_Status=0 AND Damaged=1";
			$resultSet = mysqli_query($db, $sql);
			
			// f = first floor count; m = main floor count; t = third floor count;
			$f = 0;
			$m = 0;
			$t = 0;	
			
			while($port = mysqli_fetch_assoc($resultSet)){
				$location = locate_port($port);
						
				if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
					$f++;
					array_push($firstFloor, $port);
				} else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
					$t++;
					array_push($thirdFloor, $port);
				} else{
					$m++;
					array_push($mainFloor, $port);
				};		
				
			};
			
			$result["Total"] = $f + $m + $t;
			$result["First Floor"] = $f;
			$result["Main Floor"] = $m;
			$result["Third Floor"] = $t; 
			
			$floors["First Floor"] = $firstFloor;
			$floors["Main Floor"] = $mainFloor;
			$floors["Third Floor"] = $thirdFloor;
		};
			
			
	};	
	
?>