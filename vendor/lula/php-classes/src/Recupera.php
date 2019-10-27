<?php

namespace Lula;
use \Lula\Mailer;

Class Recupera {
	
	public static function sentPassword($email, $nome, $senha)
		{
			$mailer = new Mailer($email, $nome, "Cadastro no PortalAPS", "cadastro", $senha, '1', array(
				"name"=>$nome,
				"senha"=>$senha
			));
			$mailer->send();
		}

}

?>