<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

	require_once 'conn.php';
 
	$search = $_GET['term'];
 
	$query = $conn->query("SELECT * FROM `usuarios` WHERE `nome` LIKE '%$search%' AND unidade = '$codUnidade' ORDER BY `nome` ASC") or die(mysqli_connect_errno());
 
	$list = array();
	$rows = $query->num_rows;
 
	if($rows > 0){
		while($fetch = $query->fetch_assoc()){
			$data['value'] = $fetch['id']."-".$fetch['nome']; 
			array_push($list, $data);
		}
	}
 
	echo json_encode($list);
?>