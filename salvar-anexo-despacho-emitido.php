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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">

    <title>.:: Expediente ::.</title>
  </head>
  <body>
  
<?php
include_once("conn.php");
include_once("functions.php");

// Identifica o ofício
$id_despacho = $_POST['id_despacho'];
$descricao = utf8_decode($_POST['descricao']);

$anexo = $_FILES['arquivo'];

// Verifica o tamanho do arquivo
$t = $_FILES['arquivo']['size'];
$tMb = $t / 1048000;
$tamMb = round($tMb, 2);
$anexo = $_FILES['arquivo']['name'];

// Tamanho m�ximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 15; // 15Mb

// Caso o tamanho exceda 15Mb, grava apenas os dados do processo e exibe mensagem de erro para o anexo
if ($_UP['tamanho'] < $_FILES['arquivo']['size'] or $_FILES['arquivo']['size'] == 0) {

    $msg = "<font class='ver12'><i>O arquivo <b>$anexo</b> não foi enviado pois excede o tamanho máximo permitido de <b>15Mb</b></i><br>Você pode reenviar o anexo com o tamanho correto utilizando o menu \"Anexos\"</font>.";	   
            
    
} else {    

    // Pasta para onde devem ser enviados os anexos
    $_UP['pasta'] = 'anexos/';
    
    // Data completa, utilizada para renomear os arquivos
    $dataCompleta  = date("dmYHis");
    
    // Identifica a extens�o do arquivo
    $extensao_nome = $_FILES['arquivo']['name'];
    $extensao_array = explode('.', $extensao_nome);
    $extensao_end = $extensao_array[1];
    $extensao_min = strtolower($extensao_end);
    $extensao = $extensao_min;
    //$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
            
    // Move da pasta tempor�ria para a pasta ANEXOS
    $nome_final = $_FILES['arquivo']['name'];
    move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final);
    
    // Renomeia o arquivo para evitar quebra de link e nomes iguais
    $ext      = strtolower($extensao);
    $novoNome = "anexo_"."$dataCompleta.".$ext;

    rename ("anexos/".$nome_final,"anexos/".$novoNome);
    
    if($_POST['descricao']=='') {

        $descricao = "$nome_final";        

    }
    
    
    // Grava as informa��es no banco de dados ANEXOS
    $gravaAnexo = mysqli_query($conn,"insert into anexos (arquivo, tam, descricao, referencia, data) values ('$novoNome','$tamMb','$descricao','$id_despacho', curdate())");
    $gravaEventoAnexo = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'NOVO ANEXO ENVIADO','$id_despacho')");
            
}

header('location: anexar-despacho-emitido.php?id='.$id_despacho);


?>

<!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
  </body>
</html>