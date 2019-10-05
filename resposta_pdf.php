<?php
include_once("conn.php");

$html = "";
$idRef = $_GET['idRef'];

$sql = "SELECT numero FROM documentos WHERE id = '$idRef'";
$res = mysqli_query($conn, $sql);

require_once 'vendor/autoload.php';

// referenciando o namespace do dompdf
use Dompdf\Dompdf;

// instanciando o dompdf
$dompdf = new Dompdf();

//define o estilo da página pdf
$style="<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <!-- Bootstrap CSS -->
    <link rel='stylesheet' href='node_modules/bootstrap/dist/css/bootstrap.css'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>
<style type='text/css'>
@page {
    margin: 120px 50px 80px 50px;
}
#head{
    background-image: url('img/brasao110px.png');
    background-repeat: no-repeat;
    background-position: center;    
    font-size: 25px;
    text-align: center;
    text-valign: bottom;
    height: 140px;
    width: 100%;
    position: relative;
    top: -110px;
    left: 0;
    right: 0;
    margin: auto;
    margin-bottom: 25px;
    border-bottom: 1px solid gray;
}
#corpo{
    width: 600px;
    position: relative;
    width: 100%;
    top: 0px;
    margin-bottom: 15px;
}
table{
    border-collapse: collapse;
    width: 100%;
    position: relative;
}
td{
    padding: 3px;
}
#footer {
    position: fixed;
    bottom: 20;
    width: 100%;    
    text-align: right;
    font-size: smaller;
    border-top: 1px solid gray;
    margin-top: 10px;
}
#footer .page:after{ 
    content: counter(page); 
}
</style></head><body>";

//define o cabeçalho da página
$head="
<div id='head'>
    <p style='position: fixed; top:10px;'><small><b>INSTITUTO NACIONAL DO SEGURO SOCIAL</b></small></p>
</div>
<br>
<br>
";


//define o corpo do documento
$body="
<div id='corpo'>
    <div class='row'>
        <div class='col-12'>
            <b>OFÍCIO Nº". mysqli_fetch_assoc($res['numero']) . "/APSIRECE/INSS</b>, em 09/02/2019<br>
        </div>
    </div>
    <div class='row'>    
        <div class='col-12' style='line-height: 120%;'>        
            <p>&nbsp;<p>
            Ao(à) Senhor(a)<br>
            <b>Nome do Destinatário</b><br>
            Cargo<br>
            Órgão<br>
            Endereço<br>
            CEP - Cidade<br>
            <br>
            <b>Assunto:</b> Descrição do assunto<br>
            <b>Interessado(a):</b> nome do Interessado<br>
            <b>Assunto:</b> descrição da resposta<br>
            <br>
            <br>        
            Tratamento,
            <br>
        </div>
    </div>
    <div class='row'>
        <div class='col-12' align='justify' style='line-height: 120%;'>
        Texto
        </div>
    </div>
        <br>
        <br>
        <br>
    <div class='row'>
        <div class='col-12' align='center' style='line-height: 120%;'>
            Atenciosamente,
            <br>
            <br>
            <br>
            ____________________________________________________<br>
            <b>Nome do Emissor</b><br>
            Matrícula 123456<br>
            Função
        </div>
    </div>
</div>
";


//define o rodapé da página
$footer="</tbody>
</table>
</div>
<div id='footer'>
    <div align='center'><small><b>Agência da Previdência Social em Irecê/BA</b></small><p><small>Rua Trinta e Três s/n - Centro - Irecê/BA - Fone (74) 3641-3166 e-mail: aps04024020@inss.gov.br</small></div><p class='page'><small>OFÍCIO Nº Número/APSIRECE/INSS - Página </p>
</small></div></body></html>";

//concatenando as variáveis

$html=$head.$style.$body.$footer;

//inserindo o HTML que queremos converter

$dompdf->loadHtml($html);

// Definindo o papel e a orientação

$dompdf->setPaper('A4', 'portrait');

// Renderizando o HTML como PDF

$dompdf->render();

// Enviando o PDF para o browser
$dompdf-> stream("oficio.pdf", array("Attachment" => false));

//$dompdf->stream();