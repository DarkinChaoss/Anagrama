<?php
	// efetua devolu��o do material
	if ($_POST['acao'] == "devolver" && $_POST['idsaida'] != "" && $_POST['idproduto'] != "") {
		// volta status do produto para "pronto"
		$res = ProdutosController::setStatus($_POST['idproduto'], '1');
		if($res){
			// apaga registro de sa�da do material
			$res = ItensSaidaController::delete($_POST['idsaida']);
			if($res){
				die("OK");
			} else {
				die("ERRO");
			}
		} else {
			die("ERRO");
		}
	}

	// busca produto para devolu��o
	if ($_POST['acao'] == "buscar" && isset($_POST['qrcode']) && (!empty($_POST['qrcode']) || $_POST['qrcode'] != "" || $_POST['qrcode'] != " ")){
		$pro = ProdutosController::getProdutos("pro_qrcode = '".$_POST['qrcode']."'");
		if (!empty($pro)){


		} else {
			die("ERRO");
		}
	}

?>