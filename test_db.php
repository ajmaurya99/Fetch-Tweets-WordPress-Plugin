<?php

// Connect to database using db_lib.php
require('db_lib.php');
$db = new db();

// Test connection with a simple query
$result = $db->select("SHOW DATABASES");
while($row = mysqli_fetch_assoc($result)) {
	print $row['Database'] . "\n";
}

?>
