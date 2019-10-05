<?php
	require_once 'conn.php';
	require_once 'functions.php';
 
	$search = $_GET['term'];
 
	$query = $conn->query("SELECT * FROM `documentos` WHERE `numero` LIKE '%$search%' AND resposta ='0' AND tipo = '0' ORDER BY `numero` ASC") or die(mysqli_connect_errno());
 
	$list = array();
	$rows = $query->num_rows;
 
	if($rows > 0){
		while($fetch = $query->fetch_assoc()){

			$id_dest = $fetch['destinatario'];
			$query_emissor = $conn->query("SELECT nome FROM contatos WHERE id = '$id_dest'");
			while($dados = $query_emissor->fetch_assoc()){
				$nome_dest = $dados['nome'];
			}

			$data['value'] = $fetch['id']." - Ofício nº ".$fetch['numero']."/APSIRECE/INSS, para ".$nome_dest.", emitido em ".converteData($fetch['data']); 
			array_push($list, $data);
		}
	}
 
	echo json_encode($list);
?>