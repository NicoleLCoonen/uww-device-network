<?php require_once('queryFunctions.php') ; 

/* This file assembles data in two ways: Port-Oriented and Device-Oriented
 The firs chunk of code is the Port-Oriented data-assembly
 I wrote this first, because I like specificity, however the visuals can be cluttered.
*/

// ** PORT-ORIENTED ASSEMBLY **

	$jsonfile = "data.json"	;
	$group_no = array();
	
	// Create an array of Port_Group #s.
	$sql = 'SELECT DISTINCT ID FROM port_groups';
	$result_set = mysqli_query($db, $sql);
	
	while($result = mysqli_fetch_assoc($result_set)){
		array_push($group_no, $result['ID']);
	};
	
	unset($sql);
	unset($result_set);
	unset($result);
	$firstFloor = array();
	$mainFloor = array();
	$thirdFloor = array();
	$lenoxUpper = array();
	$lenoxLower = array();
	
	// This is for generating a csv of Lenox data for quality checking.
	//$lenoxCheck = array();
	//$group_objects = array();
	
	$port_groups = array() ;
	$port_array = array() ;
	
	// Gets info on each port in a Port_Group and stores it as an associative array.
	// The array of ports is then stored in another associative array like this:
	
	// [Group #], [location code],[y coords,], [ports] => (port 1)
	//																=>	[Name], [Status], [Device]
																							//	=> [Name], [Model], [Etc]
												//	   => (port 2)
														//			=>	[Name], [Status], [Etc]	
												//	   => (port 3)
														//			=>	[Name], [Status], [Etc]	
	foreach($group_no as $value) {
		
		$sql = 'SELECT * FROM port_groups WHERE ID=' . $value ;
		$result_set = mysqli_query($db, $sql) ;	
		confirm_result_set($result_set) ;
		
		unset($sql) ;
		
		while($current_group = mysqli_fetch_assoc($result_set)){
		
		$location = $current_group['Location'];	
		$group = array();
		
		$sql = 'SELECT * FROM data_ports WHERE Port_Group=' . $value ;
		
		$result_set = mysqli_query($db, $sql) ;
		
		$y = mysqli_num_rows($result_set) ;
		
		if($y === 0){continue;};
		
		$b = 0 ; 
		$c = 0;

		while($result = mysqli_fetch_assoc($result_set)) {
			unset($sql) ;
			$b++ ;
			
			$result['Port_Status'] = get_status($result['Port_Status']);
			$result = trim_port($result);
			
			// If a device is connected to the port, finds it and tacks it onto the end of the port
			$sql = "SELECT * FROM computers WHERE Connection=" . $result['ID'] ;
			
			$device_result = mysqli_query($db, $sql) ;
			
			$rows = mysqli_num_rows($device_result);
			
			if($rows > 0){
				$device = mysqli_fetch_assoc($device_result) ;
				
				if($device !== null){
					$device = trim_flex($device) ;
					$result['Device'] = $device ;
					$c++ ;
				};
				
			} else if($rows == 0) {
				unset($sql) ;
				unset($device_result) ;
				$sql = "SELECT * FROM printers_and_scanners WHERE Connection=" . $result['ID'] ;
				$device_result = mysqli_query($db, $sql) ;
				$rows .= mysqli_num_rows($device_result);
				
				if($rows > 0){
					if($device_result !== null){
						$device = mysqli_fetch_assoc($device_result) ;
						if($device !== null){
							trim_flex($device) ;
							$result['Device'] = $device ;
							$c++ ;
						};
					};
				} else if($rows == 0){
					unset($sql) ;
					unset($device_result) ;
					$sql = "SELECT * FROM staff_computers WHERE Connection=" . $result['ID'] ;
					$device_result = mysqli_query($db, $sql);
					
					if($device_result !== null){
						$device = mysqli_fetch_assoc($device_result) ;
						if($device !== null){
							$d['ID'] = $device['ID'];
							$d['Computer_Name'] = $device['Computer_Name'];
							$d['Noncap'] = $device['Noncap'];
							$d['Model'] = $device['Model'];
						
							$sql = "SELECT * FROM accessories WHERE Device_Connection=" . $device['ID'];
							$aResult = mysqli_query($db, $sql);
							if($aResult !== null){
								$x = mysqli_num_rows($aResult);
								if($x > 0){
									while($accessory = mysqli_fetch_assoc($aResult)){
										$a['ID'] = $accessory['ID'];
										$a['Name'] = $accessory['Model'] . " " . $accessory['Device_Type'];
										$periphery[] = $a;
									};
									$d['Accessories'] = $periphery;
								};
							};
							unset($aResult);
							unset($periphery);
							$result['Device'] = $d ;
							$c++ ;
							unset($d);
						};
					};
				};
			};
			
			//Lenox Quality Assurance code
			/*if($location >= 24){
				var_dump($location);
				$portCheck["Port Name"] = $result["Port_Name"];
				if(isset($result["Device"])){
					$deviceCheck = $result["Device"];
					$portCheck["Computer Name"] = $deviceCheck["Computer_Name"];
					$portCheck["Model"] = $deviceCheck["Model"];
					$portCheck["Noncap"] = $deviceCheck["Noncap"];
				}else{
					$portCheck["Computer Name"] = '';
					$portCheck["Model"] = '';
					$portCheck["Noncap"] = '';
				};
				$lenoxCheck[] = $portCheck;
			};*/
			
			$group["Port" . $b]  = $result;	
			
		};
		$text = array();
		$text['ports'] = $y ;
		$text['devices'] = $c ;
		$current_group['text'] = $text ;
		$current_group["Ports"] = $group ;
		
		// sorts port groups into their respective floors based on the location code.
		if(!$location){
			continue;
		}else if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
			array_push($firstFloor, $current_group);
		}else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
			array_push($thirdFloor, $current_group);
		}else if($location == 25){
			array_push($lenoxLower, $current_group);
		}else if($location == 24 || $location > 25){
			array_push($lenoxUpper, $current_group);
		}else{
			array_push($mainFloor, $current_group);
		};
		
		
		//array_push($group_objects, $current_group);
	
	};
};

	$firstJsonP = '{"coords" : ' . json_encode($firstFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$mainJsonP = '{"coords" : ' . json_encode($mainFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$thirdJsonP = '{"coords" : ' . json_encode($thirdFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$lenoxUJsonP = '{"coords" : ' . json_encode($lenoxUpper, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$lenoxLJsonP = '{"coords" : ' . json_encode($lenoxLower, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	
	//$json = '{"coords" : ' . json_encode($group_objects, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	
	//echo($firstJsonP) . "</br>";
	//echo($mainJsonP) . "</br>";
	//echo($thirdJsonP) . "</br>";
	$jsonf = fopen($jsonfile, "w") or die ("Unable to open json file");
	$success = fwrite($jsonf, $mainJsonP);
	
	fclose($jsonf);
	
	/* More Lenox Quality Assurance code
	$fp = fopen('file.csv', 'w');
	foreach ($lenoxCheck as $fields) {
		fputcsv($fp, $fields);
	};	

	fclose($fp);*/
	
// ** DEVICE-ORIENTED ASSEMBLY **

// This orientation is meant to provide visual clarity 

/* Just like the Port-Oriented View, we need to construct a multi-dimensional array.
	   The structure of this one will look something like:
	   
			[Port_Group] => [ID]
						 => [Location]
						 => [Coords]
						 => [Orientation]
						 => [Devices] => [Device1] => [ID]
												   => [Name]
												   => [Model]
												   => [Noncap]
												   => [Flex_Order]
									  => [Device2] => etc.
	
	*/
	
	// reset variables
	unset($sql);
	unset($result_set);
	unset($result);
	
	$firstFloor = array();
	$mainFloor = array();
	$thirdFloor = array();
	$lenoxUpper = array();
	$lenoxLower = array();
	
	foreach($group_no as $value) {
		
		$sql = 'SELECT * FROM port_groups WHERE ID=' . $value ;
		
		$result_set = mysqli_query($db, $sql) ;
			
		confirm_result_set($result_set) ;
		
		unset($sql) ;
		
		
		while($current_group = mysqli_fetch_assoc($result_set)){
			$q = 0 ;
			$location = $current_group['Location'];
			$current_group['Orientation'] = set_orientation($current_group['Orientation']) ;
			$portNumber = array() ;
			$devices = array();
			
			$sql = "SELECT DISTINCT ID FROM data_ports WHERE Port_Group=" . $value ;
			$port_result = mysqli_query($db, $sql) ;
	
			while($result = mysqli_fetch_assoc($port_result)){
				array_push($portNumber, $result['ID']) ;
			} ;
			unset($sql) ;
			
			foreach($portNumber as $port){
				$sql = "SELECT * FROM computers WHERE Connection=" . $port ;
				$computerResult = mysqli_query($db, $sql) ;
				
				if($computerResult !== null){
					while($computer = mysqli_fetch_assoc($computerResult)){
						$q++ ;
						$computer = trim_connection($computer);
						$devices['Device' . $q] = $computer;
						
					} ;
					
				}else{
					$sql = "SELECT * FROM staff_computers WHERE Connection=" . $port ;
					$staffCompResult = mysqli_query($db, $sql);
					
					if($staffCompResult !== null){
						$a = 0 ;
						while($computer = mysqli_fetch_assoc($staffCompResult)){
							$q++ ;
							$computer - trim_connection($computer);
							$sql = "SELECT * FROM accessories WHERE device_connection=" . $computer['ID'];
							$accessoryCheck = mysqli_query($db,$sql);
							
							if($accessoryCheck != false){
								$accessories = array();
								while($accessory = mysqli_fetch_assoc($accessoryCheck)){
									$a++ ;
									$accessories['accessory' . $a] = $accessory ;
								} ;
								$computer['accessories'] = $accessories ;
							} ;
							
							$devices['Device' . $q] = $computer;
						} ;
					}else{
						$sql = "SELECT * FROM printers_and_scanners WHERE Connection=" . $port ;
						$printerResult = mysqli_query($db, $sql) ;
						
						if($printerResult !== null){
							while($printer = mysqli_fetch_assoc($printerResult)){
								$q++ ;
								$devices['Device' . $q] = $printer;
								
							} ; 
						} ;
					} ;
				} ;
				if(count($devices) > 0){
					$current_group['Devices'] = $devices;
				};
				
			} ;
			$current_group['text'] = $q;
			// sorts port groups into their respective floors based on the location code.
			if(!$location){
				continue;
			}else if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
				array_push($firstFloor, $current_group);
			}else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
				array_push($thirdFloor, $current_group);
			}else if($location == 25){
				array_push($lenoxLower, $current_group);
			}else if($location == 24 || $location > 25){
				array_push($lenoxUpper, $current_group);
			}else{
				array_push($mainFloor, $current_group);
			};
		};
	};
	//print_r($mainFloor);
	$firstJsonD = '{"coords" : ' . json_encode($firstFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$mainJsonD = '{"coords" : ' . json_encode($mainFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$thirdJsonD = '{"coords" : ' . json_encode($thirdFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$lenoxUJsonD = '{"coords" : ' . json_encode($lenoxUpper, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$lenoxLJsonD = '{"coords" : ' . json_encode($lenoxLower, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	
// To allow users to include withdrawn devices in their search results, the entire graveyard table is included here and picked up by graveyard.php
	unset($sql);
	unset($result_set);
	 
	$sql = "SELECT * FROM  graveyard";
	$result_set = mysqli_query($db, $sql);
	
// To change the view (and have it stick for the user) we're using a cookie to pass info around.
// The default view is device-oriented right now.
// The cookie is set to expire every 3 months (186400 * 90) if the user doesn't change between views regularly.
setcookie("view", "device", time() + (186400 * 90),"/");

// To ensure we include the correct .js file, we'll match the value of the $view cookie to a key with the right value
$views["device"] = 	"../Private/JavaScript/deviceView.js" ;
$views["port"] = "../Private/JavaScript/portViewEdit.js";	

// We'll do something similar with the button text.
$buttonText["device"] = "Switch to port view." ;
$buttonText["port"] = "Switch to device view." ;

// And for the .css files
$style["device"] = "../Private/Styling/deviceViewStyling.css" ;
$style["port"] = "../Private/Styling/portViewStyling.css";
?>
