<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5, 6, 7));

	// verifica se tem id de saida caso não tenha volta para a conferencia
	if( !isset( $_GET['idsaida'] ) ){
		header('Location: ConferenciaMaterial');
	}

	$prontuario = SaidaMateriaisController::getSaidaMateriais( $_GET['idsaida'] );
	$setor = null;
	if( !empty( $prontuario ) ){
		$setor = SetoresController::getSetor( $prontuario->sma_idsetor );
	}

	$item_saida = ItensSaidaController::getItensSaida( "isa_consignado <> 1 AND isa_idsaida = {$prontuario->sma_id} AND (isa_conferente is null OR isa_dataconferencia is null)" );
	
	$item_saida_consig = ItensSaidaController::getItensSaida( "isa_consignado = 1 AND isa_idsaida = {$prontuario->sma_id} AND (isa_conferente is null OR isa_dataconferencia is null)" );	
	// $json_produtos = ConferindoMaterialHelper::itensToJson( $item_saida );

	include "helper/cabecalho.php";

?>

	<link rel="stylesheet" type="text/css" href="css/conferindoMaterial.css">
		<style type="text/css">
		  .cs-tr td{
   			padding:0px !important;
   			height: 20px !important;
   			padding-left: 10px !important;
   			background-color: #ceeded !important;
		  }
		  .cs-tr td h4{
			margin: 5px 0 !important;
		  }

		/* navega?o interna */
		#cs-linksInternos{
			padding: 10px;
			z-index:999;
			position:fixed !important;
			right: 1%;
			bottom: 6%;
			display: flex;
			opacity: .8;
			display: '';
		}
		.cs-link{
			width:30px  !important;
			height:30px !important;
			background:#ffffff;
			margin: 5px;
			padding: 10px;
			-webkit-box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			-moz-box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			box-shadow: -1px 2px 5px 0px rgba(184,184,184,1);
			-webkit-border-radius: 100px;
			-moz-border-radius: 100px;
			border-radius: 100px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#cs-linksInternos a{
			display: block;
			text-decoration: none !important;
			color: #222;
			font-size: 1.5em;
		}
		
	
		
		</style>
	<h1>
		<span>Conferindo Material </span>
		<span>
			<a href="ConferenciaMaterial" class="btn btn-default">
				<i class="icon-chevron-left"></i>
				Voltar
			</a>
		</span>

		<small>(ap&oacute;s o uso e antes de encaminhar para a Esteriliza&ccedil;&atilde;o)</small>
	</h1>
	<div class="panel panel-default">
		<div class="panel-body">
			
			<h3>
				<span>Prontu&aacute;rio</span>
				<span class="pull-right">
					<button type="button" onclick="salvarConferencia()" id='btnSalvar' class="btn btn-success">
						<i class="icon-check"></i>
						<span>Salvar Confer&ecirc;ncia</span>
					</button>
				</span>
			</h3>	
			<div class='linhaProntuario'>
				<span class='bolder'>Paciente: </span>
				<span class='dadosPaciente'><?php echo $prontuario->sma_paciente;?></span>
			</div>
			<div class='linhaProntuario'>
				<span class='bolder'>Data: </span>
				<span class='dadosPaciente'><?php echo DefaultHelper::converte_data($prontuario->sma_ultimolancamento)?></span>
			</div>
			<div class='linhaProntuario'>
				<span class='bolder'>Setor: </span>
				<span class='dadosPaciente'><?php echo ( !empty( $setor ) ? $setor->set_nome : 'Setor não informado' );?></span>
			</div>
			<div class='linhaProntuario'>
				<span class='bolder'>Conferente: </span>
				<span class='dadosPaciente'><?php echo $_SESSION['usu_login']?></span>
			</div>

			<br clear="all">
			<form id="formBuscaProduto">
				<div class="btn-group" data-toggle="buttons-radio" style="margin-left: 0px; display: flex; width:270px;">	
					<label for="pn" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:48% !important; height:30px; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary active">
							<input checked id="pn" type="radio" name="optradio" value="pn" style="position: fixed; left: 120vw"> Produto Comun <span class="correct-pn"></span>
						</button>
					</label>
			
					<label for="pc" style="width:70%;">
						<span style="background: rgba(0,0,0,.0); width:60% !important; height:90%; display:block; position: absolute"></span>
						<button type="button" class="btn btn-primary">
							<input id="pc" type="radio" name="optradio" value="pc" style="position: fixed; left: 120vw "> Produto Consignado <span class="correct-pc"></span>
						</button>
					</label>
				</div>
				<span>
					<label>QRCode:</label>
					<input type="text" name="qrcode" id="txqrcode" class="input-medium" autofocus>
					<button title="Buscar" type="submit" class="btn btn-primary" style="margin-top: -10px;">
						<i class="icon-search icon-white"></i>
					</button>					
				</span>
				<span id='avisoMateriais'>Informe os QRCode's dos materiais a serem conferidos</span>
				<br>
				<span id="err" style="color: red; font-weight: bold; display: inline;"></span>
			</form>

			<div >
				<table id='tableConferencia' class="table table-hover table-striped">
					<thead>
						<tr>
							<th>Resumo da Confer&ecirc;ncia</th>
							<th id='qtdConferida'>0 item(ns) conferido(s)</th>
							<th id='qtdNaoConferida'>0 item(ns) &agrave; conferir</th>
						</tr>
					</thead>
					<tbody>
						<tr class="cs-tr">
							<td colspan="2"><h4 id="comuns">Produtos Comuns <i class="fas fa-tag cs-fa"></i></h4></td>
							<td colspan="">
								<span id="totalComum" class="totalComum" style="float:right !important; margin:5px !important;">
								</span>
							</td>
						</tr>
						<?php
							if( !empty( $item_saida ) ){
								?>
								<form id='frmMateriais'>
								
									<input type="hidden" name="idsaida" value="<?php echo $prontuario->sma_id;?>">
								<?php								
                                $contmaterial = 0;
								foreach ($item_saida as $key => $item) {
									$produto = ProdutosController::getProduto( $item->isa_idproduto );
	
	/*
									if($produto->pro_qtde > 0){
										$id = $produto->pro_id . '_';
										$name_materiais = 'materiais_pendentesQtde[]';								
									}else{
										$id = $produto->pro_id;
										$name_materiais = 'materiais_pendentes[]';	
									}
	*/
                                    if( !empty( $produto ) ){
										if($produto->pro_qtde <= 0 || $produto->pro_qtde == ''){
											$contmaterial++;
								    ?>
									<tr pro_id='<?php echo $produto->pro_id ?>'  class="qtdprods">
										<input type="text" name="materiais_pendentes[]" id="input<?php echo $produto->pro_id ?>"  value="<?php echo $produto->pro_id ?>" style="display:none;">								
										<td nome_produto='1' colspan="2"><?php echo $produto->pro_nome ?></td>
										<td><?php echo $produto->pro_qrcode?></td>
									</tr>
	                                <?php
										}
									}
								}

								foreach ($item_saida as $key => $item) {
									$produto = ProdutosController::getProduto( $item->isa_idproduto );
									
									if( !empty( $produto ) ){
										if($produto->pro_qtde > 0){
											$contmaterial++;
									?>
									<tr pro_idqtde='<?php echo $produto->pro_id . '_' ?>'  class="qtdprods">
										<input type="hidden" name="materiais_pendentesQtde[]" id="input<?php echo $produto->pro_id . '_'?>"  value="<?php echo $produto->pro_id . '_' ?>">								
										<td nome_produto='1' colspan="2"><?php echo $produto->pro_nome ?></td>
										<td><?php echo $produto->pro_qrcode?></td>
									</tr>
									<?php
										}
									}

								}
								
								?>
									<input type="hidden" name="qtde2" class="input-mini" id="qtde2"/>		
			                         <input type="hidden" name="qtde1" class="input-mini" id="qtde1">  
									 <input type="hidden" name="qtdMaterial" id="qtdMaterial" class="form-control" value="<?php echo $contmaterial ?>">                                
								<?php

							}
							else{
								?>
								<tr>
									<td colspan="3">
										<h4>Nenhum material a ser conferido</h4>
									</td>
								</tr>
								<?php
							}
						?>
					</tbody>
					<tbody>
						<tr class="cs-tr">
							<td colspan="2"><h4 id="consignados">Produtos Consignados <i class="fas fa-user-tag cs-fa"></h4></td>
							<td colspan=""><span id="totalConsignado" style="float:right !important; margin:5px !important;"></span></td>
						</tr>
						<?php
							if( !empty( $item_saida_consig ) ){
								?>

									<input type="hidden" name="idsaida" value="<?php echo $prontuario->sma_id;?>">
								<?php								
                                $contmaterial = 0;
								foreach ($item_saida_consig as $item) {
									$produto = ProdutosConsignadoController::getProdutoConsignado( $item->isa_idproduto );
						
                                    if( !empty( $produto ) ){
                                        $contmaterial++;
								    ?>
									<tr pro_id_consig='<?php echo $produto->pro_id . '_c' ?>' class="qtdprodsConsignado">
										<input type="hidden" name="materiais_pendentesConsignado[]" id="input<?php echo $produto->pro_id . '_c' ?>" value="<?php echo $produto->pro_id . '_c'?>">									
										<td nome_produto='1' colspan="2"><?php echo $produto->pro_nome ?></td>
										<td><?php echo $produto->pro_qrcode?></td>
									</tr>
	                                <?php
                                    }
								}
								?>
			                         <input type="hidden" name="qtdMaterialConsignado" id="qtdMaterialConsignado" class="form-control" value="<?php echo $contmaterial ?>">                                
								</form>
								<?php

							}
							else{
								?>
								<tr>
									<td colspan="3">
										<h4>Nenhum material a ser conferido</h4>
									</td>
								</tr>
								<?php
							}
						?>						
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- cleverson matias -> links de navega?o interna -->
	<div id="cs-linksInternos">
		<a id="cs-comuns" href="#comuns" title="Ir at?produtos comuns"><div class="cs-link"><i class="fas fa-tag"></i></div></a>
		<a id="cs-consignados" href="#consignados" title="Ir at?produtos consignados"><div class="cs-link"><i class="fas fa-user-tag"></i></div></a>
	</div>

	<div class="modal fade hide" id="telaProdQtde">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title text-center">Produto com quantidade</h3>
				</div>
				<div class="modal-body">
					<p><b>Qtde:</b> <input type="text" name="qtde" class="input-mini" id="qtde"/></p>
					<p><b>Qtde dispon�vel:</b> <input type="text" name="qtdeDisponivel" id="qtdeDisponivel" readonly class="input-mini"/></p>
					<input type="hidden" id="idprod"/>
				</div>
				<div class="modal-footer">
					<button onclick="removeMaterialListaComQuantidade()" type="button" class="btn btn-success pull-right">
						<i class="icon-ok"></i>
						<span>Conferir</span>						
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade hide" id="mdl-aviso-pendencia">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title text-center">Aten��o</h3>
				</div>
				<div class="modal-body">
					
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
							<h4>
								Existe(m) item(ns) &agrave; ser(em) conferido(s): 
								<div class='avisoQtdNaoConferida' id='avisoQtdNaoConferida'></div>
								
								Deseja continuar e salvar a confer&ecirc;ncia mesmo com a divergência? <br><br>

								P.S.: Caso este material seja encontrado dever&aacute; ser conferido no Relat&oacute;rio de Confer&ecirc;ncia ou dever&aacute; ser considerado o extravio do mesmo, reportando ao setor Respons&aacute;vel.
							</h4>							
						</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
						<i class="icon-remove"></i>
						<span>N&atilde;o, vou revisar</span>
					</button>
					<button onclick="enviarItensConferencia()" type="button" class="btn btn-success pull-right">
						<i class="icon-ok"></i>
						<span>Sim, continuar e salvar</span>						
					</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade hide" id="mdl-aviso-pendencia">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title text-center">Atenção</h3>
				</div>
				<div class="modal-body">
					
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
							<h4>
								Existe(m) item(ns) &agrave; ser(em) conferido(s): 
								<div class='avisoQtdNaoConferida' id='avisoQtdNaoConferida'></div>
								
								Deseja continuar e salvar a confer&ecirc;ncia mesmo com a divergência? <br><br>

								P.S.: Caso este material seja encontrado dever&aacute; ser conferido no Relat&oacute;rio de Confer&ecirc;ncia ou dever&aacute; ser considerado o extravio do mesmo, reportando ao setor Respons&aacute;vel.
							</h4>							
						</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
						<i class="icon-remove"></i>
						<span>N&atilde;o, vou revisar</span>
					</button>
					<button onclick="enviarItensConferencia()" type="button" class="btn btn-success pull-right">
						<i class="icon-ok"></i>
						<span>Sim, continuar e salvar</span>						
					</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src='js/conferindoMaterial.js'></script>

<?php

	include "helper/rodape.php";
	/*
	 * Desenvolvido por Weslen Augusto Marconcin
	 *
	 * Brothers Soluções em T.I. © 2017
	*/