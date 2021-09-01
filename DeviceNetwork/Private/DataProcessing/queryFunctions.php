<?php 

// Use this one when working from DeviceNetwork
require_once('..\Private\Connect\initialize.php') ;

// Use this one when working from placeholder.php
//require_once('..\Connect\initialize.php') ;

// translate on/off
function get_status($bool) {
	
	if($bool == true){
		return $bool = "On" ; 
	}
	else{return $bool = "Off" ; }
}

//removes the Port_Group field
function trim_array($current_array){

		$keep = array_pop($current_array) ;
		$trash = array_pop($current_array) ;
		$current_array['Damaged'] = $keep ;
		
		return $current_array;
}

//sorts ports into 3 groups based on port_status and damage data
function count_ports_by_status(){
	
	global $db;
	$result = array();
	
	$sql = "SELECT * FROM data_ports WHERE Port_Status=1";
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["On"] = mysqli_num_rows($resultSet);
			
	$sql = "SELECT * FROM data_ports WHERE Port_Status=0 && Damaged=0" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["Off"] = mysqli_num_rows($resultSet);
	
	$sql = "SELECT * FROM data_ports WHERE Port_Status=0 && Damaged=1" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["Broken"] = mysqli_num_rows($resultSet);
	$result["Total Ports"] = $result["On"] + $result["Off"] + $result["Broken"];
	
	return $result;
}

//creates an array of unique model names
function get_models() {
	
	global $db;
	$modelArr = array();
	
	$sql = "SELECT DISTINCT Model FROM computers" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
			
	while($modelResult = mysqli_fetch_assoc($resultSet)){
				
		if($modelResult["Model"] == ""){continue;}
		else{array_push($modelArr, $modelResult["Model"]);};
	};
	return$modelArr;
}

//uses results from get_models to determine how many of each the library owns. useful for determining age at a glance
function count_models($modelArr){
	
	global $db;
	$compTotal = 0;
	foreach($modelArr as $model){
	
		$sql = "SELECT * FROM computers WHERE Model='" . $model . "'";
		$resultSet = mysqli_query($db, $sql);
		confirm_result_set($resultSet);
		
		$result[$model] = mysqli_num_rows($resultSet);
		
		$compTotal = $compTotal + $result[$model];
	};
			
	$result["Total Computers"] = $compTotal ;
	return $result;
}

// Determines a device's location based on its connection
function locate_device($device){
	
	global $db;
	$connection = $device["Connection"];
	$sql = "SELECT * FROM data_ports WHERE ID=" . $connection;
					
	$conResultSet = mysqli_query($db, $sql);
	confirm_result_set($conResultSet);
					
	while($conResult = mysqli_fetch_assoc($conResultSet)){
		$location = locate_port($conResult);
		return $location;
	};
	
}

//gets the location code of a port in order to sort by floor, or eventually, by zone.
function locate_port($port){
	global $db;
	
	$group = $port["Port_Group"];
	$sql = "SELECT * FROM port_groups WHERE ID=" . $group;
						
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
						
	while($groupResult = mysqli_fetch_assoc($resultSet)){
		$location = $groupResult["Location"];
		return $location;
	};
	
}

//sorts an array 
function count_by_floor($locationArr, $f, $m, $t){
	foreach($locationArr as $location){
	
		if(($location >= 1 && $location <= 5) || ($location >= 21 && $location <= 23)){
			$f++;
		} else if (($location >= 15 && $location <= 17) ||( $location == 19)) {
			$t++;
		} else{
			$m++;
		};		
	};	
	return array($f, $m, $t);
}

// This function, as well as create_breakdown, create_table_head, and create_table_body dynamically create html element(s) of a report
function create_overview($assocArray) {
	$display = "<div class='overview'><h3>Total: </h3>";
	
	foreach($assocArray as $key => $content){
		$display .= ("<div class='generic'><h5>" . $key . ":</h5><p>". $content . "</p></div>");
	};
	
	$display .= "</div>";
	return $display;
}


function create_breakdown($multiDimArray){

	$display = "<div class='breakdown'>";
	foreach($multiDimArray as $subArr => $content){
		
		$display .= "<div class='floor'><h3>". $subArr . "</h3>";
		foreach($content as $key => $data){
			$display .= "<div class='generic'><h5>" . $key . ":</h5><p>". $data . "</p></div>";
		};
											
		$display .= "</div>";
	};
								
	$display .= "</div>";
	return $display;	
}

// creates an array of unique venodors
function get_vendors() {
	
	global $db;
	$vendorArr = array();
	
	$sql = "SELECT DISTINCT Vendor FROM printers_and_scanners" ;		
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
			
	while($vendorResult = mysqli_fetch_assoc($resultSet)){
				
		if($vendorResult["Vendor"] == "" || $vendorResult["Vendor"] === null){continue;}
		else{array_push($vendorArr, $vendorResult["Vendor"]);};
	};
	return $vendorArr;
}

function create_table_head($assocArray){
	$keys = array_keys($assocArray);
	$display = "<tr>" ;
	
	foreach($keys as $key){
		if($key === 'Recallable'){
			$key = "Recall";
		};
		
		$display .= "<th>" . str_replace("_", " ", $key) . "</th>"; 
	};
	 
	$display .= "</tr>";
	return $display;
}

function create_table_body($assocArray){
	$display = "<tr>" ;
	
	foreach($assocArray as $key => $data){
		$display .= "<td>" . str_replace("_", " ", $data) . "</td>"; 
	};
	 
	$display .= "</tr>";
	return $display;
}

//replaces null values with N/A for a more easily comprehensible report
function fill_empty_cells($assocArray){
	foreach($assocArray as $th => $td){
		if(!$td){
			$td = "N/A" ;
		};
		$assocArray[$th] = $td;	
	};
	return($assocArray);
}
// converts sql date format (YYYY-MM-DD) to US standard(MM-DD-YYYY)
function date_from_sql($string){
	$date = explode("-", $string) ;
	$year = $date[0] ;
	$month = $date[1];
	$day = $date[2] ;
	$string = $month . "-" . $day . "-" . $year ;
	return($string);
	
}

// self-explanatory
function full_name($assocArray){
	$fullName = $assocArray["First_Name"] . " " . $assocArray["Last_Name"];
	return($fullName);
}


?>