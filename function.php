<?php
include_once("conexao.php");

function retorna($destinatario, $conn){
		
	$n = explode("-", $destinatario);
	$id=$n[0];	

	$result_aluno = "SELECT * FROM contatos WHERE id = '$id' LIMIT 1";
	$resultado_aluno = mysqli_query($conn, $result_aluno);
	if($resultado_aluno->num_rows){
		$row_aluno = mysqli_fetch_assoc($resultado_aluno);
		$valores['nome_aluno'] = $row_aluno['nome'];
		$valores['cargo'] = $row_aluno['cargo'];
		$valores['orgao'] = utf8_encode($row_aluno['orgao']);
		$valores['endereco'] = utf8_encode($row_aluno['endereco']);
		$valores['cep'] = utf8_encode($row_aluno['cep']);
		$valores['cidade'] = utf8_encode($row_aluno['cidade']);
		$valores['id_destinatario'] = $row_aluno['id'];

		// Pesquisar os ofícios emitidos pelo mesmo destinatário ainda sem resposta
		// Armazenar num novo JSON e transportar para o script
	}else{
		$valores['nome_aluno'] = $n[1];
		$valores['cargo'] = "";
		$valores['orgao'] = "";
		$valores['endereco'] = "";
		$valores['cep'] = "";
		$valores['cidade'] = "";
		$valores['id'] = 0;
	}
	return json_encode($valores);
}

if(isset($_GET['destinatario'])){
	echo retorna($_GET['destinatario'], $conn);
}
?>