<?php
session_start();

$codUnidade = $_SESSION['codUnidade'];

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">

    <!-- JQUERY Autocomplete CSS -->
    <link rel="stylesheet" type="text/css" href="ui/jquery-ui.css"/>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <!-- JQUERY completar formulário -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>

    <title>.:: Expediente ::.</title>
  </head>
  <body>

<?php

include_once("conn.php");
include_once("functions.php");

// Pesquisa os dados da unidade logada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
while($u=mysqli_fetch_array($sqlUnidade)) {

    $nomeUnidade  = utf8_encode($u['nome']);
    $siglaUnidade = $u['sigla'];
    $endUnidade   = utf8_encode($u['end']);
    $telUnidade   = $u['tel'];
    $emailUnidade = $u['email'];
    $cidadeUnidade= utf8_encode($u['cidade']);

}

// Identifica o ofício selecionado
$id = $_GET['id'];

// Pesquisa os dados do ofício selecionado
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
while($o=mysqli_fetch_array($sqlOficio)) {

    $id           = $o['id'];
    $numero       = $o['numero'];
    $emissor      = $o['emissor'];
    $assunto      = utf8_encode($o['assunto']);
    $destinatario = $o['destinatario'];
    $interessado  = $o['interessado'];
    $data         = $o['data'];
    $dtEmissao    = converteData($data);
    $recebido     = $o['recebido'];
    $dtRecebido   = converteData($recebido);
    $prazo        = $o['prazo'];
    $dtPrazo      = converteData($prazo);
    $resposta     = $o['resposta'];
    $resumo       = $o['texto'];
    $anoEmissao   = anoEmissao($dtEmissao); 

}

// Pesquisa o nome do emissor
$sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$emissor'");
while($n = mysqli_fetch_array($sqlEmissor)) {

    $nomeEmissor = utf8_encode($n['nome']);
    $orgao       = utf8_encode($n['orgao']);
    $cargo       = utf8_encode($n['cargo']);
}

// Flag prazo e resposta
$hoje = date('Y-m-d');

if($resposta !='0') {

    $textColor = "text-success";
    $textTitle = "Ofício respondido";        
    
    // Pesquisa os dados do ofício de resposta
    $sqlResposta = mysqli_query($conn, "SELECT * FROM documentos where id = '$resposta'");
    while($resp=mysqli_fetch_array($sqlResposta)) {

        $numeroResposta = $resp['numero'];
        $dataResposta   = converteData($resp['data']);
        $oficioResposta = "Ofício nº $numeroResposta/APSIRECE/INSS, de $dataResposta";
        $idResposta     = $resp['id'];

        // Pesquisa o arquivo HTML gerado para o ofício
        $sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$idResposta'");
        while($h = mysqli_fetch_array($sqlHtml)) {

            $arquivoHtml = $h['arquivo'];

        }

        $textoResposta = "<strong>Resposta:</strong> <a href='detalha-oficio-emitido.php?id=$idResposta' target='_self'>$oficioResposta</a>";
    }

} else {

    if($prazo >= $hoje) {

        if($prazo > $hoje) {
        
            $textColor = "text-primary";
            $textTitle = "Ofício não respondido. Prazo $dtPrazo";

        } else {
            $textColor = "text-warning";
            $textTitle = "Ofício não respondido. Prazo vence hoje: $dtPrazo";
        }
        

    } else {

        if($prazo !="0000-00-00") {
            
            $textColor = "text-danger";
            $textTitle = "Ofício não respondido. Prazo ultrapassado: $dtPrazo";
        
        } else {

            $textColor = "text-primary";
            $textTitle = "Ofício não respondido. Prazo indefinido.";

        }
        

    }

    $textoResposta = "<strong>Resposta:</strong> Ofício não respondido.";
}

echo "
<div class='container-fluid'>

    <h6><strong>DETALHES DO OFÍCIO</strong></h6>
    <hr size='1' width='100%'>

    <div class='row'>
        <div class='col-6'>
            <b>Número:</b> $numero/$anoEmissao<br>
            <b>Emissor:</b> $nomeEmissor<br>
            <b>Cargo:</b> $cargo<br>
            <b>Órgão:</b> $orgao<br>
            <hr size='1' width='100%'>
            <b>Emitido em:</b> $dtEmissao<br>
            <b>Recebido em:</b> $dtRecebido <br>
            <i class='fas fa-check $textColor'></i> <b>Status:</b> $textTitle <br>
            $textoResposta<br>
            <hr size='1' width='100%'>
            <b>RESUMO:</b><br>
            $resumo<br>
        </div>
        <div class='col-6'>
            <b>ANEXOS:</b><br>";
            // Pesquisa os anexos relacionados a este ofício
            $sqlAnexos = mysqli_query($conn, "SELECT * FROM anexos WHERE referencia ='$id' ORDER BY id ASC");
            while($a=mysqli_fetch_array($sqlAnexos)) {
                $idAnexo = $a['id'];
                $arquivo = $a['arquivo'];
                $tam     = $a['tam'];
                $dtAnexo = converteData($a['data']);
                $descricao = utf8_encode($a['descricao']);

                echo "• <a href='download.php?url=$arquivo'>$descricao</a> <small class='text-muted'><i>($tam Mb) Anexado em $dtAnexo</i></small><br>";
            }
            echo "<hr>
            <strong>HISTÓRICO:</strong><br>";

            // Pesquisa os eventos relacionados ao ofício
            $sqlEventos = mysqli_query($conn, "SELECT * FROM eventos WHERE referencia = '$id' ORDER BY id ASC");
            while($e=mysqli_fetch_array($sqlEventos)) {
                $data = converteDataHora($e['data']);
                $evento = $e['descricao'];

                echo "<small>$data - $evento</small><br>";

            }
            echo "
        </div>
    </div>

</div>";

?>