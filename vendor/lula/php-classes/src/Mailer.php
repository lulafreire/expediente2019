<?php 
namespace Lula;
use Rain\Tpl;
class Mailer {
	
	const USERNAME = "***";
	const PASSWORD = "***";
	const NAME_FROM = "Aplicativo Expediente";
	private $mail;
	public function __construct($toAddress, $toName, $subject, $senhaProvisoria, $option)
	{
		/*
		$config = array(
			"tpl_dir"       => "views/email/",
			"cache_dir"     => "views-cache/",
			"debug"         => false
	    );
		Tpl::configure( $config );
		$tpl = new Tpl;
		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}
		$html = $tpl->draw($tplName, $data = array(), $returnHTML = true);
		*/

if($option== 0) {

$html = "
		<body topMargin='30'>
			<center>
			<img src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Coat_of_arms_of_the_United_States_of_Brazil.svg/766px-Coat_of_arms_of_the_United_States_of_Brazil.svg.png' height='100'><br>
			<strong><h2>Expediente2019</h2></strong><br>
			Prezado(a), Gestor(a) do(a) ".$toName. ",<br>
			Sua Unidade está cadastrada no aplicativo Expediente versão 2019.<br>
			Utilize a chave <strong>".$senhaProvisoria. "</strong> para acessar no endereço abaixo.
			<p><strong>http://10.48.124.172/expediente2019/
			</center>
		</body>";

} else {

	$html = "
		<body topMargin='30'>
			<center>
			<img src='https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Coat_of_arms_of_the_United_States_of_Brazil.svg/766px-Coat_of_arms_of_the_United_States_of_Brazil.svg.png' height='100'><br>
			<strong><h2>Expediente2019</h2></strong><br>
			Olá, Gestor d(o)a ".$toName. ",<br>
			Estamos enviando uma nova chave de acesso ao aplicativo Expediente versão 2019.<br>
			Utilize a chave <strong>".$senhaProvisoria. "</strong> para acessar no endereço abaixo.
			<p><strong>http://10.48.124.172/expediente2019/
			</center>
		</body>";
}
		
		$this->mail = new \PHPMailer;
		//Tell PHPMailer to use SMTP
		$this->mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$this->mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$this->mail->Host = 'smtp.gmail.com';
		$this->mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) );
		// use
		// $this->mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$this->mail->Port = 465;
		//Set the encryption system to use - ssl (deprecated) or tls
		$this->mail->SMTPSecure = 'ssl';
		//Whether to use SMTP authentication
		$this->mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$this->mail->Username = Mailer::USERNAME;
		//Password to use for SMTP authentication
		$this->mail->Password = Mailer::PASSWORD;
		//Set who the message is to be sent from
		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
		//Set an alternative reply-to address
		//$this->mail->addReplyTo('replyto@example.com', 'First Last');
		//Set who the message is to be sent to
		$this->mail->addAddress($toAddress, $toName);
		//Set the subject line
		$this->mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->mail->msgHTML($html);
		//Replace the plain text body with one created manually
		$this->mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');
	}
	public function send()
	{
		return $this->mail->send();
	}
}
 ?>