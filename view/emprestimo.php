<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 4, 5));
	
	include "helper/cabecalho.php";
?>
	
	<script src="js/emprestimo.js"></script>
	
	<form>
		<div class="form-search pull-right">
			<a href="#" class="hide" id="btLimparBusca">Limpar busca</a>&nbsp;
			<input type="text" class="input-medium search-query" name="buscar" id="txBuscar" autofocus>
			<button type="submit" class="btn" id="btBuscar"><i class="icon-search"></i></button>
		</div>
	
		<h1>Empréstimo de materiais internos</h1>
			
		<a href="#" id="btNovoEmprestimo" class="btn btn-primary pull-right hide">Novo empréstimo</a>
		<label>Setor:</label>
		<select name="setor" id="slSetor" class="input-large">
			<?php
			echo SetoresHelper::populaComboSetores($_REQUEST['setor']);
			?>
		</select>
	</form>
	
	<h4>
		Débitos atuais <span id="txNomeSetor"></span>
		<span class="pull-right">Total: <span id="txTotalDebitos">0</span></span>
	</h4>
	
	<table class="table table-hover">
		<thead>
			<tr>
				<th width="140">Data</th>
				<th width="50">Código</th>
				<th>Material</th>
				<th width="70" style="text-align: center;">Débito</th>
				<th width="70" style="text-align: center;">Entregue</th>
				<th width="70" style="text-align: center;">Dev. sujo</th>
				<th width="80" style="text-align: center;">Dev. s/uso</th>
				<th width="105"></th>
			</tr>
		</thead>
		<tbody id="listaItens">
			<?php
			echo ItensEmprestimoHelper::listaItensDebito(
					$_REQUEST['buscar'],
					(($_REQUEST['setor'] != "0" && $_REQUEST['setor'] != "") ? "sem_idsetor = " . $_REQUEST['setor'] : ""),
					$_REQUEST['setor']);
			?>
		</tbody>
	</table>
	
	<!-- Tela Novo Empréstimo -->
	<div id="telaNovoEmprestimo" class="modal hide fade">
		<div id="divNovoEmprestimo">
			<div class="modal-header">
				<h3>Novo Empréstimo</h3>
			</div>
			<div class="modal-body" style="width: 94%; height: 500px;">
				<input type="hidden" id="idEmprestimo">
				<div class="row-fluid">
					<div class="span6">
						<label>Setor: <span id="nomeSetor"></span></label>
						<br>
						<label><span id="dataEmprestimo"></span></label>
					</div>
					<div class="span6">
						<label>Nome do solicitante:</label>
						<input type="text" id="txNomeSolicitante" class="input-large"/>
					</div>
				</div>
				<br>
				<div id="divItensEmprestimo" style="width: 100%; height: 270px; background: #dfdfdf; border: 1px solid #cbcbcb; overflow: auto;"></div>
				<h4 class="pull-right" id="totalItensNovoEmprestimo" style="padding-bottom: 0px; margin-bottom: 0px;"></h4>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary pull-left" id="btAdicionarMaterial"><i class="icon-plus icon-white"></i> Adicionar material</a>
				<a href="#" class="btn btn-danger" id="btCancelarNovo"><i class="icon-remove icon-white"></i> Cancelar</a>
				<a href="#" class="btn btn-success" id="btSalvarNovo"><i class="icon-ok icon-white"></i> Salvar</a>
			</div>
		</div>
		<div id="divAdicionarMateriais" class="hide">
			<div class="modal-header">
				<h3>Adicionar Material</h3>
			</div>
			<div class="modal-body" style="width: 94%; height: 500px;">
				<label>Turno atual: <span id="nomeTurno"></span></label>
				<br>
				<label>Nome do material:</label>
				<input type="text" id="txNomeMaterial" class="input-large vaivolta" data-provide="typeahead">
				<br>
				<input type="hidden" id="idMaterialLido">
				<h4 id="nomeMaterialLido">&nbsp;</h4>
				<br>
				<label>Quantidade:</label>
				<input type="text" id="txQtde" class="input-mini vaivolta">
				<br><br><br>
				<div id="divMsg" class="hide alert alert-success">Material adicionado à lista com sucesso!</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" id="btSalvarMaterial"><i class="icon-plus-sign icon-white"></i> Adicionar</a>
				<a href="#" class="btn btn-danger" id="btCancelarMaterial"><i class="icon-remove icon-white"></i> Fechar</a>
				<!--a href="#" class="btn btn-success" id="btFinalizarMaterial"><i class="icon-ok icon-white"></i> Finalizar</a-->
			</div>
		</div>
	</div>
	
	<!-- Tela Devolução de Materiais -->
	<div id="telaDevolucaoMateriais" class="modal hide fade">
		<div class="modal-header">
			<a id="fechaTelaDevolucaoMateriais" class="close" data-dismiss="modal">X</a>
			<h3>Devolução de Materiais</h3>
		</div>
		<div class="modal-body" style="width: 94%; height: 160px;">
			<input type="hidden" id="idItemEmprestimo">
			<h4 id="nomeMaterialDevolucao">&nbsp;</h4>
			<br>
			<div class="row-fluid">
				<div class="span3">
					<label>Entregue:</label>
					<br>
					<label><h4 id="totalEntregue" style="margin-top: 7px; margin-left: 5px;">7</h4></label>
				</div>
				<div class="span3">
					<label>Dev. sujo:</label>
					<br>
					<input type="text" id="txDevSujo" class="input-mini pula"/>
					<input type="hidden" id="devSujo"/>
				</div>
				<div class="span3">
					<label>Dev. s/uso:</label>
					<br>
					<input type="text" id="txDevSemUso" class="input-mini pula"/>
					<input type="hidden" id="devSemUso"/>
				</div>
				<div class="span3">
					<label>Débito:</label>
					<br>
					<label><h5 id="totalDebito" style="color: red; margin-top: 7px; margin-left: 5px;">7</h5></label>
					<input type="hidden" id="devDebito"/>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn btn-danger" id="btCancelarDevolucao" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			<a href="#" class="btn btn-success pula" id="btSalvarDevolucao"><i class="icon-ok icon-white"></i> Salvar</a>
		</div>
	</div>
	
<?php
	if(isset($_REQUEST['buscar'])) {
		echo "	<script>
					$('#txBuscar').val('" . $_REQUEST['buscar'] . "');
					$('#btLimparBusca').show();
					" . (($_REQUEST['setor'] == 0) ? "" : "$('#btNovoEmprestimo').show();") . "
				</script>";
	}
	echo MateriaisInternosHelper::populaNomesMateriais();

	include "helper/rodape.php";
?>