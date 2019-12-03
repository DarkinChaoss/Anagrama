<?php
	if (isset($_POST['nome']) && !isset($_POST['acao'])) {
		if(empty($_POST['id']))
			$res = TurnosTrabalhoController::insert($_POST);
		else
			$res = TurnosTrabalhoController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(TurnosTrabalhoController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if($_POST['acao'] == "repetido"){
		$res = TurnosTrabalhoController::getTurnosTrabalho("tur_nome = '" . utf8_decode($_POST['nome']) . "'");
		if($res)
			die("REPETIDO");
		else
			die("OK");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/turnosTrabalho.js"></script>
	
	<h1>
		Turnos de trabalho
		<small>Novo registro</small>
	</h1>
	
	<form id="formTurnoTrabalho">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
		<label>Início:</label>
		<input type="text" name="inicio" id="txInicio" maxlength="10" class="input-medium hora">
		<label>Fim:</label>
		<input type="text" name="fim" id="txFim" maxlength="10" class="input-medium hora">
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$turnoTrabalho = TurnosTrabalhoController::getTurnoTrabalho($_GET['id']);
		echo TurnosTrabalhoHelper::populaCampos($turnoTrabalho);
	}
	
	include "helper/rodape.php";
?>