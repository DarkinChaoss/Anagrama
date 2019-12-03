<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	if( isset( $_GET ) AND !empty( $_GET['idproduto'] ) ){

		$item_saida = new ItensSaidaModel();
		$ret = $item_saida->updateConfereSaidaProduto( array('conferente' => $_SESSION['usu_login'] ) , 
													"isa_idsaida={$_GET['idsaida']} AND isa_idproduto={$_GET['idproduto']}");

		header("Location: produtosAConferir");

	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));

	include "view/helper/cabecalho.php";

	$lista = null;
	if( isset( $_POST ) ){

		if( ( array_key_exists('prontuario', $_POST ) AND !empty( $_POST['prontuario'] ) ) OR 
			( array_key_exists('txQrcode', $_POST ) AND !empty( $_POST['txQrcode'] ) ) ){

			$relatorio = new ConferenciaMaterialModel();
			$lista = $relatorio->select( @$_POST['prontuario'] , @$_POST['txQrcode'] );

		}

	}

?>

	<h1>Confer&ecirc;ncia de Saida de Material</h1>

	<div class="pull-right">
		<a href="#" id="btPrint" class="btn btn-default hide"><i class="icon-print"></i> Imprimir</a>
	</div>		

	<form id='frmGerar' method="POST" class="form-horizontal" role="form">
	
		<div class="form-group">
			<label class="sr-only" for="">QRCode</label>
			<input type="text" class="form-control" value='<?php echo @$_POST['txQrcode']?>' name="txQrcode" id="txQrcode" placeholder="Informe o QRCode" autofocus>
		</div>
		
		<div class="form-group" style="margin-top: 10px;">
		
			<label>Prontu&aacute;rio / Paciente:</label>
			<input type="text" name="prontuario" value='<?php echo @$_POST['prontuario']?>' id="txProntuario" class="form-control" placeholder="Informe o Prontu&aacute;rio / Paciente" autocomplete="off" >
		
			<button title="Buscar" type="submit" class="btn btn-primary">
				<i class="icon-search icon-white"></i>
			</button>
			<button type='button' onclick="javascript:location.href='produtosAConferir'" class="btn btn-warning">
				<i class="icon-remove icon-white"></i>
			</button>			

		</div>

	</form>

	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressão -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>

		<div class='onlyPrint'>
			<div class='row-fluid'>
				<img src='img/tms.png' width='100px' class='pull-left'>
				<img src='img/<?php echo (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa")?>.png' width='120px' class='pull-right'>
			</div>
			<h4>Controle de Saídas de Materiais</h4>
		</div>

		<div class="row">

			<table class="table table-hover ">
				<thead>
					<tr>
						<th>Paciente</th>					
						<th>Produto</th>
						<th>QRCode</th>						
						<th>Data Saida</th>
						<th>Setor Solicitante</th>
						<th>Atrasado &agrave;</th>
						<th>A&ccedil;&atilde;o</th>
					</tr>
				</thead>
				<tbody>

					<?php
					if( !empty( $lista ) ){
						foreach ($lista as $l) {
							?>
							<tr>
								<td><?php echo $l->paciente?></td>					
								<td><?php echo $l->nome_produto?></td>
								<td><?php echo $l->qrcode?></td>						
								<td><?php echo $l->data_saida?></td>
								<td><?php echo $l->setor?></td>
								<td><?php echo $l->dias_pendente?></td>
								<td>
									<button onclick="abrirConferencia('<?php echo $l->nome_produto?>', '<?php echo $l->qrcode?>', '<?php echo $l->idproduto?>', '<?php echo $l->idsaida?>')" type="button" class="btn btn-success" title="Conferir" alt='Conferir'>
										<i class="icon-ok icon-white"></i>
									</button>
								</td>
							</tr>						
							<?php
						}
					}
					else{
						?>
						<tr><td colspan="7">O Material pesquisado n&atilde;o foi localizado.</td></tr>
						<?php
					}
					?>
				</tbody>
			</table>

		</div>

	</div>

	<div class="modal fade" id="mdl-conferir">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-center">Confirmar Confer&ecirc;ncia</h4>
				</div>
				<div class="modal-body">
					<h3>Confirmar a confer&ecirc;ncia do: <div id='avisoProduto' style='font-weight: bold; color:red;'></div></h3>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
						<i class='icon-remove'></i>
						<span>N&atilde;o, cancelar.</span>
					</button>
					<a href="#" class="btn btn-success pull-right">
						<i class="icon-check"></i>
						<span>Sim, conferir!</span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src='js/produtoAConferir.js'></script>

<?php

	include "view/helper/rodape.php";

	/*
	 * Desenvolvido por Weslen Augusto Marconcin
	 *
	 * Brothers Soluções em T.I. © 2017
	*/