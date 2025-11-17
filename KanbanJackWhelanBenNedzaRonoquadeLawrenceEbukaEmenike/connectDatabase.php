<?php
	$servername = "ggcdomains";
	$username = "rlawrenc_guest";
	$password = "ggcITEC4450@";
	$dbname = "rlawrenc_sportsdb";
					
	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
	}
?>
