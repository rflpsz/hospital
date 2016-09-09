<?php

if(isset($_REQUEST['pagina']))
{
	@$pagina = $_REQUEST['pagina'];
	switch($pagina)
	{
		case 'home': $pagina = 'destaque.php'; $aba= 'aba_home'; break;

		case 'empresa': $pagina = 'empresa.php'; $aba= 'aba_empresa'; break;
			case 'editEmpresa': $pagina = 'editEmpresa.php'; $aba= 'aba_empresa'; break;

		case 'arquivos': $pagina = 'arquivos.php'; $aba= 'aba_home'; break;
			case 'AddArquivo': $pagina = 'AddArquivo.php'; $aba= 'aba_home'; break;

		case 'variaveis': $pagina = 'variaveis.php'; $aba= 'aba_empresa'; break;
			case 'addVariavel': $pagina = 'addVariavel.php'; $aba= 'aba_empresa'; break;

		case 'clientes': $pagina = 'clientes.php'; $aba= 'aba_clientes'; break;		
			case 'addCliente': $pagina = 'addCliente.php'; $aba= 'aba_clientes'; break;
			case 'visCliente': $pagina = 'visCliente.php'; $aba= 'aba_clientes'; break;
			case 'editCliente': $pagina = 'editCliente.php'; $aba= 'aba_clientes'; break;
			case 'editCliente': $pagina = 'editCliente.php'; $aba= 'aba_clientes'; break;
			// if AdminID = 6
			case 'lembretes': $pagina = 'lembretes.php'; $aba= 'aba_clientes'; break;
				case 'addLembrete': $pagina = 'addLembrete.php'; $aba= 'aba_clientes'; break;
				case 'editLembrete': $pagina = 'editLembrete.php'; $aba= 'aba_clientes'; break;

		case 'caixa': $pagina = 'caixa.php'; $aba= 'aba_caixa'; break;
			case 'relatoriosCaixa': $pagina = 'relatoriosCaixa.php'; $aba= 'aba_caixa'; break;
			case 'addLancamento': $pagina = 'addLancamento.php'; $aba= 'aba_caixa'; break;

		case 'estoque': $pagina = 'estoque.php'; $aba= 'aba_estoque'; break;
			case 'addProduto': $pagina = 'addProduto.php'; $aba= 'aba_estoque'; break;
			case 'addServico': $pagina = 'addServico.php'; $aba= 'aba_estoque'; break;

		case 'categorias': $pagina = 'categorias.php'; $aba= 'aba_estoque'; break;
			case 'addCategoria': $pagina = 'addCategoria.php'; $aba= 'aba_estoque'; break;
			case 'editCategoria': $pagina = 'editCategoria.php'; $aba= 'aba_estoque'; break;

		case 'subCategorias': $pagina = 'subCategorias.php'; $aba= 'aba_estoque'; break;
			case 'addSubCategoria': $pagina = 'addSubCategoria.php'; $aba= 'aba_estoque'; break;
			case 'editSubCategoria': $pagina = 'editSubCategoria.php'; $aba= 'aba_estoque'; break;

		case 'fornecedores': $pagina = 'fornecedores.php'; $aba= 'aba_fornecedores'; break;
			case 'addFornecedor': $pagina = 'addFornecedor.php'; $aba= 'aba_fornecedores'; break;
			case 'visFornecedor': $pagina = 'visFornecedor.php'; $aba= 'aba_fornecedores'; break;
			case 'editFornecedor': $pagina = 'editFornecedor.php'; $aba= 'aba_fornecedores'; break;

		case 'orcamento': $pagina = 'orcamento.php'; $aba= 'aba_orcamento'; break;

		case 'dados': $pagina = 'dados.php'; $aba= 'aba_dados'; break;

		default: $pagina = '404.php'; $aba= 'aba_home'; break;
	}
}
else
{
	$pagina = "destaque.php"; $aba= 'aba_home';
}

?>