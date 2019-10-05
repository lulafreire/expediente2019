<?php
include("conn.php");
include("functions.php");

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

    // Envia email com uma nova chave    
    $mensagem	= "
    <h1><b>Caro Usuário,</b></h1>
    <p>Você está recebendo a nova Chave de Acesso da unidade <strong>$nome</strong> ao aplicativo Expediente: <strong>$chave</strong>. Acesse através do endereço http://localhost/expediente2019/
    ";

    // Variável que junta os valores acima e monta o corpo do email
    //$vai 		= "Nome: $nome\n\nE-mail: $email\n\nTelefone: $fone\n\nMensagem: $mensagem\n";

    require_once("node_modules/phpmailer/class.phpmailer.php");

    define('GUSER', 'luizalbertofreire@gmail.com');	// <-- Insira aqui o seu GMail
    define('GPWD', 'Beto.1998');		// <-- Insira aqui a senha do seu GMail

    function smtpmailer($para, $de, $de_nome, $assunto, $corpo) { 
        global $error;
        $mail = new PHPMailer();
        $mail->IsSMTP();		// Ativar SMTP
        $mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
        $mail->SMTPAuth = true;		// Autenticação ativada
        $mail->SMTPSecure = 'tls';	// SSL REQUERIDO pelo GMail
        $mail->Host = 'smtp.gmail.com';	// SMTP utilizado
        $mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor
        $mail->Username = GUSER;
        $mail->Password = GPWD;
        $mail->SetFrom($de, $de_nome);
        $mail->Subject = $assunto;
        $mail->Body = $corpo;
        $mail->AddAddress($para);
        if(!$mail->Send()) {
            $error = 'Mail error: '.$mail->ErrorInfo; 
            return false;
        } else {
            $error = 'Mensagem enviada!';
            return true;
        }
    }

    // Insira abaixo o email que irá receber a mensagem, o email que irá enviar (o mesmo da variável GUSER), o nome do email que envia a mensagem, o Assunto da mensagem e por último a variável com o corpo do email.

    if (smtpmailer($email, 'luizalbertofreire@gmail.com', 'Aplicativo Expediente', 'Aplicativo Expediente - Nova Chave de Acesso', utf8_decode($mensagem))) {

        Header("location:http://localhost/expediente2019/index.php?msg=3"); // Redireciona para uma página de obrigado.

    }
    if (!empty($error)) echo $error;

}



?>