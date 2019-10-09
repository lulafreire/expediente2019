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

if($dtRecebido=='00/00/0000') {
    $dtRecebido = "<font class='text-muted'><i>Recebimento não confirmado</i></font>";
}

// Pesquisa se o ofício consta como resposta de algum outro
$sqlResposta = mysqli_query($conn, "SELECT * FROM documentos WHERE resposta = '$id'");
$resResp = mysqli_num_rows($sqlResposta);

if($resResp !='') {

    while($resp=mysqli_fetch_array($sqlResposta)) {
        $idOficioOriginal           = $resp['id'];
        $numOficioOriginal          = $resp['numero'];
        $emissorOficioOriginal      = $resp['emissor'];
        $dataOficioOriginal         = $resp['data'];
        $dtEmissaoOficioOriginal    = converteData($dataOficioOriginal);
        $recebidoOficioOriginal     = $resp['recebido'];
        $dtRecebidoOficioOriginal   = converteData($recebidoOficioOriginal);
        $prazoOficioOriginal        = $resp['prazo'];
        $dtPrazoOficioOriginal      = converteData($prazoOficioOriginal);
        $respostaOficioOriginal     = $resp['resposta'];
        $resumoOficioOriginal       = $resp['texto'];
        $anoEmissaoOficioOriginal   = anoEmissao($dtEmissaoOficioOriginal);


        $textColor = "text-success";
        $textTitle = "Responde ao Ofício nº $numOficioOriginal";

        // Pesquisa os dados do emissor da Resposta
        $sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$emissorOficioOriginal'");
        while($n = mysqli_fetch_array($sqlEmissor)) {

            $nomeEmissorOficioOriginal   = utf8_encode($n['nome']);
            $orgaoEmissorOficioOriginal  = utf8_encode($n['orgao']);
            $cargoEmissorOficioOriginal  = utf8_encode($n['cargo']);
        }
        $textResposta = "Responde ao <a href='detalha-oficio-recebido.php?id=$idOficioOriginal' target='_self'>Ofício nº $numOficioOriginal/$anoEmissaoOficioOriginal</a>";

    }

} else {

    if($resposta !='0') {

        $textColor = "text-success";
        $textTitle = "Ofício respondido";

        // Pesquisa os dados do ofício de resposta
        $sqlOfResp = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$resposta'");
        while($of = mysqli_fetch_array($sqlOfResp)) {

            $numOficioResposta = $of['numero'];
            $idOficioResposta  = $of['id'];
            $dtOficioResposta = converteData($of['data']);
            $anoEmissaoOficioResposta = anoEmissao($dtOficioResposta);

        }

        $textResposta = "Respondido pelo Ofício nº <a href='detalha-oficio-recebido.php?id=$idOficioResposta'>$numOficioResposta/$anoEmissaoOficioResposta, de $dtOficioResposta</a>";
    
    } else {

        $textColor = "text-danger";
        $textTitle = "Ofício não respondido.";
        $textResposta = "Ofício não respondido.";

    }

}

// Pesquisa o nome do destinatario
$sqlEmissor = mysqli_query($conn, "SELECT * FROM contatos WHERE id = '$destinatario'");
while($n = mysqli_fetch_array($sqlEmissor)) {

    $nomeDest    = utf8_encode($n['nome']);
    $orgao       = utf8_encode($n['orgao']);
    $cargo       = utf8_encode($n['cargo']);
}

// Pesquisa o nome do emissor
$sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = '$emissor'");
while($n = mysqli_fetch_array($sqlEmissor)) {

    $nomeEmissor = utf8_encode($n['nome']);
    $matricula   = utf8_encode($n['matricula']);
}

// Pesquisa o arquivo HTML gerado para o ofício
$sqlHtml = mysqli_query($conn, "SELECT * FROM oficios_html WHERE referencia = '$id'");
while($h = mysqli_fetch_array($sqlHtml)) {

    $arquivoHtml = $h['arquivo'];

}

echo "
<div class='container-fluid'>

    <h6><strong>DETALHES DO OFÍCIO</strong></h6>
    <hr size='1' width='100%'>

    <div class='row'>
        <div class='col-6'>
            <b>Número:</b> $numero/$anoEmissao/$siglaUnidade/INSS<br>
            <b>Destinatário:</b> $nomeDest<br>
            <b>Cargo:</b> $cargo<br>
            <b>Órgão:</b> $orgao<br>
            <hr size='1' width='100%'>
            <b>Emitido em:</b> $dtEmissao<br>
            <b>Recebido em:</b> $dtRecebido <br>
            <b>Referência:</b> $textResposta <br>            
            <hr size='1' width='100%'>
            <b>Assunto:</b> $assunto<br>
        </div>
        <div class='col-6'>
            <b>ANEXOS:</b><br>
            • <a href='dompdf.php?arquivo=$arquivoHtml&nome_arquivo=OFICIO-$numero-$anoEmissao-$siglaUnidade' target='_blanc'>OFÍCIO $numero/$anoEmissao/$siglaUnidade/INSS</a><br>";
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