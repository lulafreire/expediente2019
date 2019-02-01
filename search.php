<?php
	require_once 'conn.php';
 
	$search = $_GET['term'];
 
	$query = $conn->query("SELECT * FROM `contatos` WHERE `nome` LIKE '%$search%' ORDER BY `nome` ASC") or die(mysqli_connect_errno());
 
	$list = array();
	$rows = $query->num_rows;
 
	if($rows > 0){
		while($fetch = $query->fetch_assoc()){
			$data['value'] = $fetch['id']."-".$fetch['nome']." - ".utf8_encode($fetch['orgao']); 
			array_push($list, $data);
		}
	}
 
	echo json_encode($list);
?>