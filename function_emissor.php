<?php
include_once("conexao.php");

function retorna($emissor, $conn){
		
	$n = explode("-", $emissor);
	$id=$n[0];	

	$result_aluno = "SELECT * FROM usuarios WHERE id = '$id' LIMIT 1";
	$resultado_aluno = mysqli_query($conn, $result_aluno);
	if($resultado_aluno->num_rows){
		$row_aluno = mysqli_fetch_assoc($resultado_aluno);
		$valores['emissor'] = utf8_encode($row_aluno['nome']);        
        $valores['funcao_emissor'] = utf8_encode($row_aluno['funcao']);
		$valores['matricula'] = $row_aluno['matricula'];
		$valores['id_emissor'] = $row_aluno['id'];
		
	}else{
		$valores['emissor'] = $n[1];		
		$valores['funcao_emissor'] = "";
		$valores['matricula'] = "";		
		$valores['id'] = 0;
	}
	return json_encode($valores);
}

if(isset($_GET['emissor'])){
	echo retorna($_GET['emissor'], $conn);
}
?>