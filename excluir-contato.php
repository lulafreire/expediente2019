<?php

include("conn.php");

//Identifica o contato a ser excluído
$id       = $_GET['id'];
$location = $_GET['location'];
$pagina   = $_GET['pagina'];

// Deleta do banco de dados
$deleteContato = mysqli_query($conn, "DELETE FROM contatos WHERE id='$id'");

// Pesquisa ofícios emitidos e recebidos referentes ao contato selecionado
$sqlOficios = mysqli_query($conn, "SELECT * FROM documentos WHERE emissor = '$id' OR destinatario = '$id'");
while($o=mysqli_fetch_array($sqlOficios)) {

    $idOficio = $o['id'];

   // Pesquisa se há anexos
   $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia = '$idOficio'");
   $resAnexos = mysqli_num_rows($sqlAnexos);
   if($resAnexos!=''){

       // Pesquisa o nome do arquivo para excluir da pasta ANEXOS
       while($a=mysqli_fetch_array($sqlAnexos)) {

           $arquivo = $a['arquivo'];

       }
       // Apaga o aquivo físico
       unlink("anexos/$arquivo");
       // Apaga do banco de dados ANEXOS
       $deleteAnexos = mysqli_query($conn, "DELETE FROM anexos WHERE referencia = '$idOficio'");
       
   }  

   // Apaga do banco de dados EVENTOS
   $deleteOcorrencias = mysqli_query($conn, "DELETE FROM eventos WHERE referencia = '$idOficio'");
   
   // Apaga do banco de dados DOCUMENTOS
   $deleteOficio = mysqli_query($conn, "DELETE FROM documentos WHERE id='$idOficio'");

   // Pesquisa os arquivos HTML referentes aos Ofícios Emitidos
   $sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$idOficio'");
   while($h=mysqli_fetch_array($sqlHtml)) {

        $arquivoHtml = $h['arquivo'];

        // Apaga o aquivo físico
        unlink("oficios-emitidos/$arquivoHtml");

   }

   // Deleta os arquivos HTML referentes aos Ofícios Emitidos
   $deleteHtml = mysqli_query($conn, "DELETE FROM oficios_html WHERE referencia = '$idOficio'");   

   // Verifica se foi utilizado como resposta a algum ofício
   $sqlResposta = mysqli_query($conn, "SELECT * FROM documentos WHERE resposta = '$idOficio'");
   while($r=mysqli_fetch_array($sqlResposta)) {
       
       $idOficioOriginal = $r['id'];

       // Apaga a referência no Ofício original
       $deleteResposta = mysqli_query($conn, "UPDATE documentos SET resposta ='0' WHERE id = '$idOficioOriginal'");

       // Apaga o evento de resposta
       $deleteEventoResposta = mysqli_query($conn, "DELETE FROM eventos WHERE referencia = '$idOficioOriginal' AND descricao = 'OFICIO RESPONDIDO'");
   }
  
   
}

header("Location: $location.php?pagina=$pagina");
/*
// Deleta os ofícios recebidos do contato selecionado
$sqlOficios = mysqli_query($conn, "SELECT * FROM documentos");
while($o=mysqli_fetch_array($sqlOficios)) {

    $idOficio = $o['id'];

    echo "$idOficio<br>";
    exit;

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

    // Verifica se foi utilizado como resposta a algum ofício
    $sqlResposta = mysqli_query($conn, "SELECT * FROM documentos WHERE resposta = '$id'");
    while($r=mysqli_fetch_array($sqlResposta)) {
        
        $idOficioOriginal = $r['id'];

        // Apaga a referência no Ofício original
        $deleteResposta = mysqli_query($conn, "UPDATE documentos SET resposta ='0' WHERE id = '$idOficioOriginal'");

        // Apaga o evento de resposta
        $deleteEventoResposta = mysqli_query($conn, "DELETE FROM eventos WHERE referencia = '$idOficioOriginal' AND descricao = 'OFICIO RESPONDIDO'");
    } 

}
*/
?>

