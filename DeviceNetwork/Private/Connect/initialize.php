<?php

ob_start(); //Output buffering is on
  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory
  define("PRIVATE_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PRIVATE_PATH));
  define("PUBLIC_PATH", PROJECT_PATH . '/Public');
  //define("SHARED_PATH", PRIVATE_PATH . '/shared');
  
  //Assign the root URL to a PHP constant
  // * Do not need to include the domain
  // * Use the same document root as webserver
  // * Will dynamically find everything in URL up to "/public"
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/Public') +7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);
  
require_once('functions.php') ;

require_once('database.php') ;

$db = db_connect() ;
confirm_db_connect($db) ;
?>