<?php
	$conn = new mysqli('localhost', 'root', '', 'db_expediente');
 
	if(!$conn){
		die("Error: Can't connect to database");
	}
?>