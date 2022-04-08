<?php

set_time_limit(90);
$servername = "localhost";
$username = "root";	//dbc_username
$password = "";	//dbc_password
$dbname = "fabsam_csms_test";		//dbc_name

// Create connection
$conn = @mysqli_connect($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Check connection
if (mysqli_connect_errno() ) {
    die("Connection failed: " .mysqli_connect_error());
}

//VARIABLES;
// date_default_timezone_set('Asia/Karachi');
$LIMIT=200;
$ORDERBY="priority";
    
    
    
