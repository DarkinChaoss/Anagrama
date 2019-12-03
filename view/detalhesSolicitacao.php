<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 1));
	
	include "helper/cabecalho.php";
?>

	<script src="js/solicitacoes.js"></script>
	
	<h1>
		Detalhes da solicitação nº <?php echo $_REQUEST['id']; ?>
		<a href="minhasSolicitacoes" class="btn btn-danger pull-right" id="btVoltarMinhasSolicitacoes"><i class="icon-arrow-left icon-white"></i> Voltar</a>
		<a href="#" class="btn pull-right" style="margin-right: 10px;" id="btImprimirSolicitacao"><i class="icon-print"></i> Imprimir</a>
	</h1>
	<input type="hidden" name="id" id="txId" value="<?php echo $_REQUEST['id']; ?>"/>
	
	<div class="row-fluid">
		<div class="span5">
			<label>Data de entrada: <span id="txDataEntrada">-</span></label>
			<br>
			<label>Data de saída: <span id="txDataSaida">-</span></label>
		</div>
		<div class="span7" id="progressoSolicitacao" style="width: 495px;">
		</div>
	</div>
	<hr>
	<div class="row-fluid">
		<div class="span5">
			<h5>Materiais enviados: <span id="txQtdeMateriais"></span></h5>
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="50">Qtde.</th>
						<th>Descrição dos materiais</th>
					</tr>
				</thead>
				<tbody id="listaMateriais">
				</tbody>
			</table>
		</div>
		<div class="span1" style="text-align: center;">
			<br>
			<img src="img/setaDireita.png"/>
		</div>
		<div class="span6"  id="listaItens">
			<h4 style="border: none;">Solicitação ainda não conferida!</h4>
		</div>
	</div>
	
	<div class="hide" id="divPrint"></div>
	
<?php
	echo SolicitacoesHelper::populaDetalhes($_REQUEST['id']);
	
	include "helper/rodape.php";
?>