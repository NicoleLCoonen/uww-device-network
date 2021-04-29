<?php
$results = array();
$modelArr = array();
$compTotal = 0;
	if(is_post_request()){
		$reportType = $_POST["data-report"];
		
		if($reportType === "general"){
			$sql = "SELECT * FROM Data_ports WHERE Port_Status=1";
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			$result["On"] = mysqli_num_rows($resultSet);
			
			unset($sql, $resultSet);
			
			$sql = "SELECT * FROM Data_ports WHERE Port_Status=0 && Damaged=0" ;
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			$result["Off"] = mysqli_num_rows($resultSet);
			
			unset($sql, $resultSet);
			
			$sql = "SELECT * FROM Data_ports WHERE Port_Status=0 && Damaged=1" ;
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			$result["Broken"] = mysqli_num_rows($resultSet);
			
			unset($sql, $resultSet);
				
			$result["TotalPorts"] = $result["On"] + $result["Off"] + $result["Broken"];
			
			$sql = "SELECT DISTINCT Model FROM Computers" ;
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			
			while($modelResult = mysqli_fetch_assoc($resultSet)){
				
				if($modelResult["Model"] == ""){continue;}
				else{array_push($modelArr, $modelResult["Model"]);};
			};
			
			unset($sql, $resultSet);
			
			foreach($modelArr as $model){
				$sql = "SELECT * FROM Computers WHERE Model='" . $model . "'";
				$resultSet = mysqli_query($db, $sql);
				$result[$model] = mysqli_num_rows($resultSet);
				$compTotal = $compTotal + $result[$model];
			};
			
			$result["compTotal"] = $compTotal ;
			
			//print_r($result);
			//print_r($modelArr);
			//echo("Total: " . $result["TotalPorts"] . "</br>");
			//echo("On: " . $result["On"] . "</br>");
			//echo("Off: " . $result["Off"] . "</br>");
			//echo("Broken: " . $result["Broken"] . "</br>");
			
		}else if($reportType === "withdrawn"){
			
			$sql = "SELECT * FROM graveyard";
			
			$result_set = mysqli_query($db, $sql);
			
			echo($sql);
			return($result_set);
			
		}else if($reportType === "models"){
			$floors = array();
			$first = array();
			$main = array();
			$third = array();
			
			$sql = "SELECT DISTINCT Model FROM Computers" ;
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			
			while($modelResult = mysqli_fetch_assoc($resultSet)){
				
				if($modelResult["Model"] == ""){continue;}
				else{array_push($modelArr, $modelResult["Model"]);};
			};
			
			unset($sql, $resultSet);
			
			foreach($modelArr as $model){
				$sql = "SELECT * FROM Computers WHERE Model='" . $model . "'";
				$resultSet = mysqli_query($db, $sql);
				$result[$model] = mysqli_num_rows($resultSet);
				
				// f = first floor count; m = main floor count; t = third floor count;
				$f = 0;
				$m = 0;
				$t = 0;	
				
				while($breakdown = mysqli_fetch_assoc($resultSet)){
					$connection = $breakdown["Connection"];
					$auxSql = "SELECT * FROM Data_ports WHERE ID=" . $connection;
					
					$conResultSet = mysqli_query($db, $auxSql);
					confirm_result_set($conResultSet);
					
					while($conResult = mysqli_fetch_assoc($conResultSet)){
						$group = $conResult["Port_Group"];
						$moreAuxSql = "SELECT * FROM Port_Groups WHERE ID=" . $group;
						
						$groupResultSet = mysqli_query($db, $moreAuxSql);
						confirm_result_set($groupResultSet);
						
						while($groupResult = mysqli_fetch_assoc($groupResultSet)){
							$location = $groupResult["Location"];
							
							if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
								$f++;
							} else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
								$t++;
							} else{
								$m++;
							};		
						}
					};
				};
				$first[$model] = $f;
				$main[$model] = $m;
				$third[$model] = $t;
				
			};
			
			$floors["First Floor"] = $first;
			$floors["Main Floor"] = $main;
			$floors["Third Floor"] = $third;
			
		}else if($reportType === "thirdParty"){
			
			$sql = "SELECT DISTINCT Vendor FROM Printers_and_Scanners" ;
			
			$resultSet = mysqli_query($db, $sql);
			confirm_result_set($resultSet);
			
			while($vendorResult = mysqli_fetch_assoc($resultSet)){
				 
				 foreach($vendorResult as $vendor){
					 
				 };
			};
			
		};
			
	};
?>