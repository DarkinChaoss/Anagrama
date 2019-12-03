<?php
	if (isset($_POST['idpai']) && isset($_POST['method'])) {
		// carrega produto composto e sua lista
		$res = "";
		// dados do produto pai
		$pro = new ProdutosModel();
		$pro = ProdutosController::getProduto($_POST['idpai']);
		$res .= $pro->pro_id;
		$res .= "*;*";
		$res .= $pro->pro_qrcode;
		$res .= "*;*";
		$res .= $pro->pro_nome 
			. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "") 
			. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
			. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
			. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");
		$res .= "*;*";
		//$res .= $pro->pro_idsetor;
		// contagem de filhos
		$pco = ProdutosCompostosController::getProdutosCompostosInnerCount("pco_idpai = " . $pro->pro_id . " AND pro_descarte <> '*'");
		if($pco == 0)
			$res .= "VAZIO";
		else
			$res .= "" . $pco . (($pco == 1) ? " ITEM" : " ITENS");
		$res .= "*;*";
		// lista de produtos filhos
		$res .= ProdutosCompostosHelper::listaProdutosFilhosprint($_POST['idpai'], $_POST['modo'], $_POST['idses'], $_POST['idsol']);
		die($res);
	}	

	if (isset($_POST['idpai']) && !isset($_POST['acao']) && !isset($_POST['idfilho']) && !isset($_POST['method'])) {
		// carrega produto composto e sua lista
		$res = "";
		// dados do produto pai
		$pro = new ProdutosModel();
		$pro = ProdutosController::getProduto($_POST['idpai']);
		$res .= $pro->pro_id;
		$res .= "*;*";
		$res .= $pro->pro_qrcode;
		$res .= "*;*";
		$res .= $pro->pro_nome 
			. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "") 
			. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
			. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
			. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");
		$res .= "*;*";
		//$res .= $pro->pro_idsetor;
		// contagem de filhos
		$pco = ProdutosCompostosController::getProdutosCompostosInnerCount("pco_idpai = " . $pro->pro_id . " AND pro_descarte <> '*'");
		if($pco == 0)
			$res .= "VAZIO";
		else
			$res .= "" . $pco . (($pco == 1) ? " ITEM" : " ITENS");
		$res .= "*;*";
		// lista de produtos filhos
		$res .= ProdutosCompostosHelper::listaProdutosFilhos($_POST['idpai'], $_POST['modo'], $_POST['idses'], $_POST['idsol']);

		// método de esterilização em jogo e equipamento
		$iso = ItensSolicitacoesController::getItens("iso_idproduto = " . $_POST['idpai'], "iso_id DESC LIMIT 1");
		//print_r($iso);
		$count = ProdutosCompostosHelper::listaProdutosFilhosCount($_POST['idpai'], $_POST['modo'], $_POST['idses'], $_POST['idsol']);		
		
		$res .= "*;*" . $iso[0]->iso_idmetodo . "*;*" . $count . "*;*" . $iso[0]->iso_idequipamento . "*;*" . $iso[0]->iso_loteequipamento ;
		die($res);

	}
	
	elseif (isset($_GET['id'])) {
		// remove produto filho do produto pai
		die(ProdutosCompostosController::delete($_GET['id']));
	}
	
	elseif ($_POST['acao'] == "buscar") {
		// busca produto para adicionar como filho em produto composto
		$res = "";
		$pro = ProdutosController::getProdutos("pro_qrcode = '".$_POST['qrcode']."'");
		if (!empty($pro)){
			$produto = new ProdutosModel();
			$produto = $pro[0];
			/*
			// se produto não pertencer ao mesmo setor que o produto composto a ser adicionado
			if ($produto->pro_idsetor != $_POST["setor"]) {
				die("SETOR");
			}*/
			$pco = ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $_POST["idpai"] . " AND pco_idfilho = " . $produto->pro_id);
			// se produto já estiver incluso na composição do produto pai
			if (!empty($pco)) {
				die("REPETIDO");
			}
			$reproc = ItensSolicitacoesController::getReprocessamentoItem($produto->pro_id);
			//$setor = SetoresController::getSetor($produto->pro_idsetor);
			$GMaterial = GruposMateriaisController::getGrupoMateriais($produto->pro_idgrupomateriais);
			$res .= "	<input type='hidden' id='txIdProdutoAdicionar' value='" . $produto->pro_id . "'>
						<label>Produto: " 
							. $produto->pro_nome 
							. (($produto->pro_calibre != "") ? ", " . $produto->pro_calibre : "") 
							. (($produto->pro_curvatura != "") ? ", " . $produto->pro_curvatura : "")
							. (($produto->pro_comprimento != "") ? ", " . $produto->pro_comprimento : "")
							. (($produto->pro_diametrointerno != "") ? ", " . $produto->pro_diametrointerno : "") . 
						"</label><br>
						<!--label>Setor: " . /*$setor->set_nome .*/ "</label><br-->
						<label>Grupo do material: " . $GMaterial->gma_nome . "</label><br>
						<label>Reuso: " . $reproc . "/" . $produto->pro_maxqtdprocessamento . "</label><br>";
			die($res);
		} else {
			die("ERRO");
		}
	}
	
	elseif (isset($_POST['idpai']) && isset($_POST['idfilho'])) {
		// verifica se produto filho já não pertence a outro composto
		$pco = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = " . $_POST['idfilho']);
		if(count($pco) > 0){
			$pro = ProdutosController::getProduto($pco[0]->pco_idpai);
			die($pro->pro_nome . " ( " . $pro->pro_qrcode . " )");
		} else {
			// adiciona um produto filho a um produto composto
			$res = ProdutosCompostosController::insert($_POST);
			if($res)
				die("OK");
			else
				die("ERRO");
		}
	}
	
	elseif ($_POST['acao'] == "buscarFilho") {
		$res = ProdutosController::getProdutos("pro_qrcode = '" . $_POST['qrcode'] . "'");
		$pro = new ProdutosModel();
		$pro = $res[0];
		// se já existir uma leitura válida, começam as verificações
		if($pro->pro_id > 0){
			// verifica se produto lido pertence ao produto composto
			$res = ProdutosCompostosController::getProdutosCompostos("pco_idpai = " . $_POST['pai'] . " AND pco_idfilho = " . $pro->pro_id);
			if(!$res || empty($res[0])){
				$pco = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = " . $pro->pro_id);
				$pai = ProdutosController::getProduto($pco[0]->pco_idpai);
				die("NAOPERTENCE*;*" . $pai->pro_qrcode . " - " . $pai->pro_nome);
			}
			// verifica se produto já não foi lido e incluso na solicitação
			$res = ProdutosController::getProdutoInSolicitacao($_POST['qrcode'], $_POST['idses'], "0");
			if(!empty($res->pro_id)){
				die("JAFOI");
			}
			// retorna informações do produto filho para confirmação da leitura
			$reproc = ItensSolicitacoesController::getReprocessamentoItem($pro->pro_id) + 1;
			$descarte = OcorrenciasProdutosController::getDescarteByProduto($pro->pro_id);
			$res = $pro->pro_id;
			$res .= "*;*";
			$res .= $pro->pro_nome 
				. (($pro->pro_calibre != "") ? ", " . $pro->pro_calibre : "") 
				. (($pro->pro_curvatura != "") ? ", " . $pro->pro_curvatura : "")
				. (($pro->pro_comprimento != "") ? ", " . $pro->pro_comprimento : "")
				. (($pro->pro_diametrointerno != "") ? ", " . $pro->pro_diametrointerno : "");
			$res .= "*;*";
			$res .= $reproc;
			$res .= "*;*";
			$res .= $pro->pro_maxqtdprocessamento;
			$res .= "*;*";
			$res .= $descarte;
			die($res);
		} else {
			die("ERRO");
		}
	}
	
?>