<?php
session_start();

include("conn.php");

// Recupera os dados do formulário
$codigo = $_POST['codigo'];
$chave  = $_POST['chave'];

//Verifica se a unidade está cadastrada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades where cod = '$codigo'");
$resUnidade = mysqli_num_rows($sqlUnidade);
if($resUnidade=='0') {
    
    header("Location: index.php?erro=1");

} else {

    // Verifica se a chave está correta
    while($c=mysqli_fetch_array($sqlUnidade)) {

        $chaveAcesso = $c['chave'];

        if($chave != $chaveAcesso){

            header("Location:index.php?erro=2");

        } else {

           // Grava os dados da unidade na seção e acessa o sistema
           $_SESSION['codUnidade'] = "$codigo";
           header("Location: expediente.php");

        }

    }

}


?>