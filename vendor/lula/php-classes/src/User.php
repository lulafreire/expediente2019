<?php 
namespace Lula;
use \Lula\DB\Sql;
use \Lula\Mailer;
use \Lula\Model;

class User extends Model {
	const SESSION = "User";
	const SECRET = "HcodePhp7_Secret";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";
	
	public static function getFromSession()
	{
		$user = new User();
		if (isset($_SESSION[User::SESSION]) && (int)$_SESSION[User::SESSION]['iduser'] > 0) {
			$user->setData($_SESSION[User::SESSION]);
		}
		return $user;
	}

	public static function checkLogin($status = true)
	{
		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
		) {
			//Não está logado
			return false;
		} else {
			if ($status === true && (bool)$_SESSION[User::SESSION]['status'] === true) {
				return true;
			} else if ($status === false) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function trocaSenha($newPassword, $iduser)
	{	
		$password = password_hash($newPassword, PASSWORD_DEFAULT);
		
		$sql = new Sql();
		$sql->query("UPDATE tb_users SET password = :password WHERE iduser = :iduser", array(
			":password"=>$password,
			":iduser"=>$iduser
		));

		if(!$sql)
		{
			return $msg = "Não foi possível alterar a senha, tente novamente";
		}
		else
		{
			return $msg = "Senha alterada com sucesso!";
		}
	}

	public static function updateUser($iduser, $dados)
	{
		$sql = new Sql();
		$sql->query("UPDATE tb_users SET 
			nome = :nome,
			matricula = :matricula,
			lotacao = :lotacao,
			cargo = :cargo,
			cpf = :cpf,
			email = :email,
			foto = :foto,
			telefone = :telefone,
			dt_nascimento = :dt_nascimento,
			dt_ingresso = :dt_ingresso,
			endereco = :endereco,
			publicTelefone = :publicTelefone,
			publicDtNascimento = :publicDtNascimento WHERE iduser = :iduser", array(
				":iduser"=>$iduser,
				":nome" => strtoupper($dados['nome']),
				":matricula" => $dados['matricula'],
				":lotacao" => $dados['lotacao'],
				":cargo" => $dados['cargo'],
				":cpf" => $dados['cpf'],
				":email" => $dados['email'],
				":foto" => $dados['foto'],
				":telefone" => $dados['telefone'],
				":dt_nascimento" => $dados['dt_nascimento'],
				":dt_ingresso" => $dados['dt_ingresso'],
				":endereco" => $dados['endereco'],
				":publicTelefone" => $dados['publicTelefone'],
				":publicDtNascimento" => $dados['publicDtNascimento']
			));
		// Altera os dados na SESSION
		$_SESSION[User::SESSION] = NULL;
		$results = $sql->select("SELECT * FROM tb_users WHERE iduser = :iduser", array(
			":iduser"=>$iduser
		)); 
		$data = $results[0];
		$user = new User();
		$data['nome'] = utf8_encode($data['nome']);
		$user->setData($data);
		$_SESSION[User::SESSION] = $user->getValues();
				
	}

	public static function updateUsers($iduser, $dados)
	{
		$sql = new Sql();
		$sql->query("UPDATE tb_users SET 
			nome = :nome,
			matricula = :matricula,
			lotacao = :lotacao,
			cargo = :cargo,
			cpf = :cpf,
			email = :email,
			foto = :foto,
			equipe = :equipe,
			telefone = :telefone,
			dt_nascimento = :dt_nascimento,
			dt_ingresso = :dt_ingresso,
			endereco = :endereco,
			publicTelefone = :publicTelefone,
			status = :status,
			publicDtNascimento = :publicDtNascimento WHERE iduser = :iduser", array(
				":iduser"=>$iduser,
				":nome" => strtoupper($dados['nome']),
				":matricula" => $dados['matricula'],
				":lotacao" => $dados['lotacao'],
				":cargo" => $dados['cargo'],
				":cpf" => $dados['cpf'],
				":email" => $dados['email'],
				":foto" => $dados['foto'],
				":equipe"=>$dados['equipe'],
				":telefone" => $dados['telefone'],
				":dt_nascimento" => $dados['dt_nascimento'],
				":dt_ingresso" => $dados['dt_ingresso'],
				":endereco" => $dados['endereco'],
				":publicTelefone" => $dados['publicTelefone'],
				":status"=>$dados['status'],
				":publicDtNascimento" => $dados['publicDtNascimento']
			));				
	}

	public function deleteUsers($iduser)
	{
		$sql = new Sql();
		$sql->query("DELETE FROM tb_users WHERE iduser = :iduser", array(
			":iduser"=>$iduser
		));
	}

	public static function login($matricula, $password)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users WHERE matricula = :matricula", array(
			":matricula"=>$matricula
		)); 
		if (count($results) === 0)
		{
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
		$data = $results[0];
		if (password_verify($password, $data["password"]) === true)
		{
			$user = new User();
			$data['nome'] = utf8_encode($data['nome']);
			$user->setData($data);
			$_SESSION[User::SESSION] = $user->getValues();
			return $user;

		} else {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
	}

	public static function verificaStatus($iduser)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT status FROM tb_users WHERE iduser = :iduser", array(
			":iduser"=>$iduser
		));

		return $results[0]['status'];
	}

	public static function verificaUnidade($lotacao)
	{
		$sql = new Sql();
		$unidade = $sql->select("SELECT * FROM tb_unidades WHERE codigo = :lotacao", array(
			":lotacao"=>$lotacao
		));

		return(int)$unidade;
	}

	public static function nomeUnidade($userLotacao)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_unidades WHERE codigo = :userLotacao", array(
			":userLotacao"=>$userLotacao
		));
		
		return $nomeUnidade = $results[0]['nome'];

	}

	public static function getUsers($lotacao, $page = 1, $itensPerPage = 1)
    {
        $start = ($page - 1) * $itensPerPage;
        $sql = new Sql();
        $results = $sql->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_users WHERE lotacao = :lotacao ORDER BY nome ASC LIMIT $start, $itensPerPage", array(
            ":lotacao"=>$lotacao
        ));
        $total = $sql->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            "data"=>$results,
            "total"=>(int)$total[0]['nrtotal'],
            "pages"=>ceil($total[0]['nrtotal'] / $itensPerPage),
            "actual"=>$page
		];
    }

	public static function verificaUsuario($dados)
	{
		$matricula = $dados['matricula'];
		$sql = new Sql();
		$results = $sql->select("SELECT matricula FROM tb_users WHERE matricula = :matricula", array(
			":matricula"=>$matricula
		));

		$v = (int)$results;
	
		if($v==0)
		{
			$matricula = $dados['matricula'];
			$nome = strtoupper($dados['nome']);
			$cpf = $dados['cpf'];
			$foto = $dados['foto'];
			$cargo = $dados['cargo'];
			$email = $dados['email'];
			$lotacao = $dados['lotacao'];
			$dt_ingresso = $dados['dt_ingresso'];
			$telefone = $dados['telefone'];
			$dt_nascimento = $dados['dt_nascimento'];
			$nit = $dados['nit'];
			$equipe = $dados['equipe'];
			$endereco = strtoupper($dados['endereco']);
			$senha = $dados['senha'];
			$status = 2;
			$publicTelefone = $dados['publicTelefone'];
			$publicDtNascimento = $dados['publicDtNascimento'];		

			$cadastra = $sql->query("INSERT INTO tb_users 
				(nome, matricula, cargo, lotacao, dt_ingresso, email, telefone, endereco, cpf, dt_nascimento, nit, equipe, password, status, foto, publicTelefone, publicDtNascimento) 
				VALUES 
				(:nome, :matricula, :cargo, :lotacao, :dt_ingresso, :email, :telefone, :endereco, :cpf, :dt_nascimento, :nit, :equipe, :senha, :status, :foto, :publicTelefone, :publicDtNascimento)", array(
				":nome"=>$nome,
				":matricula"=>$matricula,
				":cargo"=>$cargo,
				":lotacao"=>$lotacao,
				":dt_ingresso"=>$dt_ingresso,
				":email"=>$email,
				":telefone"=>$telefone,
				":endereco"=>$endereco,
				":cpf"=>$cpf,
				":dt_nascimento"=>$dt_nascimento,
				":nit"=>$nit,
				":equipe"=>$equipe,
				":senha"=>$senha,
				":status"=>$status,
				":foto"=>$foto,
				":publicTelefone"=>$publicTelefone,
				":publicDtNascimento"=>$publicDtNascimento
			));

			$fav = $sql->query("INSERT INTO tb_favoritos (matricula) VALUES (:matricula)", array(
				":matricula"=>$matricula
			));

			return $v;

		}
		else
		{
			// Não cadastra
			return $v;
		}		

	}


	public static function verifyLogin($status = true)
	{
		if (!User::checkLogin($status)) {
			if ($status) {
				header("Location: /dashboard");
			} else {
				header("Location: /login");
			}
			exit;
		}
	}
	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}
	public static function listAll($userLotacao)
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_users WHERE lotacao = :userLotacao ORDER BY nome", array(
			":userLotacao"=>$userLotacao
		));
	}

	public static function favoritos()
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_favoritos WHERE matricula = :matricula", array(
			":matricula"=>$_SESSION[User::SESSION]['matricula']
		));
	}

	public static function selectFavoritos()
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_sistemas UNION ALL SELECT * FROM tb_formularios UNION ALL SELECT * FROM tb_links ORDER BY nome ASC");
	}

	public static function defineFavoritos($tb_origem, $idFav, $nFav, $nUrl, $nIcone, $nDesc, $nOrigem, $nID)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * from $tb_origem WHERE id = :idFav", array(
			":idFav"=>$idFav
		));
		
		$con=mysqli_connect("localhost","root","") or die ("Erro conexão".mysqli_error($con));
		$db=mysqli_select_db($con, "db_portal")or die ("Erro banco de dados".mysqli_error($con));
		$nomeFav   = utf8_decode($results[0]['nome']);
		$desc      = utf8_decode($results[0]['descricao']);
		$url       = $results[0]['url'];
		$icone     = $results[0]['icone'];
		$origem    = strtolower($results[0]['origem']);
		$idFav     = $results[0]['id'];
		$matricula = $_SESSION[User::SESSION]['matricula'];

		$update = mysqli_query($con, "UPDATE tb_favoritos set $nFav = '$nomeFav', $nDesc = '$desc', $nUrl = '$url', $nIcone = '$icone', $nOrigem = '$origem', $nID = '$idFav' WHERE matricula = '$matricula'");

	}

	

	public function save()
	{
		$sql = new Sql();
		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :status)", array(
			":desperson"=>utf8_decode($this->getdesperson()),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>User::getPasswordHash($this->getdespassword()),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":status"=>$this->getstatus()
		));
		$this->setData($results[0]);
	}
	public function get($iduser)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
			":iduser"=>$iduser
		));
		$data = $results[0];
		$data['desperson'] = utf8_encode($data['desperson']);
		$this->setData($data);
	}
	public function update()
	{
		$sql = new Sql();
		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :status)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>utf8_decode($this->getdesperson()),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>User::getPasswordHash($this->getdespassword()),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":status"=>$this->getstatus()
		));
		$this->setData($results[0]);		
	}
	public function delete()
	{
		$sql = new Sql();
		$sql->query("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()
		));
	}
	public static function getForgot($matricula)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users WHERE matricula = :matricula", array(
			":matricula"=>$matricula
		)); 
		
		if (count($results) === 0)
		{
			throw new \Exception("Não foi possível recuperar a senha.");
			
		}
		else
		{
			$data = $results[0];
			
			// Cria uma senha numérica provisória baseada na matrícula e na data e hora atual
			$date = date('Ymdhis');
			$code = $matricula.$date;
			$s = password_hash($code, PASSWORD_DEFAULT);
			$senhaProvisoria = substr(preg_replace("/[^0-9]/", "", $s), 3, 6);

			// Grava a senha criptografada no banco de dados
			$hash = password_hash($senhaProvisoria, PASSWORD_DEFAULT);

			$grava = $sql->select("UPDATE tb_users SET password = :hash WHERE matricula = :matricula", array(
				"hash"=>$hash,
				"matricula"=>$matricula
			));
		
			$mailer = new Mailer($data["email"], $data["nome"], "Redefinir Senha PortalAPS", "forgot-mail", $senhaProvisoria, array(
				"name"=>$data["nome"],
				"senhaProvisoria"=>$senhaProvisoria
			));
			$mailer->send();
		
			return $data;
	
		}
			
	}

	public static function sentPassword($email, $nome, $senha)
	{
		$mailer = new Mailer($email, $nome, "Cadastro no PortalAPS", "cadastro", $senha, array(
			"name"=>$nome,
			"senha"=>$senha
		));
		$mailer->send();
	}
	
	// Mensagens de erro ou sucesso
	public static function setError($msg)
	{
		$_SESSION[User::ERROR] = $msg;
	}
	public static function getError()
	{
		$msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';
		User::clearError();
		return $msg;
	}
	public static function clearError()
	{
		$_SESSION[User::ERROR] = NULL;
	}
	public static function setSuccess($msg)
	{
		$_SESSION[User::SUCCESS] = $msg;
	}
	public static function getSuccess()
	{
		$msg = (isset($_SESSION[User::SUCCESS]) && $_SESSION[User::SUCCESS]) ? $_SESSION[User::SUCCESS] : '';
		User::clearSuccess();
		return $msg;
	}
	public static function clearSuccess()
	{
		$_SESSION[User::SUCCESS] = NULL;
	}
	public static function setErrorRegister($msg)
	{
		$_SESSION[User::ERROR_REGISTER] = $msg;
	}
	public static function getErrorRegister()
	{
		$msg = (isset($_SESSION[User::ERROR_REGISTER]) && $_SESSION[User::ERROR_REGISTER]) ? $_SESSION[User::ERROR_REGISTER] : '';
		User::clearErrorRegister();
		return $msg;
	}
	public static function clearErrorRegister()
	{
		$_SESSION[User::ERROR_REGISTER] = NULL;
	}
	public static function checkLoginExist($login)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :deslogin", [
			':deslogin'=>$login
		]);
		return (count($results) > 0);
	}
	public static function getPasswordHash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT, [
			'cost'=>12
		]);
	}
	public function getOrders()
	{
		$sql = new Sql();
		$results = $sql->select("
			SELECT * 
			FROM tb_orders a 
			INNER JOIN tb_ordersstatus b USING(idstatus) 
			INNER JOIN tb_carts c USING(idcart)
			INNER JOIN tb_users d ON d.iduser = a.iduser
			INNER JOIN tb_addresses e USING(idaddress)
			INNER JOIN tb_persons f ON f.idperson = d.idperson
			WHERE a.iduser = :iduser
		", [
			':iduser'=>$this->getiduser()
		]);
		return $results;
	}
	public static function getPage($page = 1, $itemsPerPage = 10)
	{
		$start = ($page - 1) * $itemsPerPage;
		$sql = new Sql();
		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_users a 
			INNER JOIN tb_persons b USING(idperson) 
			ORDER BY b.desperson
			LIMIT $start, $itemsPerPage;
		");
		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");
		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}
	public static function getPageSearch($search, $page = 1, $itemsPerPage = 10)
	{
		$start = ($page - 1) * $itemsPerPage;
		$sql = new Sql();
		$results = $sql->select("
			SELECT SQL_CALC_FOUND_ROWS *
			FROM tb_users a 
			INNER JOIN tb_persons b USING(idperson)
			WHERE b.desperson LIKE :search OR b.desemail = :search OR a.deslogin LIKE :search
			ORDER BY b.desperson
			LIMIT $start, $itemsPerPage;
		", [
			':search'=>'%'.$search.'%'
		]);
		$resultTotal = $sql->select("SELECT FOUND_ROWS() AS nrtotal;");
		return [
			'data'=>$results,
			'total'=>(int)$resultTotal[0]["nrtotal"],
			'pages'=>ceil($resultTotal[0]["nrtotal"] / $itemsPerPage)
		];
	}

}
 ?>