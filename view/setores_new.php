<?php

	if (isset($_POST['action']) && $_POST['action'] == 'getCmeId') {
		die(SetoresController::getCmeId());
	}

	if (isset($_POST['nome'])) {
		if(empty($_POST['id'])) {
			$res = SetoresController::insert($_POST);
			if($res)
				die($res);
			else
				die("ERRO");
		} else {
			$res = SetoresController::update($_POST);
			if($res)
				die("OK");
			else
				die("ERRO");
		}
	}
	
	if(isset($_GET['delete'])){
		if(SetoresController::delete($_GET['id']))
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

	<script src="js/setores.js"></script>
	
	<h1>
		Setores
		<small>Novo registro</small>
	</h1>
	
	<form id="formSetor">
		<div class="row-fluid">
			<div class="span5">
				<input type="hidden" name="id" id="txId" class="input-mini" readonly>
				<label>Nome:</label>
				<input type="text" name="nome" id="txNome" maxlength="50" class="input-xlarge" autofocus>
				<br>
				<label>Solicita esterilização:</label>
				<select name="fazsolicitacao" id="slFazSolicitacao" class="input-medium">
					<option value="N">NÃO</option>
					<option value="S">SIM</option>
				</select>
				<br><br><br>
				<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
				<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
			<div class="span7">
				<label>Contato técnico:</label>
				<div id="contatosSetor">
					<!-- conteúdo dinâmico -->
				</div>
				<label class="contato" style="text-align: left;">
					<a href="#tlaAdicionarContato" id="btAdicionarContato" class="btn btn-primary" data-toggle="modal"><i class="icon-plus icon-white"></i></a>
				</label>
			</div>
		</div>
	</form>
	
	<!-- Tela Adicionar Contato -->
	<form id="formAdicionarContato">
		<div id="tlaAdicionarContato" class="modal hide fade">
			<div class="modal-header">
				<a id="fechaTelaAdicionarContato" class="close" data-dismiss="modal">X</a>
				<h3>Adicionar Contato</h3>
			</div>
			<div class="modal-body" id="divContato" style="width: 2000px; height: 210px;">
				<input type='hidden' name='id' id='txIdContato'>
				<label>Nome:</label>
				<input type="text" name="nome" id="txNomeContato" maxlength="50" class="input-xlarge" autofocus>
				<br>
				<label>E-mail:</label>
				<input type="text" name="email" id="txEmailContato" maxlength="50" class="input-xlarge">
				<br>
				<label>Telefones:</label>
				<input type="text" name="telefone" id="txTelefoneContato" maxlength="50" class="input-xlarge">
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" id="btSalvarContato"><i class="icon-ok icon-white"></i> Salvar</a>
				<a href="#" class="btn btn-danger" id="btCancelarContato" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$setor = SetoresController::getSetor($_GET['id']);
		echo SetoresHelper::populaCampos($setor);
	}
	
	include "helper/rodape.php";
?>

<script src="js/contatos.js"></script>
<script>
	listaContatos();
</script>