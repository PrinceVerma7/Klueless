<?php
	$hostname_conn = "localhost:3306";
	$database_conn = "Klueless41";
	$username_conn = "klue41";
	$password_conn = "12345";
	$db_prefix = "";
	// $table_name="klueless13";
	// $discussion_board_link="https://klueless13.wordpress.com";

$conn = mysqli_connect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysqli_select_db($conn, $database_conn) or DIE('Database name is not available!');
?>