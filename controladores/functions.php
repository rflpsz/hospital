<?php

function dataPT()
{
	$data = date('D');
	$mes = date('M');
	$dia = date('d');
	$ano = date('Y');

	$semana = array(
	'Sun' => 'Domingo', 
	'Mon' => 'Segunda-Feira',
	'Tue' => 'Terca-Feira',
	'Wed' => 'Quarta-Feira',
	'Thu' => 'Quinta-Feira',
	'Fri' => 'Sexta-Feira',
	'Sat' => 'Sábado'
	);

	$mes_extenso = array(
	'Jan' => 'Janeiro',
	'Feb' => 'Fevereiro',
	'Mar' => 'Marco',
	'Apr' => 'Abril',
	'May' => 'Maio',
	'Jun' => 'Junho',
	'Jul' => 'Julho',
	'Aug' => 'Agosto',
	'Nov' => 'Novembro',
	'Sep' => 'Setembro',
	'Oct' => 'Outubro',
	'Dec' => 'Dezembro'
	);

	echo $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
}

function banco($tabela,$order)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM ' . $tabela .' ORDER by ID '. $order;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function pegarIP()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
 
}

// Consulta de clientes ou fornecedores Tipo 1 = clientes, Tipo 2 = fornecedores
function consultaCliFor($tabela,$order,$AdminID,$Tipo)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM ' . $tabela .' WHERE AdminID = '.$AdminID.' AND Tipo = '.$Tipo.' ORDER by Titulo '. $order;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaCaixa($tabela,$order,$AdminID,$Lancamento)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = "SELECT c.*, a.Responsavel as Vendedor, IFNULL(p.Titulo, c.Titulo) as ItemNome FROM caixa c
			INNER JOIN admin a ON a.ID = c.FuncionarioID
			LEFT JOIN produtos p ON p.ID = c.Item
			WHERE c.AdminID = '".$AdminID."' AND c.Lancamento = '".$Lancamento."' ORDER by c.Data ". $order;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaProSer($AdminID,$Tipo)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = "SELECT * FROM produtos p WHERE p.AdminID = '".$AdminID."' AND p.Grupo = '".$Tipo."'";
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaEndClientes($tabela,$ID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM '.$tabela.' WHERE ClienteID = '.$ID;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaVariaveis($tabela,$Tipo,$ID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	if ($Tipo == 1)
	{
		$SQL = "	SELECT
					v.ID,
					v.Titulo,
					va.Titulo as Categoria
				FROM
					".$tabela." v
				INNER JOIN ".$tabela." va ON va.ID <> v.ID 
				WHERE v.AdminID = '".$ID."' AND v.Tipo = '".$Tipo."'
				group by v.Titulo
				ORDER by v.Titulo ASC";
	}
	else
	{
		$SQL = "SELECT * FROM ".$tabela." WHERE Tipo = '".$Tipo."' AND AdminID = ".$ID." ORDER by Titulo ASC";
	}
	
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consulta($tabela,$ID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM '.$tabela.' WHERE ID = '.$ID;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetch(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaCategoria($AdminID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM categorias WHERE AdminID = '.$AdminID;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaSubCategoria($CategoriaID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM subcategorias WHERE CategoriaID = '.$CategoriaID;
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function consultaProduto()
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT ID,Grupo,Titulo FROM produtos WHERE AdminID = 0 OR AdminID = '.$_SESSION['Empresa']['Dados']['AdminID'];
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

function geraTimestamp($data)
{
	$partes = explode('/', $data);
	return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function diferencaDatas($dataInicial,$dataFinal)
{
	// Usa a função criada e pega o timestamp das duas datas:
	$time_inicial = geraTimestamp($dataInicial);
	$time_final = geraTimestamp($dataFinal);

	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos

	// Calcula a diferença de dias
	$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

	// Exibe qtde de dias
	return $dias;
}

function consultaLembretes($tabela,$DonoID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = 'SELECT * FROM '.$tabela.' WHERE DonoID = '.$DonoID.' AND AdminID = '.$_SESSION['Empresa']['Dados']['AdminID'].' ORDER by ID DESC';
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	return $lista;
}

// Preencher select de Produtos
function listaVariaveis($tabela,$ID)
{
	include_once ("controladores/config.php");
	$Conexao = Conexao::getInstance();
	$SQL = "SELECT * FROM ".$tabela." WHERE Tipo = '".$ID."'";
	$dados = $Conexao->prepare($SQL);
	$dados->execute();
	$lista = $dados->fetchAll(PDO::FETCH_ASSOC);
	echo '<pre>';
	print_r($lista);
	echo '</pre>';
	return $lista;
}

function converteData($data)
{
	 return date('d/m/Y', strtotime($data));
}

function converteDataEN($data)
{
	$data = str_replace("/", "-", $data);
	return date('Y-m-d', strtotime($data));
}

function converteDateTimeEN($data)
{
	$data = str_replace("/", "-", $data);
	return date('Y-m-d H:i:s', strtotime($data));
}

function dataBanco($data, $converte)
{
	$data = str_replace(" 00:00:00", "", $data);


	if ($converte == "BR") {

		return implode("/", array_reverse(explode("-", $data)));
	} else {
		return implode("-", array_reverse(explode("/", $data)));
	}
}

function formata_data($dataa)

{

$banco =  explode(" ",$dataa);

$data_pt = implode("/", array_reverse(explode("-",$banco[0])));

$separa = explode("/",$data_pt);



// formata o campo mes

$mesnome['01'] = "Janeiro";

$mesnome['02'] = "Fevereiro";

$mesnome['03'] = "Março";

$mesnome['04'] = "Abril";

$mesnome['05'] = "Maio";

$mesnome['06'] = "Junho";

$mesnome['07'] = "Julho";

$mesnome['08'] = "Agosto";

$mesnome['09'] = "Setembro";

$mesnome[10] = "Outubro";

$mesnome[11] = "Novembro";

$mesnome[12] = "Dezembro";



$horario = explode(":",$banco[1]);





return array("ano" => $separa[2],"mes" => $separa[1], "mes_nome" => $mesnome[$separa[1]],"dia" => $separa[0],"hora" => $horario[0],"minuto" => $horario[1]);

}





function canonical($string) 
{		
	 $lixo = array
			(
				"á" => "a","à" => "a","ã" => "a","â" => "a",
				"Á" => "a","À" => "a","Ã" => "a","Â" => "a",
				"é" => "e","è" => "e","ê" => "e",
				"É" => "e","È" => "e","Ê" => "e",
				"í" => "i","ì" => "i","î" => "i",
				"Í" => "i","Ì" => "i","Î" => "i",
				"ó" => "o","ò" => "o","õ" => "o","ô" => "o",
				"Ó" => "o","Ò" => "o","Õ" => "o","Ô" => "o",
				"ú" => "u","ù" => "u","û" => "u",
				"Ú" => "u","Ù" => "u","Û" => "u",
				"ç" => "c","Ç" => "c",
				"ñ" => "n","Ñ" => "n",
				" " => "-","/" => "-"
			);

  return str_replace(array_keys($lixo), array_values($lixo), $string); 

}

function paginacao($tabela, $limite, $conexao, $pagina, $pg = 1, $where = NULL, $val_WHERE = NULL,$tipo_WHERE = '==', $oder = NULL, $val_ORDER = NULL)
{
	/*
	CHAMANDO A FUNCAO
	
	$tabela = Tabela de onde quer os dados
	$limite = Quantos registros quer por paginas
	$conexao = Passar a vairavel de conexao pra funcao
	$pagina = pagina AJAX que tem o conteudo (Ex: carregarEventos.php)
	$pg = 1
	$where = Campo do Where
	$val_WHERe = Valor que vai compara com o campo
	$tipo_WHERE = Like ou =
	$order = Campo que vai se ordenado
	$val_ORDER = se é DESC ASC RAND
	
	*/
	//iniciando variaveis de controle
	
	$sql_query 			= "";
	$sql_total 			= "";
	$retorno			= array();
	$paginacao_HTML		= "";
	$total_paginas		= 1;
	$retorno['erro']	= 0;
	$retorno['total_resultados'] = 0;
	
	define('ANTERIOR_INICIO','<li>');
	define('ANTERIOR_FIM','</li>');
	
	define('PROXIMO_INICIO','<li>');
	define('PROXIMO_FIM','</li>');
		
	define('ATUAL_INICIO','<li>');
	define('ATUAL_FIM','</li>');

	if(!is_numeric($pg))
	{
		$pg = 1;
	}
	
	$inicio = ($pg * $limite) - $limite;
	
	//inciando a query normal
	$sql_query .= "SELECT * FROM ".$tabela."";
	
	if($where != NULL and $val_WHERE != NULL)
		$sql_query .= " WHERE ".$where." ".$tipo_WHERE." '".$val_WHERE."'";
	
	if($oder != NULL and $val_ORDER != NULL)
		$sql_query .= " ORDER BY ".$oder." ".$val_ORDER;
	
	//SQL para pegar o total de registro do banco
	$sql_total = $sql_query;
	
	//SQL para paginacao
	$sql_query .= " LIMIT ".$inicio.", ".$limite;
	$query = $conexao->query($sql_query);
		
	//Executando a query para procura a quantidade de registro
	$total_reg = $conexao->query($sql_total);
	
	$total = $total_reg->rowCount();
	
	// se a query que esta pesquisando o total de registros nao encontra nenhuma linha retorna 1 para parar a funcao
	if($total < 1)
	{
		$retorno['erro'] = 1;
	}
	else
	{
		
		// retorna o total de linhas encontradas
		$retorno['total_resultados'] = $total;
		
		//retorna o conteudo da busca da query
		$retorno['resultado_query'] = $query;
			
		
		$controle_paginacao = 6;
		
		
		//Calculando pagina anterior
		$menos = $pg - 1;
		// Calculando pagina posterior
		$mais = $pg + 1;
		
		
		$pgs = ceil($total / $limite);
		if($pgs > 1 ) 
		{
			
			if (($pg-$controle_paginacao) < 1 )
			$anterior = 1;
			
			else 
			$anterior = $pg-$controle_paginacao;
			
			if (($pg+$controle_paginacao) > $pgs )
			$posterior = $pgs;
			else
			
			$posterior = $pg + $controle_paginacao;
			
			$var = (isset($_GET['ano'])) ? "&ano=".$_GET['ano'] : "";
			if($menos > 0) 
			{
				$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$menos.$var."');";
				$paginacao_HTML .= ANTERIOR_INICIO.'<a title="Página Anterior" href="'.$link.'">ANTERIOR</a>'.ANTERIOR_FIM;
			}
			
			
			for($i=$anterior;$i <= $posterior; $i++) 
			{
				if($pg == $i)
				{
					$paginacao_HTML .= ATUAL_INICIO."<li title='Página Atual' class='current'>".$i."</li>".ATUAL_FIM; // Escreve somente o número da página sem ação alguma
				}
				else
				{
					$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$i.$var."');";
					$paginacao_HTML .= '<li><a title="Página '.$i.'" href="'.$link.'">'.$i.'</a></li>';
				}
				
			}
			
			if($mais <= $pgs)
			{
				$link = "javascript:AjaxLink('carregar','".$pagina."?pag=".$mais.$var."');";
				$paginacao_HTML .= PROXIMO_INICIO.'<a title="Próxima Página" href="'.$link.'">PRÓXIMO</a>'.PROXIMO_FIM;
			}
			
			$retorno['html'] = $paginacao_HTML;
			$retorno['total_paginas'] = $total_paginas;
			
		}
		else
		{
			$retorno['html'] = "";
		}
	}
	
	return $retorno;
}

function encurta($texto,$limite)
{
	if(strlen($texto) > $limite)
	{
		if($texto[$limite] == " ")
		{
			return substr($texto,0,$limite);
		}
		else
		{
			return encurta($texto,($limite+1));
		}
	}
	else
	{
		return $texto;
	}
}

function removerTudo($rootDir)
{
	if (!is_dir($rootDir))
	{
		return false;
	}
	if (!preg_match("/\\/$/", $rootDir))
	{
		$rootDir .= '/';
	}

	$dh = opendir($rootDir);

	while (($file = readdir($dh)) !== false)
	{
		if ($file == '.'  ||  $file == '..')
		{
			continue;
		}
		if (is_dir($rootDir . $file))
		{
			removerTudo($rootDir . $file);
		}
		else if (is_file($rootDir . $file))
		{
			unlink($rootDir . $file);
		}
	}
	closedir($dh);
	rmdir($rootDir);
	return true;
}

?>