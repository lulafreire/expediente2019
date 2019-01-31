<?php
include_once("conexao.php");

function retorna($matricula, $conn){
	$result_aluno = "SELECT * FROM contatos WHERE id = '$matricula' LIMIT 1";
	$resultado_aluno = mysqli_query($conn, $result_aluno);
	if($resultado_aluno->num_rows){
		$row_aluno = mysqli_fetch_assoc($resultado_aluno);
		$valores['cargo'] = $row_aluno['cargo'];
		$valores['orgao'] = $row_aluno['orgao'];
	}else{
		$valores['cargo'] = 'Aluno não encontrado';
	}
	return json_encode($valores);
}

if(isset($_GET['matricula'])){
	echo retorna($_GET['matricula'], $conn);
}
?>