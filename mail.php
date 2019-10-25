<?php

require_once("node_modules/phpmailer/class.phpmailer.php");

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
$Mailer->Body = 'Conteúdo';
$Mailer->AtlBody = 'Conteúdo em texto';
$Mailer->AddAddress('luiz.aoliveira@inss.gov.br');

if($Mailer->Send()) {

    echo "E-mail enviado com sucesso";

} else {

    echo "Erro no envio" .$Mailer->ErrorInfo;

}

?>