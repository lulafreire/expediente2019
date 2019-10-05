<?php

include("conn.php");

//Identifica o ofício a ser excluído
$id       = $_GET['id'];
$location = $_GET['location'];
$pagina   = $_GET['pagina'];

// Pesquisa se o ofício já foi respondido e impede a exclusão
$sqlResp = mysqli_query($conn, "SELECT resposta FROM documentos WHERE id = '$id'");
while($r=mysqli_fetch_array($sqlResp)) {

    $resposta = $r['resposta'];

}

if($resposta!='0') {

   echo "<script>
    alert('Não é possível excluir ofícios já respondidos.'); location= 'abas.php';
    </script>";

} else {

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

    header("Location: $location.php?pagina=$pagina");
}

