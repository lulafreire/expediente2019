<?php
session_start();
$codUnidade = $_SESSION['codUnidade'];

include_once("functions.php");
include_once("conn.php");

// Pesquisa os dados da unidade logada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codUnidade'");
while($u=mysqli_fetch_array($sqlUnidade)) {

    $nomeUnidade  = utf8_encode($u['nome']);
    $siglaUnidade = $u['sigla'];
    $endUnidade   = utf8_encode($u['end']);
    $telUnidade   = $u['tel'];
    $emailUnidade = $u['email'];

}

// Recupera dados do formulário
if(isset($_POST))
{
    $nomeContato     = retiraAcentos(utf8_decode($_POST['contato']));
    $genero          = $_POST['genero'];
    $id_contato      = $_POST['id_contato'];
    $assunto         = utf8_decode($_POST['assunto']);
    $interessado     = utf8_decode($_POST['interessado']);
    $assunto         = utf8_decode($_POST['assunto']);
    $texto           = utf8_decode($_POST['txtArtigo']);
    $cargo           = utf8_decode($_POST['cargo']);
    $orgao           = utf8_decode($_POST['orgao']);
    $endereco        = utf8_decode($_POST['endereco']);
    $cep             = $_POST['cep'];
    $cidade          = utf8_decode($_POST['cidade']);    
    $nomeEmissor     = strtoupper(utf8_decode($_POST['emissor']));
    $id_emissor      = $_POST['id_emissor'];
    $matricula       = $_POST['matricula'];
    $funcao          = strtoupper(utf8_decode($_POST['funcao_emissor']));
    $resposta        = utf8_decode($_POST['resposta']);
}


// Data e Ano
$data = date('d/m/Y');
$ano = date('Y');

// Pronome de tratamento
$pronomeDeTratamento = pronomeDeTratamento($cargo, $genero);
$p = explode("|", $pronomeDeTratamento);
$enderecamento = $p[0];
$vocativo      = $p[1];

// Caso não tenha definido o número manualmente, define de forma automatica
if(isset($_POST['numeracao']))
{
    $numero = $_POST['numero'];

} else {
    
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
            $numeroCompleto = $num['numero'];
            $n = explode('/', $numeroCompleto);
            $numAnt   = $n[0];    
        }   
    }

    $numero = $numAnt + 1;

}

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
    margin: 120px 50px 80px 120px;
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
    margin-bottom: 20px;
}
table{
    border-collapse: collapse;
    width: 100%;
    position: relative;
}
td{
    padding: 3px;
}
blockquote {
    margin-left: 10%;
    font-style:italic;
    font-size: 0.9em;
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
#footer .rodape {
    font-size: 7px;
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
            <b>OFÍCIO Nº $numero/$ano/$siglaUnidade/INSS</b>, em $data<br>
        </div>
    </div>
    <div class='row'>    
        <div class='col-12' style='line-height: 120%;'>        
            <p>&nbsp;<p>
            $enderecamento<br>
            <b>".utf8_encode($nomeContato)."</b><br>"
            .utf8_encode($cargo)."<br>"
            .utf8_encode($orgao). "<br>"
            .utf8_encode($endereco)."<br>
            $cep - ".utf8_encode($cidade)."<br>
            <br>
            <b>Assunto: </b>" .utf8_encode($assunto)."<br>
            <b>Interessado(a): </b>" .utf8_encode($interessado)."<br>
            <br>
            <br>        
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$vocativo,
            <br>
        </div>
    </div>
    <div class='row'>
        <div class='col-12' align='justify' style='line-height: 120%;'>$texto</div>
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
            <b>".utf8_encode($nomeEmissor)."</b><br>
            Matrícula $matricula<br>"
            .utf8_encode($funcao).
        "</div>
    </div>
</div>
";

//define o rodapé da página
$footer="</tbody>
</table>
</div>
<div id='footer'>
    <div align='center'><small><b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b></small><p class='rodape'><small>$endUnidade<br>Fone $telUnidade - e-mail: $emailUnidade</small></div><p class='page'><small>OFÍCIO Nº $numero/$ano/$siglaUnidade/INSS - Página </p>
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
$dompdf-> stream("OFICIO $numero-$ano-APSIRECE-INSS.pdf", array("Attachment" => false));

//$dompdf->stream();

// Verifica se o destinatário já está salvo ou precisa salvar
$sqlDest = mysqli_query($conn, "SELECT * FROM contatos WHERE nome = '$nomeContato' and orgao = '$orgao'");
$resDest = mysqli_num_rows($sqlDest);
if(!$resDest)
{
    $gravaDest = mysqli_query($conn, "INSERT INTO contatos (nome, genero, cargo, orgao, endereco, cep, cidade, unidade) VALUES ('$nomeContato', '$genero', '$cargo', '$orgao', '$endereco', '$cep', '$cidade','$codUnidade')");
    $id_destinatario = mysqli_insert_id($conn);
}

//Verifica se o emisor já está cadastrado ou precisa cadastrar
$sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE nome = '$nomeEmissor'");
$resEmissor = mysqli_num_rows($sqlEmissor);
if(!$resEmissor)
{
    $gravaEmissor = mysqli_query($conn, "INSERT INTO usuarios (nome, matricula, funcao, unidade) VALUES ('$nomeEmissor','$matricula','$funcao','$codUnidade') ");
    $id_emissor = mysqli_insert_id($conn);
}

// Grava os dados do novo Ofício
$grava = mysqli_query($conn, "INSERT INTO documentos (emissor, destinatario, interessado, assunto, texto, numero, data, tipo, unidade) VALUES ('$id_emissor', '$id_contato', '$interessado', '$assunto', '$texto', '$numero', curdate(), '0', '$codUnidade')");
$id_oficio = mysqli_insert_id($conn);

$n = explode("-", $resposta);
$id_resposta=$n[0];
$gravaResposta = mysqli_query($conn, "UPDATE documentos SET resposta = '$id_oficio' WHERE id = '$id_resposta'");
