<?php
include_once("conexao.php");
include_once("functions.php");

function retorna($contato, $conn){
		
	$n = explode("-", $contato);
	$id=$n[0];	

	$sql = "SELECT * FROM contatos WHERE id = '$id' LIMIT 1";
	$resultado = mysqli_query($conn, $sql);
	if($resultado->num_rows){
		$row = mysqli_fetch_assoc($resultado);
		$valores['nome'] = utf8_encode($row['nome']);
		$valores['genero'] = $row['genero'];
		//$valores['tratamento'] = utf8_encode($row['tratamento']);
		$valores['cargo'] = utf8_encode($row['cargo']);
		$valores['orgao'] = utf8_encode($row['orgao']);
		$valores['endereco'] = utf8_encode($row['endereco']);
		$valores['cep'] = utf8_encode($row['cep']);
		$valores['cidade'] = utf8_encode($row['cidade']);
		$valores['id_contato'] = $row['id'];
	}else{
		$valores['nome'] = $n[1];
		$valores['genero'] = "";
		//$valores['tratamento'] = "Prezado(a) Sr(a).";
		$valores['cargo'] = "";
		$valores['orgao'] = "";
		$valores['endereco'] = "";
		$valores['cep'] = "";
		$valores['cidade'] = "";
		$valores['id'] = 0;
	}
	return json_encode($valores);
}

if(isset($_GET['contato'])){
	echo retorna($_GET['contato'], $conn);
}
?>