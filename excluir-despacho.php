<?php

include("conn.php");

//Identifica o despacho a ser excluído
$id       = $_GET['id'];
$location = $_GET['location'];
$pagina   = $_GET['pagina'];

// Pesquisa se há anexos
$sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia = '$id'");
$resAnexos = mysqli_num_rows($sqlAnexos);
if($resAnexos!=''){

    // Pesquisa o nome do arquivo para excluir da pasta ANEXOS
    while($a=mysqli_fetch_array($sqlAnexos)) {

        $arquivo = $a['arquivo'];

    }
    // Apaga o aquivo físico
    unlink("anexos/$arquivo");
    // Apaga do banco de dados ANEXOS
    $deleteAnexos = mysqli_query($conn, "DELETE FROM anexos WHERE referencia = '$id'");
    
}  

// Apaga do banco de dados EVENTOS
$deleteOcorrencias = mysqli_query($conn, "DELETE FROM eventos WHERE referencia = '$id'");

// Apaga do banco de dados DOCUMENTOS
$deleteOficio = mysqli_query($conn, "DELETE FROM documentos WHERE id='$id'");

// Deleta os arquivos HTML referentes aos Ofícios Emitidos
$deleteHtml = mysqli_query($conn, "DELETE FROM oficios_html WHERE referencia = '$id'");

header("Location: $location.php?pagina=$pagina");


