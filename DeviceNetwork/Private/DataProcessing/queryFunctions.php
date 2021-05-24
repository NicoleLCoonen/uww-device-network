<?php 


// Use this one when working from DNPtesting.php
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

function count_ports_by_status(){
	
	global $db;
	$result = array();
	
	$sql = "SELECT * FROM Data_ports WHERE Port_Status=1";
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["On"] = mysqli_num_rows($resultSet);
			
	$sql = "SELECT * FROM Data_ports WHERE Port_Status=0 && Damaged=0" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["Off"] = mysqli_num_rows($resultSet);
	
	$sql = "SELECT * FROM Data_ports WHERE Port_Status=0 && Damaged=1" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
	$result["Broken"] = mysqli_num_rows($resultSet);
	$result["Total Ports"] = $result["On"] + $result["Off"] + $result["Broken"];
	
	return $result;
}

function get_models() {
	
	global $db;
	$modelArr = array();
	
	$sql = "SELECT DISTINCT Model FROM Computers" ;
			
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
			
	while($modelResult = mysqli_fetch_assoc($resultSet)){
				
		if($modelResult["Model"] == ""){continue;}
		else{array_push($modelArr, $modelResult["Model"]);};
	};
	return$modelArr;
}

function count_models($modelArr){
	
	global $db;
	$compTotal = 0;
	foreach($modelArr as $model){
	
		$sql = "SELECT * FROM Computers WHERE Model='" . $model . "'";
		$resultSet = mysqli_query($db, $sql);
		confirm_result_set($resultSet);
		
		$result[$model] = mysqli_num_rows($resultSet);
		
		$compTotal = $compTotal + $result[$model];
	};
			
	$result["Total Computers"] = $compTotal ;
	return $result;
}

// Determines a device's location based on its' connection
function locate_device($device){
	
	global $db;
	$connection = $device["Connection"];
	$sql = "SELECT * FROM Data_ports WHERE ID=" . $connection;
					
	$conResultSet = mysqli_query($db, $sql);
	confirm_result_set($conResultSet);
					
	while($conResult = mysqli_fetch_assoc($conResultSet)){
		$location = locate_port($conResult);
		return $location;
	};
	
}

function locate_port($port){
	global $db;
	
	$group = $port["Port_Group"];
	$sql = "SELECT * FROM Port_Groups WHERE ID=" . $group;
						
	$resultSet = mysqli_query($db, $sql);
	confirm_result_set($resultSet);
						
	while($groupResult = mysqli_fetch_assoc($resultSet)){
		$location = $groupResult["Location"];
		return $location;
	};
	
}
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
function get_vendors() {
	
	global $db;
	$vendorArr = array();
	
	$sql = "SELECT DISTINCT Vendor FROM Printers_and_Scanners" ;		
			
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
