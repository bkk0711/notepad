<?php 
 define('DB_SERVER', 'localhost'); //Database Host , mặc định localhost
 define('DB_USERNAME', '');  // Database user
 define('DB_PASSWORD', ''); // Database Password
 define('DB_DATABASE', ''); // Database Name
 $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
 $database   = mysql_select_db(DB_DATABASE) or die(mysql_error());
 $domain 	 = "";	// Domain của bạn có http:// , https://
 $dm         = ""; // domain của bạn không có http:// , https://
 ?>