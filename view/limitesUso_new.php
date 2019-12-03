<?php
	if (isset($_POST['descricao'])) {
		if(empty($_POST['id']))
			$res = LimitesUsoController::insert($_POST);
		else
			$res = LimitesUsoController::update($_POST);
		if($res)
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['delete'])){
		if(LimitesUsoController::delete($_GET['id']))
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

	<script src="js/limitesUso.js"></script>
	
	<h1>
		Limites de uso
		<small>Novo registro</small>
	</h1>
	
	<form id="formLimiteUso">
		<div style="display: flex; height: 62px">
			<input type="hidden" name="id" id="txId" class="input-mini" readonly>
			<div>
				<label>Descrição:</label>
				<input style="margin-right: 1em;" type="text" name="descricao" id="txDescricao" class="input" autofocus>
			</div>
			<div>
				<label>Quantidade:</label>
				<input style="width: 5em; margin-right: 1em;" type="text" name="quantidade" id="txQtde" class="input">
			</div>
			<div>
				<label>Período:</label>
				<select name='periodo' id='slMedida' class='input-medium'>
					<option value='DIAS'>DIAS</option>
					<option value='MESES'>MESES</option>
					<option value='ANOS'>ANOS</option>
				</select>
				<br>
				<br>
			</div>
		</div>
		<div>
			Ex. SMS 90 DIAS
		</div>
		<div style="margin-top: 2em;">
			<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
			<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
		</div>
		
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$limiteUso = LimitesUsoController::getLimiteUso($_GET['id']);
		echo LimitesUsoHelper::populaCampos($limiteUso);
	}
	
	include "helper/rodape.php";
?>