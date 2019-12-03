<?php
	if (isset($_POST['idproduto']) && !isset($_POST['idocorrencia'])) {
		$res = OcorrenciasProdutosController::insert($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}

	// monta a lista de ocorrências do produto aberto
	elseif ($_POST['acao'] == "ocorrenciasProduto"){
		die(OcorrenciasProdutosHelper::listaOcorrenciasByProduto($_POST['produto']));
	}

	// retorna descrição da ocorrência selecionada
	elseif (isset($_POST['ocorrencia']) && !isset($_POST['produto'])){
		die(OcorrenciasHelper::getDescricao($_POST['ocorrencia']));
	}

	// grava ocorrência para produto
	elseif (isset($_POST['idocorrencia']) && isset($_POST['idproduto'])){
	$res = OcorrenciasProdutosController::insert($_POST);
		if($res){
			$oco = OcorrenciasController::getOcorrencia($_POST['idocorrencia']);
			if($oco->oco_descarte == 'S')
				die($oco->oco_descarte);
			elseif($oco->oco_efeitoespecial == 'R')
				die($oco->oco_efeitoespecial);
			else
				die("");
		} else {
			die("ERRO");
		}
	}

	// busca produto para lançamento de ocorrência do lado cliente
	elseif ($_POST['acao'] == 'buscar' && isset($_POST['qrcode'])){
		$pro = ProdutosController::getProdutos("pro_qrcode = '" . $_POST['qrcode'] . "' ");
		if (!empty($pro)){
			$produto = new ProdutosModel();
			$produto = $pro[0];
			
			$nomepai = '';
			$idprod = '';
			$idpai = '';
			if($produto->pro_composto == 1){
				$nomepai = '';
				$idprod = '';
				$idpai = '';
			}else{
				$prodcomp = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = $produto->pro_id");
				$idprod = $prodcomp[0]->pco_idpai;
				$pp = ProdutosController::getProdutos("pro_id = '" . $idprod . "' ");
				$nomepai = $pp[0]->pro_nome;
				$idpai = $pp[0]->pro_id;
			}

			if($produto->pro_diametrointerno == "" && $produto->pro_curvatura == ""){
				$dadosProduto1 = "";
			} else {
				$dadosProduto1 = ", " . (($produto->pro_diametrointerno == "") ? "-" : $produto->pro_diametrointerno);
				$dadosProduto1 .= ", ";
				$dadosProduto1 .= (($produto->pro_curvatura == "") ? "-" : $produto->pro_curvatura);
			}
			if($produto->pro_comprimento == "" && $produto->pro_calibre == ""){
				$dadosProduto2 = "";
			} else {
				$dadosProduto2 = ", " . (($produto->pro_calibre == "") ? "-" : $produto->pro_calibre);
				$dadosProduto2 .= ", ";
				$dadosProduto2 .= (($produto->pro_comprimento == "") ? "-" : $produto->pro_comprimento);
			}
			die(stripslashes($produto->pro_id . "*;*" . $produto->pro_nome . $dadosProduto1 . $dadosProduto2 . "*;*" . $produto->pro_descarte . "*;*" . $produto->pro_composto . "*;*" . $nomepai . "*;*" . $idpai));
		} else {
			die("ERRO");
		}
	}

	// lança ocorrência de "perdido" para o produto
	elseif (isset($_GET['perdido'])){
		// recupera primeira ocorrência com efeito especial "P" que encontrar
		$ocos = OcorrenciasController::getOcorrenciasIndMasterclient("oco_efeitoespecial = 'P'");
		if ($ocos[0]->oco_id != "") {
			// grava ocorrência para o produto
			$_POST['idocorrencia'] = $ocos[0]->oco_id;
			$_POST['idproduto'] = $_GET['perdido'];
			$res = OcorrenciasProdutosController::insert($_POST);
			if ($res) {
				die("OK");
			} else {
				die("ERRO");
			}
		} else {
			die("OCO");
		}
	}
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2015
*/
?>