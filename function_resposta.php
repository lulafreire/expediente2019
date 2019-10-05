<?php
include_once("conexao.php");

function retorna($resposta, $conn){
		
	$n = explode("-", $resposta);
	$id=$n[0];	

	$result_aluno = "SELECT * FROM documentos WHERE id = '$id' LIMIT 1";
	$resultado_aluno = mysqli_query($conn, $result_aluno);
	if($resultado_aluno->num_rows){
		$row_aluno = mysqli_fetch_assoc($resultado_aluno);		
		$valores['assunto'] = "Resposta ao Ofício nº ". $row_aluno['numero']. " - ". utf8_encode($row_aluno['assunto']);		
		$valores['interessado'] = utf8_encode($row_aluno['interessado']);
	}else{		
		$valores['assunto'] = "";		
	}
	return json_encode($valores);
}

if(isset($_GET['resposta'])){
	echo retorna($_GET['resposta'], $conn);
}
?>