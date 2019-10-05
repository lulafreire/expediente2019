<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include_once("conn.php");

// Pesquisa os dados da unidade logada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
while($u=mysqli_fetch_array($sqlUnidade)) {

    $nomeUnidade  = $u['nome'];
    $siglaUnidade = $u['sigla'];
    $endUnidade   = $u['end'];
    $telUnidade   = $u['tel'];
    $emailUnidade = $u['email'];

}

// Recupera dados do formulário
if(isset($_POST))
{
    $contato         = utf8_decode($_POST['contato']);
    $id_contato      = $_POST['id_contato'];
    $assunto         = utf8_decode($_POST['assunto']);
    $interessado     = utf8_decode($_POST['interessado']);
    $texto           = utf8_decode($_POST['txtArtigo']);
    $cargo           = utf8_decode($_POST['cargo']);
    $orgao           = utf8_decode($_POST['orgao']);
    $endereco        = utf8_decode($_POST['endereco']);
    $cep             = $_POST['cep'];
    $cidade          = utf8_decode($_POST['cidade']);   
    $emissor         = utf8_decode($_POST['emissor']);
    $id_emissor      = $_POST['id_emissor'];
    $matricula       = $_POST['matricula'];
    $funcao          = utf8_decode($_POST['funcao_emissor']);
    $resposta        = utf8_decode($_POST['resposta']);
}

// Define o número do Ofício atual
$hoje = date('d/m/Y');
$ano = date('Y');
$numOficio = mysqli_query($conn, "SELECT numero FROM documentos WHERE tipo ='0' and data like '$ano-%' and unidade = '$codUnidade' ORDER BY numero DESC LIMIT 1");
$resNumAnt = mysqli_num_rows($numOficio);
if($resNumAnt=='')
{
    $numAnt = 0;
}
else
{
    while($num = mysqli_fetch_array($numOficio))
    {
        $numAnt   = $num['numero'];    
    }   
}

$numAtual = $numAnt + 1;

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
            <b>OFÍCIO Nº $numAtual/$ano/$siglaUnidade/INSS</b>, em $hoje<br>
        </div>
    </div>
    <div class='row'>    
        <div class='col-12' style='line-height: 120%;'>        
            <p>&nbsp;<p>
            Ao(à) Senhor(a)<br>
            <b>". utf8_encode($destinatario). "</b><br>"
            . utf8_encode($cargo). "<br>"
            . utf8_encode($orgao). "<br>"
            . utf8_encode($endereco). "<br>
            $cep -" . utf8_encode($cidade). "<br>
            <br>
            <b>Assunto:</b>". utf8_encode($assunto). "<br>
            <b>Interessado(a):</b>". utf8_encode($interessado). "<br>
            <br>
            <br>        
            $tratamento,
            <br>
        </div>
    </div>
    <div class='row'>
        <div class='col-12' align='justify' style='line-height: 120%;'>"
        . utf8_encode($texto).
        "</div>
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
            <b>". utf8_encode($emissor). "</b><br>
            Matrícula $matricula<br>"
            . utf8_encode($funcao).

        "</div>
    </div>
</div>
";

//define o rodapé da página
$footer="</tbody>
</table>
</div>
<div id='footer'>
    <div align='center'><small><b>$nomeUnidade</b></small><p><small>$endUnidade - Fone $telUnidade e-mail: $emailUnidade</small></div><p class='page'><small>OFÍCIO Nº $numAtual/$ano/$siglaUnidade/INSS - Página </p>
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
$dompdf-> stream("oficio-$numero-$ano.pdf", array("Attachment" => false));

//$dompdf->stream();

// Verifica se o destinatário já está salvo ou precisa salvar
$sqlDest = mysqli_query($conn, "SELECT * FROM contatos WHERE nome = '$destinatario' and orgao = '$orgao'");
$resDest = mysqli_num_rows($sqlDest);
if(!$resDest)
{
    $gravaDest = mysqli_query($conn, "INSERT INTO contatos (nome, cargo, orgao, endereco, cep, cidade) VALUES ('$destinatario', '$cargo', '$orgao', '$endereco', '$cep', '$cidade')");
    $id_destinatario = mysqli_insert_id($conn);
}

//Verifica se o emisor já está cadastrado ou precisa cadastrar
$sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE nome = '$emissor'");
$resEmissor = mysqli_num_rows($sqlEmissor);
if(!$resEmissor)
{
    $gravaEmissor = mysqli_query($conn, "INSERT INTO usuarios (nome, matricula, funcao) VALUES ('$emissor','$matricula','$funcao') ");
    $id_emissor = mysqli_insert_id($conn);
}

// Grava os dados do novo Ofício
$grava = mysqli_query($conn, "INSERT INTO documentos (emissor, destinatario, interessado, assunto, texto, numero, data, tipo, tratamento, unidade) VALUES ('$id_emissor', '$id_destinatario', '$interessado', '$assunto', '$texto', '$numAtual/$ano', curdate(), '0', '$tratamento','$codUnidade')");
$id_oficio = mysqli_insert_id($conn);

$n = explode("-", $resposta);
$id_resposta=$n[0];
$gravaResposta = mysqli_query($conn, "UPDATE documentos SET resposta = '$id_oficio' WHERE id = '$id_resposta'");