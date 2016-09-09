<?php

if(!session_id())
{
	session_start();
}

// Recebendo dados do POST
$dados = $_REQUEST;
$dados['Login'] = strtolower($dados['Login']);

if (!$dados['Login'] or !$dados['Senha'])
{
	if (!$dados['Login'])
	{
		echo '	<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
			<strong>Oops!</strong> Usuário é obrigatório!</div>';
		exit();
	}
	else
	{
		echo '	<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
			<strong>Oops!</strong> Senha é obrigatório!</div>';
		exit();
	}
}
else
{
	//inciando sessão
	ob_start();

	//Incluir arquivos de configuração e classe PDO
	include_once("config.php");

	// verificacao de ip na lista negra
	$lista_negra = $Conexao->query('SELECT * FROM "PACIENTE".cadope LIMIT 10');

	var_dump($lista_negra); exit;

	if ($lista_negra->rowCount() > 0)
	{
		$listar = $lista_negra->fetch(PDO::FETCH_OBJ);
		$sql = strtotime($listar->tempo);
		$banco = strtotime("+1 day", $sql);
		$php = strtotime("now");
		if ($banco < $php)
		{
			$deleta = $Conexao->query("DELETE FROM listanegra WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "'");
			echo '	<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<span class="icon_success"></span><strong>Parabéns!</strong> Acesso desbloqueado, acesse novamente! </div>';
		}
		else
		{
			echo '	<div class="alert alert-block">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Atenção!</strong> Seu acesso foi bloqueado! </div>';
		}
	}
	else
	{
		// Criptografando a senha
		$pass = senha($dados['Senha']);
		$senhaAberta = $dados['Senha'];

		$query = $Conexao->prepare("SELECT codope FROM cadope WHERE nomeope = :Login");
		$query->bindValue(':Login', $dados['Login'], PDO::PARAM_STR);
		$query->execute();

		if ($query->rowCount() > 0) // se existir o usuário cadastrado no banco
		{
			$senha = $Conexao->prepare("SELECT * FROM cadope WHERE nomeope = :Login AND senha = :Senha");
			$senha->bindValue(':Login', $dados['Login'], PDO::PARAM_STR);
			$senha->bindValue(':Senha', $pass, PDO::PARAM_STR);
			$senha->execute();

			if ($senha->rowCount() > 0) // se a senha bater com a senha cadastrada no banco
			{
				$dados = $senha->fetch(PDO::FETCH_ASSOC);

				// Criando Sessão com dados da Empresa
				$_SESSION['Empresa']['Dados']['ID'] = $dados['codope'];
				$_SESSION['Empresa']['Dados']['Login'] = $dados['nomeope'];
				$_SESSION['Empresa']['Dados']['Responsavel'] = $dados['username'];
				$_SESSION['Empresa']['Dados']['Senha'] = $dados['senha'];
				$_SESSION['Empresa']['Dados']['SenhaAberta'] = $senhaAberta;
				$_SESSION['Empresa']['Dados']['Titulo'] = $dados['username'];

				// verifica se a opcao de auto login foi marcada e se o cookie do mesmo nao existe (Apenas se vier por POST)
				if ($_SERVER['REQUEST_METHOD'] == "POST")
				{
					if (isset($_POST['Auto']) and !isset($_COOKIE['Auto']))
					{
						$tempo = time() + (3600 * 24 * 30);
						setcookie("autoLogin", $login . "&" . $pass, $tempo, "/");
					}
				}

				echo '	<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
					<span class="icon_success"></span><strong>Parabéns!</strong> Você está conectado! </div>';

				echo '<script type="text/javascript">document.location = "painel.php";</script>';
			}
			else // caso a senha for digitada errada
			{
				// verificando tentativas
				if (isset($_COOKIE['login']))
				{
					if ($_COOKIE['login'] < 4)
					{
						setcookie('login', ($_COOKIE['login'] + 1));

						echo '	<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
							<strong>Oops!</strong> Dados não conferem! Você tem mais ' . (4 - $_COOKIE['login']) . ' tentativas</div>';
					}
					else if ($_COOKIE['login'] >= 4)
					{
						setcookie("login", "", time() - 3600);

						echo '	<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
							<strong>Oops!</strong> Número de tentativas excedido. Volte em 24 horas.</div>';

						$Conexao->query("INSERT INTO listanegra(ip,tempo) VALUES('" . $_SERVER['REMOTE_ADDR'] . "',now())");
					}
				}
				else
				{
					setcookie('login', 1, time() + 3600);
					echo '	<div class="alert alert-block">
						<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
						<strong>Oops!</strong> Confira os dados por favor.</div>';
				}
			}
		}
		else // se não existir o usuário cadastrado no banco
		{
			echo '	<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert" data-original-title="">×</button>
				<strong>Oops!</strong> Usuário não encontrado!</div>';
		}
	}
	ob_end_flush();
}
?>
