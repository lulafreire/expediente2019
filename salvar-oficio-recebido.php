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
    $contato         = utf8_decode(tratar_nome($_POST['contato']));
    $genero          = $_POST['genero'];   
    $interessado     = utf8_decode($_POST['interessado']);
    $id_contato      = $_POST['id_contato'];
    $assunto         = utf8_decode($_POST['assunto']);
    $texto           = utf8_decode($_POST['txtArtigo']);
    $cargo           = utf8_decode($_POST['cargo']);
    $orgao           = utf8_decode($_POST['orgao']);
    $endereco        = utf8_decode($_POST['endereco']);
    $cep             = $_POST['cep'];
    $cidade          = utf8_decode($_POST['cidade']);
    $numero          = $_POST['numero'];
    $emissao         = converteData($_POST['emissao']);
    $recebido        = converteData($_POST['recebido']);
    $anoEmissao      = anoEmissao($emissao);
    $resposta        = (int)$_POST['resposta'];   
    
}

// Verifica se já foi gravado o ofício anteriormente
$sqlOficio = mysqli_query($conn, "SELECT * FROM documentos WHERE numero = '$numero' AND emissor ='$id_contato' AND data='$emissao'");
$qtOficio  = mysqli_num_rows($sqlOficio);
if($qtOficio!='') {

    while($r=mysqli_fetch_array($sqlOficio)) {
        
        $idOficioRecebido = $r['id'];

    }

    echo "<script>
    alert('Já existe um ofício com este número, emitido pelo mesmo contato. Favor verificar.'); location= 'detalha-oficio-recebido.php?id=$idOficioRecebido';
    </script>";

    exit;

}

// Verifica se foi definido um prazo de resposta
if($_POST['prazo'] == "") {

    $prazo = "0000-00-00";

} else {

    $prazo           = converteData($_POST['prazo']);

}

// Verifica se houve envio de anexo
$anexo = $_FILES['arquivo'];

if($_FILES['arquivo']['error']== 4) { // Nenhum anexo enviado, grava apenas os dados do ofício

    // Verifica se o contato já está salvo ou precisa salvar
    $sqlDest = mysqli_query($conn, "SELECT * FROM contatos WHERE nome = '$contato' and orgao = '$orgao'");
    $resDest = mysqli_num_rows($sqlDest);
    if(!$resDest)
    {
        $gravaDest = mysqli_query($conn, "INSERT INTO contatos (genero, nome, cargo, orgao, endereco, cep, cidade, unidade) VALUES ('$genero','$contato', '$cargo', '$orgao', '$endereco', '$cep', '$cidade','$codUnidade')");
        $id_contato = mysqli_insert_id($conn);
    }

    // Grava os dados do novo Ofício
    $grava = mysqli_query($conn, "INSERT INTO documentos (emissor, interessado, assunto, texto, numero, data, recebido, prazo, tipo, unidade) VALUES ('$id_contato', '$interessado', '$assunto', '$texto', '$numero', '$emissao', '$recebido','$prazo', '1', '$codUnidade')");

    // Grava o evento
	$id = mysqli_insert_id($conn);
    $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO CADASTRADO','$id')");
    
    $msg = "<font class='ver12'><i>Nenhum documento anexado</i><br>Você pode enviar posteriormente utilizando o menu \"<i class='fas fa-paperclip'></i> Anexos\"</font>.";	
	

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
    
        // Grava no banco de dados
        // Verifica se o contato já está salvo ou precisa salvar
        $sqlDest = mysqli_query($conn, "SELECT * FROM contatos WHERE nome = '$contato' and orgao = '$orgao'");
        $resDest = mysqli_num_rows($sqlDest);
        if(!$resDest)
        {
            $gravaDest = mysqli_query($conn, "INSERT INTO contatos (genero, nome, cargo, orgao, endereco, cep, cidade, unidade) VALUES ('$genero','$contato', '$cargo', '$orgao', '$endereco', '$cep', '$cidade','$unidade')");
            $id_contato = mysqli_insert_id($conn);
        }

        // Grava os dados do novo Ofício
        $grava = mysqli_query($conn, "INSERT INTO documentos (emissor, interessado, assunto, texto, numero, data, recebido, prazo, tipo, unidade) VALUES ('$id_contato', '$interessado', '$assunto', '$texto', '$numero', '$emissao', '$recebido','$prazo', '1', '$codUnidade')");

        // Grava o evento
        $id = mysqli_insert_id($conn);
        $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO CADASTRADO','$id')");
        
	    
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
        
        $descricao = "OFICIO $numero/$anoEmissao - $orgao";
        
        // Grava no banco de dados
        // Verifica se o contato já está salvo ou precisa salvar
        $sqlDest = mysqli_query($conn, "SELECT * FROM contatos WHERE nome = '$contato' and orgao = '$orgao'");
        $resDest = mysqli_num_rows($sqlDest);
        if(!$resDest)
        {
            $gravaDest = mysqli_query($conn, "INSERT INTO contatos (genero, nome, cargo, orgao, endereco, cep, cidade, unidade) VALUES ('$genero','$contato', '$cargo', '$orgao', '$endereco', '$cep', '$cidade','$codUnidade')");
            $id_contato = mysqli_insert_id($conn);
        }

        // Grava os dados do novo Ofício
        $grava = mysqli_query($conn, "INSERT INTO documentos (emissor, interessado, assunto, texto, numero, data, recebido, prazo, tipo, unidade) VALUES ('$id_contato', '$interessado', '$assunto', '$texto', '$numero', '$emissao', '$recebido','$prazo', '1', '$codUnidade')");

        // Grava o evento Ofício Cadastrado
        $id = mysqli_insert_id($conn);
       
        $gravaEvento = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO CADASTRADO','$id')");
        
        // Grava as informa��es no banco de dados ANEXOS
        $gravaAnexo = mysqli_query($conn,"insert into anexos (arquivo, tam, descricao, referencia, data) values ('$novoNome','$tamMb','$descricao','$id', curdate())");
        $gravaEventoAnexo = mysqli_query($conn,"insert into eventos (data, descricao, referencia) values (now(),'OFICIO ANEXADO','$id')");
        
        $msg = "O arquivo $anexo foi anexado e renomeado para \"<a href='download.php?url=$novoNome' class='linkcza'>$novoNome</a>\" com $tamMb Mb.";
        
    }


}

// Caso tenha sido resposta a algum ofício
if($resposta > 0) {

    $n = explode("-", $resposta);
    $id_resposta=$n[0];
    $gravaResposta = mysqli_query($conn, "UPDATE documentos SET resposta = '$id' WHERE id = '$id_resposta'");

    if($id_resposta!='0') {

        $gravaEvento = mysqli_query($conn, "INSERT INTO eventos (data, descricao, referencia) VALUES (now(), 'OFICIO RESPONDIDO','$id_resposta')");
    }

}


$orgao_encode = utf8_encode($orgao);
$contato_encode = utf8_encode($contato);

echo "
<center>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-2'>
            </div>
                <div class='alert alert-success col-8 mr-2' role='alert'>
                    <h4 class='alert-heading'>Sucesso!</h4>
                    <p>O Ofício nº <b>$numero</b> expedido por <b>$contato_encode</b> <i>($orgao_encode)</i><br>foi cadastrado com sucesso.</p>
                    <hr>
                    <p class='mb-0'>$msg</p>
                </div>
            <div class='col-2'>
        </div>
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