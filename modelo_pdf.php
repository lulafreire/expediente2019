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

$idModelo = $_GET['idModelo'];
$sqlModelo = mysqli_query($conn, "SELECT * FROM modelos WHERE id = '$idModelo'");
while($m = mysqli_fetch_array($sqlModelo)) {

    $textoModelo = utf8_encode($m['texto']);
    $tipoModelo  = $m['tipo'];

}

$data = date('d/m/Y');
$ano  = date('Y');
$dataExtenso = dataExtenso($data);

// Conta a quantidade de caracteres no campo TEXTO
$qtCaracteres = strlen($textoModelo);

require_once 'vendor/autoload.php';

// referenciando o namespace do dompdf

use Dompdf\Dompdf;

// instanciando o dompdf

$dompdf = new Dompdf();

// Cria o aquivo html de acordo com o tipo de documento
if($tipoModelo=='OFICIO') {

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
        <div align='center'><b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
    </div>    
    ";
} else {

    if($qtCaracteres < 2550) {

        // Aumenta o espaço entrelinhas da tag <p> para 1.75
        $p = "
        p {

            font-size: 1em;
            line-height: 1.75;
        
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
        <div align='center'><p class='page'><strong> </p></strong>
        <b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
    </div>    
    ";
    
}

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

.enderecamento {
    
    text-align: left;
    margin-bottom: 20px;    
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
        OFÍCIO Nº 9999999/$ano/$siglaUnidade/INSS</b>
    </div>

    <div class='data-emissao'>
        $cidadeUnidade, $dataExtenso.
    </div>

    <div class='enderecamento'>
        Ao(à) Senhor(a)<br>
        <strong>NOME DO DESTINATÁRIO</strong><br>
        CARGO DO DESTINATÁRIO<br>
        ÓRGÃO DO DESTINATÁRIO<br>
        ENDEREÇO DO DESTINATÁRIO<br>
        CEP - CIDADE/UF<br>        
    </div>

    <div class='assunto'>
        <strong>Assunto: </strong>ASSUNTO
    </div>

    <div class='vocativo'>
        Senhor(a) CARGO,
    </div>

    <div class='corpo'>
        $textoModelo        
    </div>

    <div class='fechamento'>
        Atenciosamente,
    </div>

    <div class='emissor'>
        ____________________________________________________<br>
        <b>NOME DO SERVIDOR EMISSOR</b><br>
        MATRÍCULA <br>
        FUNÇÃO
    </div>
            
    $footer

</body>
</html>";
}

if($tipoModelo=='CARTA') {

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
            <div align='center'><b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
        </div>    
        ";
    } else {
    
        if($qtCaracteres < 2550) {
    
            // Aumenta o espaço entrelinhas da tag <p> para 1.75
            $p = "
            p {
    
                font-size: 1em;
                line-height: 1.75;
            
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
            <div align='center'><p class='page'><strong> </p></strong>
            <b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
        </div>    
        ";
        
    }
    
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
    
    .enderecamento {
        
        text-align: left;
        margin-bottom: 20px;    
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
            CARTA Nº 9999999/$ano/$siglaUnidade/INSS</b>
        </div>
    
        <div class='data-emissao'>
            $cidadeUnidade, $dataExtenso.
        </div>
    
        <div class='enderecamento'>
            Ao(à) Senhor(a)<br>
            <strong>NOME DO DESTINATÁRIO</strong><br>            
            ENDEREÇO DO DESTINATÁRIO<br>
            CEP - CIDADE/UF<br>        
        </div>
    
        <div class='assunto'>
            <strong>Assunto: </strong>ASSUNTO
        </div>
    
        <div class='vocativo'>
            Senhor(a) CARGO,
        </div>
    
        <div class='corpo'>
            $textoModelo        
        </div>
    
        <div class='fechamento'>
            Atenciosamente,
        </div>
    
        <div class='emissor'>
            ____________________________________________________<br>
            <b>NOME DO SERVIDOR EMISSOR</b><br>
            MATRÍCULA <br>
            FUNÇÃO
        </div>
                
        $footer
    
    </body>
    </html>";
}

if($tipoModelo=='DESPACHO') {

// Condicionais para a formatação do despacho com maior ou menor entrelihas e número da página no rodapé
if($qtCaracteres <= 2000) {

    // Mantém o espaço entrelinhas da tag <p> em 1.15    
    $p = "
    p {

        font-size: 1em;
        line-height: 1.15;
    
    }";

    // Retira a numeração do rodapé
    $footer = "
    <div id='footer'>
        <div align='center'><b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
    </div>    
    ";
} else {

    if($qtCaracteres < 2550) {

        // Aumenta o espaço entrelinhas da tag <p> para 1.75
        $p = "
        p {

            font-size: 1em;
            line-height: 1.75;
        
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
        <div align='center'><p class='page'><strong> </p></strong>
        <b>".mb_strtoupper($nomeUnidade, 'UTF-8')."</b><br><small>$endUnidade - Fone $telUnidade<br>e-mail: $emailUnidade</small></div></br>        
    </div>    
    ";
    
}

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
    margin-left: 7.5cm;
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
        DESPACHO Nº 9999999/$ano/$siglaUnidade/INSS</b>
    </div>

    <div class='data-emissao'>
        $cidadeUnidade, $dataExtenso.
    </div>

    <div class='referencia'>
        <b>Ref.:</b> NB/PROTOCOLO/SIPPS<br>
        <b>Int.:</b> NOME DO INTERESSADO<br>
        <b>Ass.:</b> ASSUNTO       
    </div>    

    <div class='corpo'>
        $textoModelo        
    </div>

    <div class='fechamento'>
        Atenciosamente,
    </div>

    <div class='emissor'>
        ____________________________________________________<br>
        <b>NOME DO SERVIDOR EMISSOR</b><br>
        MATRÍCULA <br>
        FUNÇÃO
    </div>
            
    $footer

</body>
</html>";
}


/*
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

//define o corpo do documento, de acordo com o tipo
if($tipoModelo=='OFICIO') {

    $body="
<div id='corpo'>
    <div class='row'>
        <div class='col-12'>
            <b>OFÍCIO Nº 999999/$ano/$siglaUnidade/INSS</b>, em $data<br>
        </div>
    </div>
    <div class='row'>    
        <div class='col-12' style='line-height: 120%;'>        
            <p>&nbsp;<p>
            Ao(à) Senhor(a)<br>
            <b>NOME DO DESTINATÁRIO</b><br>
            CARGO<br>
            ÓRGÃO<br>
            ENDEREÇO COMPLETO<br>
            CEP 99999-999 - CIDADE<br>
            <br>
            <b>Assunto:</b> Descrição do Assunto<br>
            <b>Interessado(a):</b> NOME DO INTERESSADO<br>
            <br>
            <br>        
            Prezado(a) Senhor(a),
            <br>
        </div>
    </div>
    <div class='row'>
        <div class='col-12' align='justify' style='line-height: 120%;'>$textoModelo</div>
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
            <b>NOME DO EMISSOR</b><br>
            Matrícula 9999999<br>
            FUNÇÃO DO EMISSOR
        </div>
    </div>
</div>
";

}

if($tipoModelo=='DESPACHO') {

    $body="
<div id='corpo'>
    <div class='row'>
        <div class='col-12'>
            <b>DESPACHO Nº 999999/$ano/$siglaUnidade/INSS</b>, em $data<br>
        </div>
    </div>
    <div class='row'>    
        <div class='col-12' style='line-height: 1.65; margin-left: 10cm;'>        
            <p>&nbsp;<p>
            <b>Ref.:</b> NB/PROTOCOLO/SIPPS<br>
            <b>Int.:</b> NOME DO INTERESSADO<br>
            <b>Ass.:</b> ASSUNTO<br>
        </div>
    </div>
    <div class='row'>
        <div class='col-12' align='justify' style='line-height: 120%;'>$textoModelo</div>
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
            <b>NOME DO EMISSOR</b><br>
            Matrícula 9999999<br>
            FUNÇÃO DO EMISSOR
        </div>
    </div>
</div>
";

}



//concatenando as variáveis

$html=$head.$style.$body.$footer;
*/

//inserindo o HTML que queremos converter

$dompdf->loadHtml($conteudo);

// Definindo o papel e a orientação

$dompdf->setPaper('A4', 'portrait');

// Renderizando o HTML como PDF

$dompdf->render();

// Enviando o PDF para o browser
$dompdf-> stream("MODELO-$tipoModelo.pdf", array("Attachment" => false));

//$dompdf->stream();