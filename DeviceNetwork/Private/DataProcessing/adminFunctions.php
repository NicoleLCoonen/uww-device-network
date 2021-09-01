<?php
 function is_blank($value) {
    return !isset($value) || trim($value) === '';
  }
  
 function contains_string($value, $required_string) {
   return strpos($value, $required_string) !== false;
 }

function validate_password($password){
	$pwdRegEx = "/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ \*\.\!\@\$\%\^\&\(\)\{\}\[\]\:\;\<\>\,\.\?\/\~\_\+\-\=\|\\ ])$/";
	preg_match($pwdRegEx, $password, $matches );
	$validate = array_search('', $matches);
	return($validate);
	/*if($validate !== false){
		$errors[] = "Your password <strong>must</strong> contain an Uppercase Letter, a Lowercase Letter, a number, and a symbol.";
		return($errors);
	}else if($validate === false){
		return(true);
	};*/
	
}	

function validate_admin($assocArray){
	// creates an array of minimum lengths to check values against later
	$k = array_keys($assocArray);
	$min = array_fill_keys($k, 2);
	$min["Email"] = 5;
	$min["Password"] = 12;

	$max = 30;
	foreach($assocArray as $key => $value){
		if(is_blank($value)){
			$errors[] = str_replace("_", " ", $key) . " cannot be blank.";
		};
		
		if((strlen($value) < $min[$key] || strlen($value) > $max) && !is_blank($value)){
			$errors[] = str_replace("_", " ", $key) . " must be between " . $min[$key] . " and " . $max . " characters.";
 		};
	};
	
	//$errors[] = validate_password($assocArray["Password"]);
		
		
	//$errors[] = contains_string($assocArray["Email"], "uww.edu");
	
	return($errors);
}

  
 function get_admins(){
	global $db;
	
	$adminSql = 'SELECT * FROM admins' ;
	$adminSql .= " ORDER BY Last_Name ASC, First_Name ASC";
	$adminSet = mysqli_query($db, $adminSql);
	confirm_result_set($adminSet);
	
	return($adminSet);
}



?>