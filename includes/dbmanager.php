<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connDB = "localhost";
$database_connDB = "response_relief";
$username_connDB = "response_user";
$password_connDB = "user_response";
$connDB = @mysql_pconnect($hostname_connDB, $username_connDB, $password_connDB) or trigger_error(mysql_error(),E_USER_ERROR); 
$GLOBALS['g_connDB'] = $connDB;
$GLOBALS['g_database_connDB'] = $database_connDB;
?>