<?php
	if (isset($_POST['nome']) && !isset($_POST['acao'])) {
		if(empty($_POST['id']))
			$res = MateriaisInternosController::insert($_POST);
		else
			$res = MateriaisInternosController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(MateriaisInternosController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if($_POST['acao'] == "repetido"){
		$res = MateriaisInternosController::getMateriaisInternos("mai_nome = '" . utf8_decode($_POST['nome']) . "' AND mai_id <> " . (($_POST['id'] == "") ? "0" : $_POST['id']));
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

	<script src="js/materiaisInternos.js"></script>
	
	<h1>
		Materiais internos
		<small>Novo registro</small>
	</h1>
	
	<form id="formMaterialInterno">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Código:</label>
		<input type="text" name="cod" id="txCod" maxlength="15" class="input-medium" autofocus>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge">
		<label>Qtde. em estoque:</label>
		<input type="text" name="qtde" id="txQtde" maxlength="5" class="input-small">
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$materialInterno = MateriaisInternosController::getMaterialInterno($_GET['id']);
		echo MateriaisInternosHelper::populaCampos($materialInterno);
	}
	
	include "helper/rodape.php";
?>