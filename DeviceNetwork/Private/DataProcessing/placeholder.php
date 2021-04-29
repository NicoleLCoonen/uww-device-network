<?php require_once('queryFunctions.php') ; 

	$jsonfile = "data.json"	;

	// Create an array of Port_Group #s. Manually check DB for highest value. currently 141
	$group_no = array() ;
	
	for($a = 1 ; $a <= 141 ; $a++) {
		
		$group_no[] = $a ;
	}
	
	$firstFloor = array();
	$mainFloor = array();
	$thirdFloor = array();
	//$group_objects = array();
	$port_groups = array() ;
	$port_array = array() ;
	
	
	// Gets info on each port in a Port_Group and stores it as an associative array.
	// The array of ports is then stored in another associative array like this:
	
	// [Group #], [location code],[y coords,], [ports] => (port 1)
	//																=>	[Name], [Status], [Etc]		
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
			$result = trim_array($result);
			
			// If a device is connected to the port, finds it and tacks it onto the end of the port
			$sql = "SELECT * FROM computers WHERE Connection=" . $result['ID'] ;
			
			$device_result = mysqli_query($db, $sql) ;
			//print_r($device_result);
			$rows = mysqli_num_rows($device_result);
			
			if($rows > 0){
				$device = mysqli_fetch_assoc($device_result) ;
				
				if($device !== null){
					$result['Device'] = $device ;
					$c++ ;
				};
				
			} else if($rows === 0) {
				unset($sql) ;
				unset($device_result) ;
				$sql = "SELECT * FROM printers_and_scanners WHERE Connection=" . $result['ID'] ;
				$device_result = mysqli_query($db, $sql) ;
				
				if($device_result !== null){
					$device = mysqli_fetch_assoc($device_result) ;
					if($device !== null){
						
						$result['Device'] = $device ;
						$c++ ;
					};
				
				};
			};

			$group["Port" . $b]  = $result;	
			
		};
		$text = array();
		$text['ports'] = $y ;
		$text['devices'] = $c ;
		$current_group['text'] = $text ;
		$current_group["Ports"] = $group ;
		
		if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
			array_push($firstFloor, $current_group);
		} else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
			array_push($thirdFloor, $current_group);
		} else {
			array_push($mainFloor, $current_group);
		};

		//array_push($group_objects, $current_group);
		
		
	};
};

	$firstJson = '{"coords" : ' . json_encode($firstFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$mainJson = '{"coords" : ' . json_encode($mainFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	$thirdJson = '{"coords" : ' . json_encode($thirdFloor, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	//$json = '{"coords" : ' . json_encode($group_objects, JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS) . '}';
	
	//echo($firstJson) . "</br>";
	//echo($mainJson) . "</br>";
	//echo($thirdJson) . "</br>";
	$jsonf = fopen($jsonfile, "w") or die ("Unable to open json file");
	$success = fwrite($jsonf, $mainJson);
	fclose($jsonf);
	
	unset($sql) ;
	unset($result) ;
	$sql = "SELECT * FROM graveyard";
	$result_set = mysqli_query($db,$sql)  ;
			
?>
