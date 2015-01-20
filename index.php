<?php
/*
 * PFP v1.0
 */

//Start the Session
session_start(); 

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');

require_once(ROOT_DIR.'vendor/autoload.php');

// Includes
require(APP_DIR .'config/config.php');
require(APP_DIR .'config/comicvine.php');
require(APP_DIR .'config/recaptcha.php');
require(APP_DIR .'config/cookies.php');
require(ROOT_DIR .'system/pdo2.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/pfp.php');

// Initialize Propel with the runtime configuration
Propel::init(APP_DIR."build/conf/comicslist-conf.php");

// Add the generated 'classes' directory to the include path
set_include_path(APP_DIR."build/classes" . PATH_SEPARATOR . get_include_path());

pfp();

?>
