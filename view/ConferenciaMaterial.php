<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 6, 7, 8));

	include "helper/cabecalho.php";

	// somente os 10 ultimos

	$where = null;
	if(isset($_GET) AND isset( $_GET['prontuario'] ) AND !empty( $_GET['prontuario'] ) ) {
		$where = "( sma_prontuario LIKE '%{$_GET['prontuario']}%' OR 
					sma_paciente LIKE '%{$_GET['prontuario']}%' ) AND sma_conferido IS NULL";
	}
	else{
		$where = "sma_conferido IS NULL";
	}

	$prontuarios = SaidaMateriaisController::getSaidasMateriaisLimite( $where , '0,10');

?>

	<h1>Confer&ecirc;ncia de Material <small>(ap&oacute;s o uso e antes de encaminhar para a Esteriliza&ccedil;&atilde;o)</small></h1>

	<h4>
		<form id="formBuscaProntuario">
			<label>Prontu&aacute;rio ou Paciente:</label>
			<input type="text" name="prontuario" id="txProntuario" class="input-medium" autofocus autocomplete="off">
			<button title="Buscar" type="submit" class="btn btn-primary" style="margin-top: -10px;">
				<i class="icon-search icon-white"></i>
			</button>
			<button onclick="$('#formBuscaProntuario').reset();" class="btn btn-warning" style="margin-top: -10px;">
				<i class="icon-remove icon-white"></i>
			</button>
		</form>
	</h4>

	<div id="divConferencia">
		<table class="table table-hover table-striped col-md-12 col-lg-12 col-sm-12 col-xs-12">
			<thead>
				<tr>
					<th class="col-md-2 col-lg-2 col-sm-2 col-xs-2" >Prontu&aacute;rio</th>
					<th class="col-md-6 col-lg-6 col-sm-6 col-xs-6">Paciente</th>
					<th class="col-md-2 col-lg-2 col-sm-2 col-xs-2">Data</th>
					<th class="col-md-2 col-lg-2 col-sm-2 col-xs-2">A&ccedil;&otilde;es</th>
				</tr>
			</thead>
			<tbody id="listaItens">
				<?php 
					if( !empty( $prontuarios ) ){
						foreach ($prontuarios as $prontuario) {
							if($prontuario->sma_tiposaida == 'S'){
							?>
								<tr>
									<th><?php echo $prontuario->sma_prontuario;?></th>
									<th><?php echo $prontuario->sma_paciente;?></th>
									<th><?php echo DefaultHelper::converte_data($prontuario->sma_ultimolancamento)?></th>
									<th>
										<a href="conferindoMaterial?idsaida=<?php echo $prontuario->sma_id;?>" title="Conferir" alt='Conferir' class="btn btn-success">
											<i class="icon-check icon-white"></i>
										</a>
									</th>
								</tr>
							<?php
							}
						}
					}
					else{
					?>
						<tr>
							<td colspan="4">Nenhum Prontu&aacute;rio foi encontrado.</td>
						</tr>
					<?php						
					}
				?>
			</tbody>
		</table>
	</div>

<?php

	include "helper/rodape.php";
	/*
	 * Desenvolvido por Weslen Augusto Marconcin
	 *
	 * Brothers Soluções em T.I. © 2017
	*/