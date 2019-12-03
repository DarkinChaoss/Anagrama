<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>comprovante empréstimo</title>
	</head>
	<body style="font-size: 10px; font-weight: bold; font-family: Arial; padding-left: 20px;">
		<?php
		$hosp = ClientesController::getCliente($_SESSION['usu_masterclient']);
		$sem = SolicitacoesEmprestimoController::getSolicitacaoEmprestimo($_REQUEST['id']);
		$set = SetoresController::getSetor($sem->sem_idsetor);
		// pega um item do empréstimo só para obter seu turno
			$iem = ItensEmprestimoController::getItens("", "iem_idsem = " . $_REQUEST['id']);
			$turno = $iem[0]->iem_turno;
		//
		$comp = $hosp->cli_nome
			. "<br><br>"
			. "COMPROVANTE DE EMPRÉSTIMO"
			. "<br><br>"
			. "DATA: " . DefaultHelper::converte_data($sem->sem_data) . " | TURNO: " . $turno
			. "<br>"
			. "SETOR: " . $set->set_nome
			. "<br>"
			. "SOLICITANTE: " . $sem->sem_nomesolicitante
			. "<br><br>"
			. "<table width='100%'>";
		foreach(ItensEmprestimoController::getItens("", "iem_idsem = " . $_REQUEST['id']) as $iem){
			$mai = MateriaisInternosController::getMaterialInterno($iem->iem_idmai);
			$comp .= "	<tr>
							<td>" . $mai->mai_cod . " - " . $mai->mai_nome . "</td>
							<td width='20%'>" . $iem->iem_qtdeentregue . "</td>
						</tr>";
		}
		$comp .= "</table>"
			. "<br><br>"
			. "<div style='border-top: 1px solid black; display: inline;'>" . $sem->sem_nomesolicitante . "</div>"
			. "<br><br>"
			. "<span style='font-size: 8px; font-weight: normal;'>- - - - - - - - - - - - CORTE AQUI - - - - - - - - - - - -</span>"
			. "<br><br>";
		echo stripslashes($comp . $comp);
		?>
	</body>
	<script type="text/javascript">
		window.print();
		setTimeout(function(){ window.close(); }, 500);
		$("body").keypress(function(e){
			/*
			 * Verifica se o evento é Keycode (IE e outros)
			 * Se não for, pega o evento Which (Firefox)
			*/
			var tecla = (e.keyCode?e.keyCode:e.which);
			if(tecla){
				window.close();
			}
		});
	</script>
</html>