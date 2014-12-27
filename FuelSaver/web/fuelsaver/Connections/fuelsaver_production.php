<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_fuelsaver = "localhost";
$database_fuelsaver = "myphytos_fuel";
$username_fuelsaver = "fuelsaver";
$password_fuelsaver = "fuelsaver";
$fuelsaver = mysql_pconnect($hostname_fuelsaver, $username_fuelsaver, $password_fuelsaver) or trigger_error(mysql_error(),E_USER_ERROR); 
?>