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

// Recupera dados do formulário
if(isset($_POST))
{    
    $id_oficio       = $_POST['id_oficio'];
    $numero          = $_POST['numero'];
    $contato         = utf8_decode($_POST['contato']);
    $orgao           = utf8_decode($_POST['orgao']);
    $assunto         = utf8_decode($_POST['assunto']);
    $interessado     = utf8_decode($_POST['interessado']);
    $texto           = utf8_decode($_POST['txtArtigo']);    
    $emissao         = converteData($_POST['emissao']);
    $recebido        = converteData($_POST['recebido']);   
    
}

// Verifica se foi definido um prazo de resposta
if($_POST['prazo'] == "") {

    $prazo = "0000-00-00";

} else {

    $prazo           = converteData($_POST['prazo']);

}

// Grava os dados do Ofício
$grava = mysqli_query($conn, "UPDATE documentos SET interessado = '$interessado', assunto = '$assunto', texto = '$texto', numero = '$numero', data = '$emissao', recebido = '$recebido', prazo = '$prazo' WHERE id='$id_oficio'");

// Grava o evento
$gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO EDITADO','$id_oficio')");

$msg = "<font class='ver12'><i>Não houve alteração no documento anexado.</i><br>Você pode editar posteriormente utilizando o menu \"<i class='fas fa-paperclip'></i> Anexos\"</font>.";	

$orgao_encode = utf8_encode($orgao);
$contato_encode = utf8_encode($contato);

echo "
<center>
    <div class='row'>
        <div class='col-2'>
        </div>
        <div class='alert alert-success col-8 mr-2' role='alert'>
            <h4 class='alert-heading'>Sucesso!</h4>
            <p>O Ofício nº <b>$numero</b> expedido por <b>$contato_encode</b> <i>($orgao_encode)</i><br>foi editado com sucesso.</p>
            <hr>
            <p class='mb-0'>$msg</p>
        </div>
        <div class='col-2'>
        </div>
    </div>
</center>";

?>

<!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
  </body>
</html>