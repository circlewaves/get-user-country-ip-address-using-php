<?php
/**
*----------------------------------------- 
* Author: Max Kostinevich <info@circlewaves.com>
*
* Source URL: https://github.com/circlewaves/get-user-country-ip-address-using-php/
* Article URL: http://circlewaves.com/blog/get-user-country-by-ip/ 
*
* MIT license. 
* circlewaves.com - All rights reserved, 2014 (c)
* 
* Version 1.0.0 / Last updated: September 08, 2014
*-----------------------------------------
*/

/**
*----------------------------------------- 
* GETTING STARTED
*
* 1. Import table (file 'ip_country_list.sql') to your MySQL database.
* You can download CSV-file with new GeoLocation data here: http://dev.maxmind.com/geoip/legacy/geolite/
* 
* 2. Fill in database settings below.
*
* 3. Script is ready!
*-----------------------------------------
*/


/**
*----------------------------------------- 
* Database settings 
*-----------------------------------------
*/
define('DB_HOST',			'localhost');
define('DB_NAME',			'database_name_here');
define('DB_USER',			'username_here');
define('DB_PASS',			'password_here');

/**
*----------------------------------------- 
* Functions 
*-----------------------------------------
*/

// Connect to Database

function db_connect(){
	// Just for example, in live project you should use mysqli
	$db_link=mysql_connect(DB_HOST, DB_USER, DB_PASS);
	
	if (!$db_link){
		die('Database connection error: ' . mysql_error());
	}
	
	if (!mysql_select_db(DB_NAME, $db_link)){
		die ('Database error: ' . mysql_error());
	}
}

// Get User IP 
function get_client_ip() {
	 $ipaddress = '';
	 if (isset($_SERVER['HTTP_CLIENT_IP']))
			 $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	 else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	 else if(isset($_SERVER['HTTP_X_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	 else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	 else if(isset($_SERVER['HTTP_FORWARDED']))
			 $ipaddress = $_SERVER['HTTP_FORWARDED'];
	 else if(isset($_SERVER['REMOTE_ADDR']))
			 $ipaddress = $_SERVER['REMOTE_ADDR'];
	 else
			 $ipaddress = 'UNKNOWN';

	 return $ipaddress; 
}

// Get user country by IP
function get_user_country($user_ip){
	if($user_ip){
		$rs=mysql_query("SELECT `country_name`
		FROM `ip_country_list`
		WHERE
		INET_ATON('".$user_ip."') BETWEEN ip_range_start_int AND ip_range_end_int
		LIMIT 1");
	 if(list($user_country)=mysql_fetch_row($rs)){
		return $user_country;
	 }else{
		 return "Can't find user country, probably you run this script from your localhost";
	 }
	}
}

/**
*----------------------------------------- 
* Execute functions 
*-----------------------------------------
*/

// Connect to database
db_connect();

// Get user IP
$user_ip=get_client_ip();

// Get user country by user IP
$user_country=get_user_country($user_ip);

?>


<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<title>How to get user Country by IP-address using PHP</title>
</head>
<body>
	<h1>How to get user Country by IP-address using PHP</h1>
	<hr />
	
	<h3>User IP: <?php echo $user_ip; ?></h3>
	<h3>User Country: <?php echo $user_country; ?></h3>
</body>
</html>