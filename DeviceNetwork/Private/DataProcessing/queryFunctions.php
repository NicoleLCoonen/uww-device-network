<?php 
// Use this one when working from DNPtesting.php
require_once('..\Private\Connect\initialize.php') ;

// Use this one when working from placeholder.php
//require_once('..\Connect\initialize.php') ;

// translate on/off

function get_status($bool) {
	
	if($bool == true){
		return($bool = "On") ; 
	}
	else{return($bool = "Off") ; }
}


function trim_array($current_array){

		$keep = array_pop($current_array) ;
		$trash = array_pop($current_array) ;
		$current_array['Damaged'] = $keep ;
		
		return($current_array);
}


?>