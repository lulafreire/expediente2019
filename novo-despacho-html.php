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
    $cidadeUnidade= utf8_encode($u['cidade']);

}

// Recupera dados do formulário
if(isset($_POST))
{
    $referencia      = utf8_decode($_POST['referencia']);
    $assunto         = utf8_decode($_POST['assunto']);
    $interessado     = utf8_decode($_POST['interessado']);   
    $texto           = utf8_encode($_POST['txtArtigo']);    
    $nomeEmissor     = strtoupper(utf8_decode($_POST['emissor']));
    $id_emissor      = $_POST['id_emissor'];
    $matricula       = $_POST['matricula'];
    $funcao          = strtoupper(utf8_decode($_POST['funcao_emissor']));
}

$converte = desconversao($texto);
$novoTexto = utf8_decode($converte);

// Data e Ano
$data = date('d/m/Y');
$ano = date('Y');
$dataExtenso = dataExtenso($data);

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
    
    $numOficio = mysqli_query($conn, "SELECT numero FROM documentos WHERE tipo ='2' and data like '$ano-%' and unidade = '$codUnidade' ORDER BY numero DESC LIMIT 1");
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

// Conta a quantidade de caracteres no campo TEXTO
$qtCaracteres = strlen($texto);

// Condicionais para a formatação do ofício com maior ou menor entrelihas e número da página no rodapé
if($qtCaracteres <= 1820) {

    // Mantém o espaço entrelinhas da tag <p> em 1.15    
    $p = "
    p {

        font-size: 1em;
        line-height: 1.15;
    
    }";

    // Retira a numeração do rodapé
    $footer = "
    <div id='footer'>
        <b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></br>        
    </div>    
    ";
} else {

    if($qtCaracteres < 2550) {

        // Aumenta o espaço entrelinhas da tag <p> para 1.75
        $p = "
        p {

            font-size: 1em;
            line-height: 1.65;
        
        }";

    } else {

        // Volta o espaço entrelinhas da tag <p> para 1.15
        $p = "
        p {

            font-size: 1em;
            line-height: 1.15;
        
        }";
    
    }
    

    // Imprime a numeração no rodapé, a partir da página 2
    $footer = "
    <div id='footer'>
        <p class='page'><strong> </p></strong>
        <b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></br>        
    </div>    
    ";
    
}

// Cria o aquivo oficio.html
$conteudo = "

<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
   <link rel='stylesheet' href='node_modules/css/style.css'>
    <title>Document</title>
</head>

<style type=\"text/css\">

body {

    margin: 0;
    padding: 0;
    font-family: 'Calibri', 'Trebuchet MS', sans-serif;
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    -ms-hyphens: auto;
    hyphens: auto;

}

@page {
    margin: 0.5cm 2cm 1cm 3cm;
}

.cabecalho{

    background-image: url('../../img/brasao110px.png');
    background-repeat: no-repeat;
    background-position: center;        
    height: 115px;
    padding-bottom: 5px;
    width: 100%;
    position: relative;
    top: 0;
    left: 0;
    right: 0;
    margin-bottom: 50px;
    border-bottom: 1px solid gray;

}

.texto-cabecalho {

    font-size: 14px;
    text-align: center;
    font-weight: 900;
    top: 3.3cm;
    position: relative;

}

.expediente {

    font-weight: bold;
    top: 7cm;        
}

.data-emissao {
    
    text-align: right;
    margin-bottom: 20px;
}

.referencia {
    
    text-align: left;
    margin-bottom: 20px;
    margin-left: 8cm;
    line-height: 1.65;    
}

.assunto {

    text-align: left;
    margin-bottom: 40px;    
}

.vocativo {
    
    text-align: left;
    margin-bottom: 20px;
    margin-left: 3.3cm;
}

.corpo {

    position: relative;
    width: 100%;
    top: 0px;
    margin-bottom: 50px;       
    text-align: justify;
}

$p

blockquote, .p {
    
    font-size:14px;
    font-style: italic;

}

.fechamento {

    text-align: left;
    margin-bottom: 20px;    
    margin-left: 3.3cm;
}

.emissor {
    text-align: center;
    margin-top: 50px;
}

#footer {
    position: absolute;
    bottom: 5px;
    width: 100%;    
    text-align: center;
    font-size: 12px;
    border-top: 1px solid gray;
    margin-top: 10px;    
}

#footer .page:after{ 
    content: counter(page); 
}

.page {
    margin: 5px 0px 5px 0px;
}


</style>

<body>

    <div class='container-fluid'>
        <div class='cabecalho'>

            <div class='texto-cabecalho'>
                INSTITUTO NACIONAL DO SEGURO SOCIAL
            </div>

        </div>
        
    </div>

    <div class='expediente'>
        DESPACHO Nº $numero/$ano/$siglaUnidade/INSS</b>
    </div>

    <div class='data-emissao'>
        $cidadeUnidade, $dataExtenso.
    </div>

    <div class='referencia'>
        <b>Ref.: </b>" .utf8_encode($referencia). "<br>
        <b>Int.: </b>" .utf8_encode($interessado). "<br>
        <b>Ass.: </b>" .utf8_encode( $assunto). "<br>        
    </div>       

    <div class='corpo'>
        $texto        
    </div>    

    <div class='emissor'>
        ____________________________________________________<br>
        <b>".utf8_encode($nomeEmissor)."</b><br>
        Matrícula $matricula<br>"
        .utf8_encode($funcao)."
    </div>
            
    $footer

</body>
</html>";

//Verifica se o emisor já está cadastrado ou precisa cadastrar
$sqlEmissor = mysqli_query($conn, "SELECT * FROM usuarios WHERE nome = '$nomeEmissor'");
$resEmissor = mysqli_num_rows($sqlEmissor);
if(!$resEmissor)
{
    $gravaEmissor = mysqli_query($conn, "INSERT INTO usuarios (nome, matricula, funcao, unidade) VALUES ('$nomeEmissor','$matricula','$funcao','$codUnidade') ");
    $id_emissor = mysqli_insert_id($conn);
}

$textoReferencia = "$referencia|$novoTexto";

// Grava os dados do novo Despacho
$grava = mysqli_query($conn, "INSERT INTO documentos (emissor, interessado, assunto, texto, numero, data, tipo, unidade) VALUES ('$id_emissor', '$interessado', '$assunto', '$textoReferencia', '$numero', curdate(), '2', '$codUnidade')");
$id_oficio = mysqli_insert_id($conn);

// Grava o evento
$gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'DESPACHO EMITIDO','$id_oficio')");

// Pega da data e hora atuais
$data = date('Ymdhis');
// Dá um nome dinâmico ao arquivo HTML
$arquivo = $data.".html";
// Abre o arquivo para escrita
$file = fopen("$arquivo", "w+");
// Escreve o conteúdo HTML
fwrite($file, $conteudo);
// Fecha o aqruivo
fclose($file);
// Move o arquivo para a pasta OFICIOS-EMITIDOS
rename ($arquivo,"oficios-emitidos/".$arquivo);
// Grava no banco de dados OFICIOS-HTML
$grava = mysqli_query($conn, "INSERT INTO oficios_html (arquivo, referencia) values ('$arquivo','$id_oficio')");
//Redireciona para a página dompdf.php
header("Location: dompdf.php?arquivo=".$arquivo."&nome_arquivo=DESPACHO-".$numero."-".$ano."-".$siglaUnidade);

?>