<?php
	if (isset($_POST['nome'])) {
		if(empty($_POST['id']))
			$res = ResponsaveisTecnicosController::insert($_POST);
		else
			$res = ResponsaveisTecnicosController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(ResponsaveisTecnicosController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/responsaveisTecnicos.js"></script>
	
	<h1>
		Responsável técnico
		<small>Novo registro</small>
	</h1>
	
	<form id="formRTecnico">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Nome:</label>
		<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
		<br>
		<label>Contato:</label>
		<input type="text" name="contato" id="txContato" maxlength="50" class="input-xlarge">
		<br>
		<label>COREN:</label>
		<input type="text" name="coren" id="txCoren" maxlength="50" class="input-xlarge">
		<br>
		<br>
		<label class="checkbox">
			<input type="checkbox" name="admin" id="ckAdmin" value="1"> Somente administrador
		</label>
		<br>
		<label class="checkbox">
			<input type="checkbox" name="permissao" id="ckPermissao" value="1"> Permissão para liberar composição
		</label>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$RTecnico = ResponsaveisTecnicosController::getRTecnico($_GET['id']);
		echo ResponsaveisTecnicosHelper::populaCampos($RTecnico);
	}
	
	include "helper/rodape.php";
?>