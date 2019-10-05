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

if(isset($_POST))
{
    $data     = converteData($_POST['data']);
    $id       = $_POST['id'];
    $pagina   = $_POST['pagina'];
    $location = $_POST['location'];
}

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

// Verifica os dados do ofício selecionado
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE id = '$id'");
while($o=mysqli_fetch_array($sqlOficio)) {
    $numero     = $o['numero'];
    $dtEmissao  = converteData($data);
    $anoEmissao = anoEmissao($dtEmissao);
}

// Verifica se houve envio de anexo
$anexo = $_FILES['arquivo'];

if($_FILES['arquivo']['error']== 4) { // Nenhum anexo enviado, grava apenas os dados do ofício    

    // Grava os dados do novo Ofício
    $grava = mysqli_query($conn, "UPDATE documentos SET recebido = '$data' WHERE id = '$id'");

    // Grava o evento
    $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO RECEBIDO','$id')");
    
    $msg = "<font class='ver12'><i>O AR não foi anexado</i><br>Você pode enviar posteriormente utilizando o menu \"<i class='fas fa-paperclip'></i> Anexos\"</font>.";	
	

} else {

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
    
        // Grava os dados do novo Ofício
        $grava = mysqli_query($conn, "UPDATE documentos SET recebido = '$data' WHERE id = '$id'");

        // Grava o evento
        $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO RECEBIDO','$id')");
        
	    
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
        
        $descricao = "AR - OFICIO $numero/$anoEmissao/$siglaUnidade/INSS";
        
        // Grava no banco de dados        
        $grava = mysqli_query($conn, "UPDATE documentos SET recebido = '$data' WHERE id = '$id'");

        // Grava o evento
        $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO RECEBIDO','$id')");
        
        // Grava as informa��es no banco de dados ANEXOS
        $gravaAnexo = mysqli_query($conn,"insert into anexos (arquivo, tam, descricao, referencia, data) values ('$novoNome','$tamMb','$descricao','$id', curdate())");
                
        $msg = "O arquivo $anexo foi anexado e renomeado para \"<a href='download.php?url=$novoNome' class='linkcza'>$novoNome</a>\" com $tamMb Mb.";
        
    }


}

/*
echo "
<center>
    <div class='row'>
        <div class='col-2'>
        </div>
        <div class='alert alert-success col-8 mr-2' role='alert'>
            <h4 class='alert-heading'>Sucesso!</h4>
            <p>O Recebimento do Ofício foi Cadastrado com Sucesso!</p>
            <hr>
            <p class='mb-0'>$msg</p>
        </div>
        <div class='col-2'>
        </div>
    </div>
</center>";
*/

header("Location: $location.php?pagina=$pagina");

?>

<!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="node_modules/popper.js/dist/popper.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.js"></script>
    <script src="ckeditor/ckeditor.js"></script>
  </body>
</html>