<?php
include("conn.php");
include("functions.php");
require_once("vendor/autoload.php");
use \Lula\Mailer;

// Recupera dados do formulário
$codigo = $_POST['codigo'];

// Verifica se a unidade está cadastrada
$sqlUnidade = mysqli_query($conn, "SELECT * FROM unidades WHERE cod = '$codigo'");
$resUnidade = mysqli_num_rows($sqlUnidade);

if($resUnidade=='') {

    $msg = "Esta unidade não está cadastrada. Solicite o cadastro.";

} else {

    while($u=mysqli_fetch_array($sqlUnidade)) {

        $email = $u['email'];
        $nome  = utf8_encode($u['nome']);

    }

    // Cria a nova chave pela combinação do código com a data
    $data = date('Ymdhis');
    $comb = $codigo.$data;
    $chave = substr(preg_replace("/[^0-9]/", "", md5($comb)), 0, 5);

    // Atualiza o banco de dados
    $grava = mysqli_query($conn, "UPDATE unidades SET chave = '$chave' WHERE cod = '$codigo'");

    $mailer = new Mailer($email, $nome, "Aplicativo Expediente - Recuperação da Chave de Acesso", $chave, 1);

    if($mailer->Send()) {

        Header("location:http://localhost/expediente2019/index.php?msg=3");
    
    } else {
    
        Header("location:http://localhost/expediente2019/index.php?erro=3");
    
    }
    

}



?>