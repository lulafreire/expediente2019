<?php
	require_once 'conn.php';
	require_once 'functions.php';
 
	$search = $_GET['term'];
 
	$query = $conn->query("SELECT * FROM `documentos` WHERE `numero` LIKE '%$search%' ORDER BY `numero` ASC") or die(mysqli_connect_errno());
 
	$list = array();
	$rows = $query->num_rows;
 
	if($rows > 0){
		while($fetch = $query->fetch_assoc()){
			$data['value'] = "Ofício nº ".$fetch['numero'].", de ".converteData($fetch['data']); 
			array_push($list, $data);
		}
	}
 
	echo json_encode($list);
?>