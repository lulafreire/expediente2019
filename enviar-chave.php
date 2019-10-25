<?php
include("conn.php");
include("functions.php");

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
    
    require_once("node_modules/phpmailer/class.phpmailer.php");

    $html = "
    <body topMargin='30'>
        <center>
        <img src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Coat_of_arms_of_the_United_States_of_Brazil.svg/766px-Coat_of_arms_of_the_United_States_of_Brazil.svg.png' height='100'><br>
        <strong><h2>Expediente2019</h2></strong><br>
        Prezado(a) Gestor(a) da <strong>$nome</strong>, bem-vindo(a)! 
        <p>Você está recebendo a chave de acesso ao aplicativo <strong>Expediente</strong>2019.
        <p>Utilize a chave <strong>".$chave. "</strong> para acessar no endereço abaixo.
        <p><strong>http://10.48.124.172/expediente2019/
        </center>
    </body>";

    $Mailer = new PHPMailer();
    $Mailer->IsSMTP();
    $Mailer->IsHTML(true);
    $Mailer->Charset = 'UTF-8';
    $Mailer->SMTPAuth = true;
    $Mailer->SMTPSecure = 'ssl';
    $Mailer->Host = 'smtp.gmail.com';
    $Mailer->Port = 465;
    $Mailer->Username = 'luizalbertofreire@gmail.com';
    $Mailer->Password = 'D@y.1985';
    $Mailer->From = 'luizalbertofreire@gmail.com';
    $Mailer->FromName = 'Expediente2019';
    $Mailer->Subject = 'Chave de Acesso';
    $Mailer->msgHTML($html);
    $Mailer->AtlBody = 'Conteúdo em texto';
    $Mailer->AddAddress($email);

    if($Mailer->Send()) {

        Header("location:http://localhost/expediente2019/cadastrar-unidade.php?msg=1");
    
    } else {
    
        Header("location:http://localhost/expediente2019/cadastrar-unidade.php?erro=1");
    
    }
    

}



?>