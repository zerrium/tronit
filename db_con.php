<?php 
function OpenCon(){
	$dbhost = "localhost";
	$dbuser = "dbuser";
	$dbpass = "dbpass";
	$db = "database";
	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db);

	// Check connection
	if ($conn -> connect_errno){
		echo "Failed to connect to database: " . mysqli_connect_error() . "<br>Contact Zerrium for more info.<br>";
	}
	return $conn;
}
function CloseCon($conn)
{
	$conn -> close();
}
?>