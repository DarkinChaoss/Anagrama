<?php
	if (isset($_POST['login'])) {
		if(empty($_POST['id']))
			$res = UsuariosController::insert($_POST);
		else
			$res = UsuariosController::update($_POST);
		if(!$res)
			die("ERRO");
		else
			die($res);
	}
	
	if(isset($_GET['delete'])){
		if(UsuariosController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if(isset($_GET['comboRef'])){
		die(utf8_encode(UsuariosHelper::getComboRef($_GET['comboRef'])));
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>

	<script src="js/usuarios.js"></script>
	
	<h1>
		Usuários
		<small>Novo registro</small>
	</h1>
	
	<form id="formUsuario">
		<input type="hidden" name="id" id="txId" class="input-mini" readonly>
		<label>Login:</label>
		<input type="text" name="login" id="txLoginU" maxlength="20" class="input-large" autofocus>
		<br>
		<label>Senha:</label>
		<input type="password" name="senha" id="txSenhaU" maxlength="20" class="input-large">
		<br>
		<label>Nível de acesso:<?php echo $_POST['nivel']; ?></label>
		<select name="nivel" id="slNivel" class="input-large">
			<?php 
			echo "	<option value='-' " . ((!isset($_POST['nivel'])) ? "selected" : "") . ">** Escolha **</option>
					<option value='2' " . (($_POST['nivel'] == 2) ? "selected" : "") . ">CONFERENTE</option>
					<option value='3' " . (($_POST['nivel'] == 3) ? "selected" : "") . ">ETIQUETADOR</option>
					<option value='4' " . (($_POST['nivel'] == 4) ? "selected" : "") . ">ADMINISTRADOR</option>
					<option value='6' " . (($_POST['nivel'] == 6) ? "selected" : "") . ">ARSENAL</option>
					<option value='7' " . (($_POST['nivel'] == 7) ? "selected" : "") . ">CIRCULANTE</option>";
			?>
		</select>
		<br>
		<label>Referente à:</label>
		<select name="referencia" id="slReferencia" class="input-xlarge" disabled="disabled">
			<!-- conteúdo dinâmico -->
		</select>
		<br>
		<br>
		<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
		<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
	</form>
	
<?php
	if(isset($_GET['populate'])){
		$usuario = UsuariosController::getUsuario($_GET['id']);
		echo UsuariosHelper::populaCampos($usuario);
	}
	
	include "helper/rodape.php";
?>