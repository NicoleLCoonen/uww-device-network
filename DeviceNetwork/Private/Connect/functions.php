<?php

function url_for($script_path) {
	// adds the leading / where not present
	if($script_path[0] != '/') {
	$script_path = "/" . $script_path;
	};
	return WWW_ROOT . $script_path ;
	}
	
//shortcuts for URL encoding and HTMLspecialchars	
function u($string="") {
	return urlencode($string);
	}
	
function raw_u($string="") {
	return rawurlencode($string);
	}
	
function h($string="") {
	return htmlspecialchars($string);
	}
	
//error codes
function error_404() {
	header($_SERVER["SERVER_PROTOCOL"] . "404 Not Found");
	exit();	
	}

function error_500() {
	header($_SERVER["SERVER_PROTOCOL"] . "500 Internal Server Error");
	exit();	
	}
	
//redirection
function redirect_to($location) {
	header("Location: " . $location);
	exit;	
	}

//form processing
function is_post_request() {
		return $_SERVER['REQUEST_METHOD'] == 'POST' ;
	}	

function is_get_request() {
		return $_SERVER['REQUEST_METHOD'] == 'GET' ;
	}	
	
// SQL sanitation
function db_escape($connection, $string) {
	return mysqli_real_escape_string($connection, $string) ;
}



?>
