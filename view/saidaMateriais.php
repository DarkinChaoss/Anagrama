<?php
	if(isset($_REQUEST['setor']) && $_REQUEST['setor'] != "0" && isset($_REQUEST['saidageral'])){
		//header("Location: saidaMateriais_new?prontuario=SG" . $_REQUEST['setor']);
		echo "	<script>
					location.href = 'saidaMateriais_new?prontuario=SG" . $_REQUEST['setor'] . "';
				</script>";
		exit;
	}
	
	elseif(isset($_REQUEST['prontuario']) && $_REQUEST['prontuario'] != ""){
		//header("Location: saidaMateriais_new?prontuario=" . $_REQUEST['prontuario']);
		echo "	<script>
					location.href = 'saidaMateriais_new?prontuario=" . $_REQUEST['prontuario'] . "';
				</script>";
		exit;
	}

	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	if ($_SESSION['usu_id'] != 34) { // usu?rio CC do Centrinho USP
		echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8));
	}
	
	include "helper/cabecalho.php";
?>

	<h1>Saída de Materiais</h1>
	
	<form id="formLancarSaida">
		<label>Prontuário:</label>
		<input type="text" name="prontuario" id="txProntuario" class="input-medium" autofocus autocomplete="off">
		<div class="pull-right">
			<?php echo SolicitacoesHelper::populaComboSetor(); ?>
			<button name="saidageral" class="btn" style="margin-top: -10px;">Saída geral</button>
		</div>
		<button class="btn btn-primary" style="margin-top: -10px;"><i class="icon-search icon-white"></i></button>
	</form>
	<br>
	<hr>
	<h4>Histórico</h4>
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="200">última alteração</th>
				<th width="150">Prontuário</th>
				<th>Paciente</th>
				<th width="30"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo SaidaMateriaisHelper::listaSaidas();
			?>
		</tbody>
	</table>
	
<?php
	include "helper/rodape.php";
?>