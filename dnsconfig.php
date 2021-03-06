<?php
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	
	session_start();

	//laptop sql
	$mysql_host  	= 	"localhost"; 
	$mysql_db     	= 	"mcc_fundraise";
	$mysql_user   	= 	"root"; 
	$mysql_pass   	= 	"pwd1"; 

	define('DB_SERVER', $mysql_host);
	define('DB_DATABASE', $mysql_db);
	define('DB_USERNAME', $mysql_user);
	define('DB_PASSWORD', $mysql_pass);

	define('ADMIN_EMAIL_FROM', "DayNightSoft@gmail.com");
	define('EMAIL_HEADER', "MIME-Version: 1.0\nContent-type: text/html; charset=iso-8859-1");

	define('PRODUCT_COMPANY_NAME',"DayNightSoft@gmail.com");
	define('LOG_FILE', 'log/fundraise_' . date('Y-m-d') . '.log');
	define('AUTH_SALT','smoims928sds0zsms280!');
	//store log in the database, 1 is yes to store, 0 is no to store.
	define('DB_LOG', 0);

	//unix path code
	//$project_path	= 	$_SERVER['DOCUMENT_ROOT']. '/';

	//windows path code
	$project_path	= 	dirname(__FILE__). '/';
	define('PROJECT_PATH', $project_path);

	ini_set('display_errors',1);
	ini_set('allow_url_include', 'on');
	//date_default_timezone_set('America/Chicago');
	date_default_timezone_set('UTC');
	setlocale(LC_MONETARY,"en_US");
	set_time_limit(3600);
?>