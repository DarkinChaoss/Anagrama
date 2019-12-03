<?php
	//error_log("- - - > LOGADO");
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 3, 4, 5));

	// verifica se tem id de saida caso não tenha volta para a conferencia
	if( !isset( $_GET['id'] ) ){
		header('Location: conferirComposto');
	}

	$produto = ProdutosController::getProduto( $_GET['id'] );

	$filhos = ProdutosCompostosController::getProdutosCompostos("pco_idpai = '{$_GET['id']}'" );
	$lista = null;

	if( !empty( $filhos ) ){

		foreach ($filhos as $filho) {
			$p = ProdutosController::getProdutos("pro_id = '{$filho->pco_idfilho}'");
			if( !empty( $p ) ){
				$lista[] = current( $p );
			}
		}
	}

	//conferir o id da solicitação
	$arrAdicionados = null;
	$itens = ItensSolicitacoesController::selectProdCompost($_GET['id']);
	$arr = array();

	foreach($itens as $itt){
		$itt->pro_id;
		$arr[] = $itt->pro_id;
	}
	
	$ids = implode(", ", $arr);
	if($ids){
		//verifica os que nao estão na solicitação
		$itensof = ItensSolicitacoesController::selectProdCompostOf($_GET['id'], $ids);
	}else{
		//verifica quando nenhum foi inserido na caixa que esta na solicitação
		$itensof = ItensSolicitacoesController::selectProdCompostoOfClean($_GET['id'], '');
	}
	

	$cont = count($itens);
	//print_r($itens);

	$wherecomposto = "pco_idpai = ".$_GET['id'];
	$compostos = ProdutosCompostosController::getCountFilhos($wherecomposto);
	
	$calcula = $compostos - $cont;

	include "helper/cabecalho.php";

?>
	<style>
	 .tdnot{
		background:#e54b4b !important;
		color:#fff;
	 }
	 
	 .tddescart{
		background:#E9967A !important;
		color:#fff;
	 }
	</style>
	<link rel="stylesheet" type="text/css" href="css/conferindoMaterial.css">

	<h1>
		<span>Conferindo Material </span>
		<span>
			<a href="conferirComposto" class="btn btn-default">
				<i class="icon-chevron-left"></i>
				Voltar
			</a>
		</span>

		<small>(ap&oacute;s a Esteriliza&ccedil;&atilde;o)</small>
	</h1>
	<div class="panel panel-default">
		<div class="panel-body">

			<input type="hidden" name="qtdMaterial" id="qtdMaterial" class="form-control" value="<?php echo $cont;?>">
			
			<h3>
				<span>Material: <?php echo "{$produto->pro_qrcode} - {$produto->pro_nome}"?> (<span id="qtdlist"><?php echo count($lista);?></span> Itens)</span> Status: <span id="autorizado" style="color:#00FF7F;"></span> <span id="naoautorizado" style="color:red;"></span>
				<input type="hidden" name="idproduto" id="txProdutoPaiid" value="<?php echo $produto->pro_id; ?>">
				<input type="hidden" name="nomeproduto" id="txProdutoPai" value="<?php echo $produto->pro_nome; ?>">
				<input type="hidden" name="qrocde" id="txQrcodePai" value="<?php echo $produto->pro_qrcode; ?>">
				<span class="pull-right">
					<button type="button" onclick="salvarConferencia()" id='btnSalvar' class="btn btn-success">
						<i class="icon-check"></i>
						<span>Salvar Confer&ecirc;ncia</span>
					</button>
					<br clear="all" >
					<a target="_blank" href='etiquetaAvulsa?qrCode=<?php echo $produto->pro_qrcode;?>' class="btn btn-default">
						<i class="icon-print"></i>
						<span>Gerar Etiqueta</span>
					</a>
					<br>
					<a href="conferirComposto" class="btn btn-danger">
						<i class="icon-chevron-left"></i>
						Sair
					</a>
					<br><br>
					<label class="checkbox pull-right">
						<input type="checkbox" name="descart" id="ckDescartados" value="S"> Mostrar produtos descartados
					</label>
				</span>
			</h3>	

			<br clear="all">

			<form id="formBuscaProduto">
				<span>
					<label>QRCode:</label>
					<input type="text" name="qrcode" id="txqrcode" class="input-medium" autofocus>
					<input type="hidden" name="id" id="idsearch" class="input-medium" autofocus>
					<button title="Buscar" type="submit" class="btn btn-primary" style="margin-top: -10px;">
						<i class="icon-search icon-white"></i>
					</button>					
				</span>
				<span id='avisoMateriais'>Informe os QRCode's dos materiais a serem conferidos</span>
			</form>

			<div >
			<?php
				$calcula;
				if($calcula != 0){
				?>
				<h4 class="prodesc" style="color:#E9967A !important">Produto(s) descartado(s), nesta composição.</h4>
				<?php
				}
				?>
			<table id='tableConferenciaNot' class="table table-hover table-striped">
				<thead>
					<tr>

					</tr>
				</thead>
				<tbody id="itensdescartados">
				  <?php
				  	foreach($itensof as $itof){
						if ($itof->pro_descarte != '') {

				  ?>
					<tr pro_qrcode='<?php echo $itof->pro_qrcode ?>' pro_id='<?php echo $itof->pro_id ?>' >
				
						<td nome_produto='1' colspan="2" class="tdnot tdmod"><?php echo $itof->pro_nome ?></td>
						<td colspan="2" class="tdnot tdmod">
							<p style="float:left; width:172px !important;"><?php echo $itof->pro_qrcode ?></p>

						</td>
					</tr>					
				  <?php
						}				  
					}
				  ?>
				</tbody>
				<tbody>
				  <tr>
					<td>
						<?php
							$calcula;
							if($calcula != 0){
							?>
							<h4  class="aviso" style="color:red !important;">Atenção: Existem <?php echo $calcula; ?> produtos desta composição, que não foram inseridos na solicitação: <a href="solicitacoes_new?populate=1&id=36" class="btn btn-default">Inserir estes produtos na solicitação</a></h4>				
							<?php
							}
							?>
					</td>
					<td><td>
				  </tr>
				  <?php
				  	foreach($itensof as $itof){
						if ($itof->pro_descarte != '*') {
				  ?>
					<tr pro_qrcode='<?php echo $itof->pro_qrcode ?>' pro_id='<?php echo $itof->pro_id ?>' >
						<input type="hidden" name="materiais_conf[]" value="<?php echo $itof->pro_id ?>">
						<input type="hidden" name="materiais_pendentes[]" id="mat_pendentes" value="<?php echo $itof->pro_id ?>">
						<td nome_produto='1' colspan="2" class="tdnot"><?php echo $itof->pro_nome ?></td>
						<td colspan="2" class="tdnot">
							<p style="float:left; width:172px !important;"><?php echo $itof->pro_qrcode ?></p>

						</td>
					</tr>					
				  <?php	
						}				  
					}
				  ?>
				</tbody>
			</table>
			<table id='tableConferencia' class="table table-hover table-striped">
					<thead>
						<tr>
							<th>Resumo da Confer&ecirc;ncia</th>
							<th id='qtdConferida'>0 item(ns) conferido(s)</th>
							<th id='qtdNaoConferida'>0 item(ns) &agrave; conferir</th>
						</tr>
					</thead>
					<tbody>
							<?php
		
	
							if( !empty( $lista ) ){
								?>
								<form id='frmMateriais'>
									<input type="hidden" name="idpai" value="<?php echo $produto->pro_id;?>">
									<input type="hidden" name="nome_pai" value="<?php echo $produto->pro_nome;?>">
								<?php
								foreach ($itens as $item) {
									echo $item->pro_descarte;
									if($item->pro_descarte != '*'){
										
							
								?>
									<tr pro_qrcode='<?php echo $item->pro_qrcode ?>' pro_id='<?php echo $item->pro_id ?>' >
										<input type="hidden" name="materiais_conf[]" value="<?php echo $item->pro_id ?>">
										<input type="hidden" name="materiais_pendentes[]" value="<?php echo $item->pro_id ?>">
										<td nome_produto='1' colspan="2"><?php echo $item->pro_nome ?></td>
										<td colspan="2">
											<p style="float:left; width:150px !important;"><?php echo $item->pro_qrcode ?></p>
                                            
											<span style="margin-left:10px">
                                                <button onclick="lancandoOcorrencia( <?php echo $item->pro_id ?> , '<?php echo $item->pro_qrcode ?>' )" type="button" class="btn btn-danger">
    												<i class='icon-trash'></i>
    												<span>Lan&ccedil;ar ocorr&ecirc;ncia</span>
    											</button>                                            
                                            </span> 
										</td>
									</tr>
								<?php
									}
								}
								?>
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

		<!-- Tela Permissao -->
	<form id="formPermissao">
		<div id="telaPermissao" class="modal hide fade" style="z-index:999999; position:absolute !important;" data-keyboard="false" data-backdrop="static">
			<div class="modal-header">
				<h3>Autorização</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 4050px;">
				<b>Solicite a autorização do responsável técnico do turno atual</b>
				<br><br>
				<label>
					Login:
					<input type="text" id="responsavel" class="input-large" autofocus>
				</label>
				<label>
					Senha:
					<input type="password" id="password" class="input-large" autofocus>
				</label>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" style="float:right;" id="autorizar"><i class="icon-ok icon-white"></i> Autorizar</a>
				<a href="#" class="btn btn-danger" style="float:right; margin-right:20px;" id="naoautorizar">Reter material</a>
				<a href="#" class="btn btn-warning" style="float:right; margin-right:20px;" id="voltarcaixa"><i class="icon-ok icon-white"></i> Voltar e inserir</a>
				<a href="#" class="btn" style="float:right; margin-right:20px;" id="voltar">Voltar</a>
			</div>
		</div>
	</form>	
	
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
								
								Deseja continuar e salvar a confer&ecirc;ncia mesmo com a divergência? <br>
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
	
	<!-- Tela Lançar Ocorrência -->
	<form id="formLancarOcorrencia">
		<div id="tlaLancarOcorrencia" class="modal hide fade">
			<div class="modal-header">
				<h3>Lançar Ocorrência</h3>
			</div>
			<div class="modal-body" style="width: 530px; height: 500px;">
				<input type="hidden" id="idProduto">
				<input type="hidden" id="QrcodeProduto">
				<h4 id="produtoAlvo"></h4>
				<h5 id="produtoPai"></h5>
				<div id="divOcorrenciasProduto" style="width: 100%; height: 150px; background: #dfdfdf; border: 1px solid #cbcbcb; overflow: auto;"></div>
				<br>
				<label>
					Nova ocorrência:
					<?php
					echo OcorrenciasHelper::populaComboOcorrencias();
					?>
				</label>
				<label>Descrição: <span id="descricaoOcorrencia"></span></label>
				<br>
				<label id="lbObs" class="hide">
					Obs.:<br>
					<input type="text" class="input-block-level" name="obs" id="txObs">
				</label>
				<label id="lbProduct" class="hide">
					Produto pai:<br>
					<input type="text" class="input-block-level" name="produtopai" readonly id="ProdutoPai">
					<input type="hidden" class="input-block-level" name="produtopaiid" id="ProdutoPaiId">
				</label>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" onclick="lancar()"><i class="icon-ok icon-white"></i> Confirmar</a>
				<a href="#" class="btn btn-danger" id="btCancelarOcorrencia" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
	</form>
	<script type="text/javascript" src='js/conferindoComposto.js'></script>
	<script type="text/javascript" src="js/ocorrencias.js"></script>	

<?php

	include "helper/rodape.php";
	/*
	 * Desenvolvido por Weslen Augusto Marconcin
	 *
	 * Brothers Soluções em T.I. © 2017
	*/