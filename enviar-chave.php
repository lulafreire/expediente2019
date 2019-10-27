<?php
include("conn.php");
include("functions.php");
require_once("vendor/autoload.php");
use \Lula\Mailer;

// Recupera dados do formulário
$codigo = $_GET['codigo'];

// Verifica se a unidade está cadastrada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codigo'");
$resUnidade = mysqli_num_rows($sqlUnidade);

if($resUnidade=='') {

    $msg = "Esta unidade não está cadastrada. Solicite o cadastro.";

} else {

    while($u=mysqli_fetch_array($sqlUnidade)) {

        $email = $u['email'];
        $nome  = utf8_encode($u['nome']);
        $chave = $u['chave'];

    }   

    $mailer = new Mailer($email, $nome, "Aplicativo Expediente - Chave de Acesso", $chave, 0);
    
    if($mailer->Send()) {

        Header("location:http://localhost/expediente2019/cadastrar-unidade.php?msg=1");
    
    } else {
    
        Header("location:http://localhost/expediente2019/cadastrar-unidade.php?erro=1");
    
    }
    

}



?>