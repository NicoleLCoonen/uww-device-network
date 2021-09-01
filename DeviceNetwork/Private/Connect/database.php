<?php

require_once('database_credentials.php') ;

// Create connection
function db_connect() {
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) ;
	confirm_db_connect() ;
	return $connection ;
}
// Test if connection succeeded
function confirm_db_connect() {
	if(mysqli_connect_errno()) {
	$msg = "Database connection failed: " ;
	$msg .= mysqli_connect_error() ;
	$msg .= " (" . mysqli_connect_errno() . ")" ;
	exit($msg) ;
	}
}

// Test if query succeded
function confirm_result_set($query_set) {
	if (!$query_set) {
	exit("Database query failed.") ;
	}
}	

// Disconnect
function db_disconnect($connection) {
	if(isset($connection)) {
	mysqli_close($connection) ;
	}
}

?>