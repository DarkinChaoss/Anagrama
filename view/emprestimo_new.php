<?php
	// busca empr�stimos em d�bito
	if ($_POST['acao'] == "buscar"){
		$buscar = $_POST['filtro'];
		$where = "";
		if($_POST['setor'] != 0)
			$where = "sem_idsetor = " . $_POST['setor'];
		die(ItensEmprestimoHelper::listaItensDebito($buscar, $where, $_POST['setor']));
	}
	
	// apaga solicita��o de empr�stimo cancelada e seus itens
	elseif ($_POST['acao'] == "cancelar"){
		SolicitacoesEmprestimoController::delete($_POST['id']);
	}
	
	// retorna nome do turno para ser usado em itens do empr�stimo
	elseif ($_POST['acao'] == "nomeTurno"){
		$agora = date("H:i:s");
		$res = TurnosTrabalhoController::getTurnosTrabalho("
					(tur_inicio < '" . $agora . "' AND tur_fim > '" . $agora . "')
					OR
					(tur_inicio < '" . $agora . "' AND tur_fim < '" . $agora . "' AND tur_fim < tur_inicio)
				");
		$tur = $res[0];
		echo $tur->tur_nome;
	}
	
	// salva empr�stimo
	elseif ($_POST['acao'] == "salvar"){
		if(empty($_POST['id']))
			$res = SolicitacoesEmprestimoController::insert($_POST);
		else
			$res = SolicitacoesEmprestimoController::update($_POST);
		if($res){
			$aux = SolicitacoesEmprestimoController::getSolicitacaoEmprestimo($res);
			echo $res . "*;*" . DefaultHelper::converte_data($aux->sem_data);
		} else {
			die("ERRO");
		}
	}
	
	// verifica se nome digitado existe e retorna dados do material
	elseif ($_POST['acao'] == "checkMaterial"){
		$res = MateriaisInternosController::getMateriaisInternos("mai_cod = '" . $_POST['nome'] . "' OR mai_nome = '" . utf8_decode($_POST['nome']) . "'");
		if($res){
			$mai = $res[0];
			echo utf8_encode($mai->mai_id . "*;*" . $mai->mai_cod . " - " . $mai->mai_nome);
		} else {
			echo "ERRO";
		}
	}
	
	// salva item no empr�stimo
	elseif ($_POST['acao'] == "salvarItem"){
		$res = ItensEmprestimoController::insert($_POST);
		if($res)
			echo $res;
		else
			die("ERRO");
	}
	
	// lista itens do empr�stimo
	elseif ($_POST['acao'] == "listaItens"){
		echo ItensEmprestimoHelper::listaItensNovoEmprestimo($_POST['id']);
	}
	
	// salva item no empr�stimo
	elseif ($_POST['acao'] == "removerItem"){
		$res = ItensEmprestimoController::delete($_POST['id']);
		if($res)
			echo $res;
		else
			die("ERRO");
	}
	
	// atualiza valores de item ao realizar devolu��o
	elseif ($_POST['acao'] == "devolverItem"){
		$res = ItensEmprestimoController::update($_POST);
		if($res)
			echo $res;
		else
			die("ERRO");
	}
?>