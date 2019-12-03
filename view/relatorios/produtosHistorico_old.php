<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8, 9));

	include "view/helper/cabecalho.php";
?>


	<script type="text/javascript">

		$(document).ready(function(){

			var url_atual = window.location.href;

			console.log(url_atual);			
				

			//$('.correct-pn').html('<i class="fas fa-check"></i>')

			$("input[name='chProduto']").live("click", function(){
			var chProduto = $("input[name='chProduto']:checked").val()
			if(chProduto == 'pn'){
			$('.correct-pn').html('<i class="fas fa-check"></i>')
			$('.correct-pc').html('')


			}else if(chProduto == 'pc'){
			$('.correct-pc').html('<i class="fas fa-check"></i>')
			$('.correct-pn').html('')			
			}	
			});

		});


	</script>


	<h1>Histórico do produto</h1>
	<form>

		<div class="btn-group" data-toggle="buttons-radio" style="margin-top:px; display: flex; width:270px;">
			
				<label for="pn" style="width:70%; margin-right: 10px">
					<span style="background: rgba(0,0,0,.0); width:78% !important; height:30px; display:block; position: absolute"></span>
					<a class="btn btn-primary  hover-button" id="bt-pn">
						<input checked id="pn" type="radio" name="chProduto" value="pn" style="position: absolute; left: 0vw; z-index:-9999;"> Produto  Comun <span class="correct-pn"></span>
					</a>
				</label>
		
				<label for="pc" style="width:70%;">
					<span style="background: rgba(0,0,0,.0); width:60% !important; height:90%; display:block; position: absolute"></span>
					<a class="btn btn-primary hover-button"  id="bt-pc">
						<input id="pc" type="radio" name="chProduto" value="pc" style="position: absolute; left: 0vw; z-index:-9999;"> Produto Consignado <span class="correct-pc"></span>
					</a>
				</label>

		</div>

		<div class="row-fluid">
			<div class="span4">
				<label>QRCode:</label>
				<input type="text" class="input-large" name="filtro" autofocus autocomplete="off">
			</div>

			<div class="pull-right">
				<button class="btn btn-primary" name="gerar" value="1"><i class="icon-file icon-white"></i> Gerar relatório</button>
				<a href="#" id="btPrint" class="btn hide"><i class="icon-print"></i> Imprimir</a>
			</div>
		</div>
	</form>

	<hr>

	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressão -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		if($_REQUEST['gerar'] == 1){

			if($_REQUEST['chProduto'] == 'pn'){
					// busca produto
				$res = ProdutosController::getProdutos("pro_qrcode = '" . $_REQUEST['filtro'] . "'");
				$pro = new ProdutosModel();
				$pro = $res[0];

				$qrcodenew = explode(".", $_REQUEST['filtro']);
				
				$prodcomp = ProdutosCompostosController::getProdutosCompostos("pco_idfilho = $pro->pro_id");
				$idprod = $prodcomp[0]->pco_idpai;
				$pp = ProdutosController::getProdutos("pro_id = '" . $idprod . "' ");			
				$nomepai = $pp[0]->pro_nome == '' ? 'Não faz parte de nenhuma composição' : $pp[0]->pro_nome;
				$qrcode = $pp[0]->pro_qrcode == '' ? '' : $pp[0]->pro_qrcode;
				//mexer aqui para buscar o pai deste produto.

				if(!empty($pro->pro_id)){
					$html = "	<script>
									$('#btPrint').show();
								</script>";
					$html .= "	<div class='onlyPrint'>
									<div class='row-fluid'>
										<img src='img/tms.png' width='100px' class='pull-left'>
										<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
									</div>
	                                <h4>Histórico do produto</h4>
								</div>
								";

					// verifica se possui imagem de rótulo salva
					$arquivo = "img/rotulos/" . $pro->pro_id . ".jpg";
					if(is_file($arquivo)){
					    $html .='
	        		    <label>
	                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#telaRotulo">
			                      <i class="icon-camera icon-white"></i> Rótulo
			                </a>
	                        <div id="telaRotulo" class="modal hide fade" style="width:350px; margin-left:-175px;">
	                		    <div class="modal-header">
	                				<h3>Rótulo do Produto</h3>
	                			</div>
	                            <img width="350" src="' . $arquivo .'">
	            			    <div class="modal-footer">
	                				<a href="#" class="btn" id="btFecharRotulo" data-dismiss="modal">Fechar</a>
	                			</div>
	    		            </div>
				     </label>
	    		        ';
					}


					$gma = GruposMateriaisController::getGrupoMateriais($pro->pro_idgrupomateriais);
					$cli = ClientesController::getCliente($pro->pro_idcliente);
					$usu = UsuariosController::getUsuario($pro->pro_idusuario);
					if(count($qrcodenew) >= 2){
						$html .= "
	                            <label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
								<br>
								
									<!--|| Tabla para mensaje de Produto||  -->

									<table class='tableLinhas'>
									
									<th class='dark'><center><p>PRODUTO PRÓPIO</p></center></th>
									
									</table>
									<br>
									<!--|| Tabla para mensaje de Produto || -->

	                            <table class='tableLinhas'>
									<tr>
										<th class='dark'>QRCode: " . $pro->pro_qrcode . "</th>
										<th colspan='3' class='dark'>Produto: " . $pro->pro_nome . "</th>
									</tr>
									<tr>
										<th class='dark'>COMPOSIÇÃO: " . $nomepai . ' - QRCode: ' .$qrcode. "</th>
										<th colspan='3' class='dark'></th>
									</tr>
									<tr>
										<td width='23%'>Calibre: " . $pro->pro_calibre . "</td>
										<td width='23%'>Curvatura: " . $pro->pro_curvatura . "</td>
										<td width='23%'>Comprimento: " . $pro->pro_comprimento . "</td>
										<td>Diâmetro interno: " . $pro->pro_diametrointerno . "</td>
									</tr>
									<tr>
										<td colspan='3'>Fabricante: " . $pro->pro_fabricante . "</td>
										<td>Data de fabricação: " . DefaultHelper::converte_data($pro->pro_datafabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Marca: " . $pro->pro_marca . "</td>
										<td>Anvisa: " . $pro->pro_anvisa . "</td>
										<td>Lote de fabricação: " . $pro->pro_lotefabricacao . "</td>
									</tr>
									<tr>
										<td colspan='3'>Referências: " . $pro->pro_referencias . "</td>
										<td>Validade: " . DefaultHelper::converte_data_permanente($pro->pro_validacaofabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Grupo de material: " . $gma->gma_nome . "</td>
										<td colspan='2'>Qtde. máxima de reprocessamento: " . $pro->pro_maxqtdprocessamento . "</td>
									</tr>
									<tr>
										<td colspan='3'>Cliente: " . $cli->cli_nome . "</td>
										<td>Cadastrado por: " . (($usu->usu_login != "") ? $usu->usu_login : "-") . "</td>
									</tr>
								</table>

								<br>

								<table class='tableLinhas'>
									<tr>
										<td width='46%'>Data de cadastro no sistema: " . DefaultHelper::converte_data($pro->pro_data) . "</td>
										<td>Estado atual: " .
											(($pro->pro_descarte == '*')
												? "DESCARTADO"
												: (($pro->pro_status == '1')
														? "PRONTO PARA USO"
														: "NÃO PRONTO - NECESSITA ESTERILIZAÇÃO!"
													)
											) .
										"</td>
									</tr>
								</table>

								<br>

								<table class='tableLinhas'>
									<tr>
										<th width='120px'>Data</th>
										<th width='50px'>Empresa</th>
										<th width='85px'>Qrcode</th>
										<th width='40px'>Qtde</th>
										<th width='80'>Lote Expurgo</th>
										<th width='105px'>Lote Esterilização</th>
										<th width='190px'>Método de esterilização</th>
										<th>Responsável técnico</th>
										<th width='35px'>Reuso</th>
									</tr>";
					}
					else{
						$html .= "
	                            <label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
								<br>
								
									<!--|| Tabla para mensaje de Produto||  -->

									<table class='tableLinhas'>
									
									<th class='dark'><center><p>PRODUTO PRÓPIO</p></center></th>
									
									</table>
									<br>
									<!--|| Tabla para mensaje de Produto || -->

	                            <table class='tableLinhas'>
									<tr>
										<th class='dark'>QRCode: " . $pro->pro_qrcode . "</th>
										<th colspan='3' class='dark'>Produto: " . $pro->pro_nome . "</th>
									</tr>
									<tr>
										<th class='dark'>COMPOSIÇÃO: " . $nomepai . ' - QRCode: ' .$qrcode. "</th>
										<th colspan='3' class='dark'></th>
									</tr>
									<tr>
										<td width='23%'>Calibre: " . $pro->pro_calibre . "</td>
										<td width='23%'>Curvatura: " . $pro->pro_curvatura . "</td>
										<td width='23%'>Comprimento: " . $pro->pro_comprimento . "</td>
										<td>Diâmetro interno: " . $pro->pro_diametrointerno . "</td>
									</tr>
									<tr>
										<td colspan='3'>Fabricante: " . $pro->pro_fabricante . "</td>
										<td>Data de fabricação: " . DefaultHelper::converte_data($pro->pro_datafabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Marca: " . $pro->pro_marca . "</td>
										<td>Anvisa: " . $pro->pro_anvisa . "</td>
										<td>Lote de fabricação: " . $pro->pro_lotefabricacao . "</td>
									</tr>
									<tr>
										<td colspan='3'>Referências: " . $pro->pro_referencias . "</td>
										<td>Validade: " . DefaultHelper::converte_data_permanente($pro->pro_validacaofabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Grupo de material: " . $gma->gma_nome . "</td>
										<td colspan='2'>Qtde. máxima de reprocessamento: " . $pro->pro_maxqtdprocessamento . "</td>
									</tr>
									<tr>
										<td colspan='3'>Cliente: " . $cli->cli_nome . "</td>
										<td>Cadastrado por: " . (($usu->usu_login != "") ? $usu->usu_login : "-") . "</td>
									</tr>
								</table>

								<br>

								<table class='tableLinhas'>
									<tr>
										<td width='46%'>Data de cadastro no sistema: " . DefaultHelper::converte_data($pro->pro_data) . "</td>
										<td>Estado atual: " .
											(($pro->pro_descarte == '*')
												? "DESCARTADO"
												: (($pro->pro_status == '1')
														? "PRONTO PARA USO"
														: "NÃO PRONTO - NECESSITA ESTERILIZAÇÃO!"
													)
											) .
										"</td>
									</tr>
								</table>

								<br>

								<table class='tableLinhas'>
									<tr>
										<th width='120px'>Data</th>
										<th width='50px'>Empresa</th>
										<th width='80'>Lote Expurgo</th>
										<th width='105px'>Lote Esterilização</th>
										<th width='190px'>Método de esterilização</th>
										<th>Responsável técnico</th>
										<th width='35px'>Reuso</th>
									</tr>";
					}
					// ocorrências Locais do produto
					$arrOco = OcorrenciasProdutosController::getOcorrenciasProdutos("opr_idproduto = " . $pro->pro_id);
					// retiradas do produto (saída de materiais)
					$arrIsa = ItensSaidaController::getItensSaida("isa_idproduto = " . $pro->pro_id);
					// esterilizações do item realizadas pela Sterilab
					$arrIsoS = ItensSolicitacoesController::relItensSterilab("iso_idproduto = " . $pro->pro_id, "iso_dataesterilizacao, iso_horaesterilizacao");
					// ocorrências Sterilab do produto
					$arrOcoS = OcorrenciasProdutosController::getOcorrenciasProdutosSterilab("opr_idproduto = " . $pro->pro_id);
					// arr recebe ocorrências Locais, ocorrências Sterilab e saída de materias
					$arr = array();
					$iArr = 0;
					for($i = 0; $i < sizeof($arrOco); $i++){
						$oco = OcorrenciasController::getOcorrencia($arrOco[$i]->opr_idocorrencia);
						$arr[$iArr]['data'] = $arrOco[$i]->opr_data;
						$arr[$iArr]['empresa'] = "Local";
						$arr[$iArr]['texto'] = "Ocorrência: " . $oco->oco_nome . (($arrOco[$i]->opr_obs != "") ? " | ".$arrOco[$i]->opr_obs : "");
						$iArr++;
					}
					for($i = 0; $i < sizeof($arrOcoS); $i++){
						$oco = OcorrenciasController::getOcorrenciaSterilab($arrOcoS[$i]->opr_idocorrencia);
						$arr[$iArr]['data'] = $arrOcoS[$i]->opr_data;
						$arr[$iArr]['empresa'] = "Sterilab";
						$arr[$iArr]['texto'] = "Ocorrência: " . $oco->oco_nome . (($arrOcoS[$i]->opr_obs != "") ? " | ".$arrOcoS[$i]->opr_obs : "");
						$iArr++;
					}

					$setor_anterior = '';
					for($i = 0; $i < sizeof($arrIsa); $i++){

						$sma = SaidaMateriaisController::getSaidaMateriais($arrIsa[$i]->isa_idsaida);


						$setor_destino = ItensSaidaController::getItemBySaidaEProduto($arrIsa[$i]->isa_idsaida, $pro->pro_id);
						
						if($setor_anterior == $setor_destino->isa_idsetordestino){
							continue;
						}

						$setor_anterior = $setor_destino->isa_idsetordestino;
					

						$set = SetoresController::getSetor($setor_destino->isa_idsetordestino);
						$arr[$iArr]['data'] = $arrIsa[$i]->isa_data;
						$arr[$iArr]['empresa'] = "-";
						$textoRetirada = ($arrIsa[$i]->isa_reuso == "") ? "Primeiro uso" : "Retirada";


						$n = $set->set_nome;
						define("VARR", $n);

						$transf_data = SaidaMateriaisController::getTransfData($sma->sma_id)[0];
			 			$usu_nome = $transf_data['usu_nome'];
			 			//print_r($usu_nome);


						if( $sma->sma_tiposaida === 'T' ){
							$arr[$iArr]['texto'] = "Transfer&ecirc;ncia para: {$set->set_nome}";
						}
						else{
							$arr[$iArr]['texto'] = 	"{$textoRetirada}: PRONTUÁRIO {$sma->sma_prontuario}, " .
													"PACIENTE: {$sma->sma_paciente}, SALA: {$arrIsa[$i]->isa_sala}, ".VARR."," .  
													(($arrIsa[$i]->isa_obs != "") ? "<br><i>Obs.: " . 
													$arrIsa[$i]->isa_obs . "</i>" : "");
						}
						$iArr++;

					}
					// ordenador de arr por data
					for ($i = 0; $i < sizeof($arr); $i++){
						for ($j = $i + 1; $j < sizeof($arr); $j++){
							if($arr[$i]['data'] > $arr[$j]['data']){
								$aux = $arr[$j];
								$arr[$j] = $arr[$i];
								$arr[$i] = $aux;
							}
						}
					}
					//
					$i = 0;
					$j = 0;
					$is = 0;
					foreach (ItensSolicitacoesController::relItens("iso_idproduto = " . $pro->pro_id, "iso_dataesterilizacao, iso_horaesterilizacao") as $iso){
						// se a esterilização na Sterilab do topo da fila estiver entre as datas
						while($is < sizeof($arrIsoS) && $iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao > $arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao){
							// se o item do topo de arr estiver entre as datas de esterilizações Sterilab
							while($j < sizeof($arr) && $arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao > $arr[$j]['data']){
								if($arr[$j]['empresa'] == "-"){
									$html .= "	<tr>
													<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
													<td colspan='6'>" . $arr[$j]['texto'] . "</td>
												</tr>";
								} else {
									$html .= "	<tr>
													<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
													<td>" . $arr[$j]['empresa'] . "</td>
													<td colspan='5'>" . $arr[$j]['texto'] . "</td>
												</tr>";
								}
								$j++;
							}
							$met = MetodosController::getMetodoSterilab($arrIsoS[$is]->iso_idmetodo);
							$rte = ResponsaveisTecnicosController::getRTecnicoSterilab($arrIsoS[$is]->iso_idrtecnico);
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao) . "</td>
											<td>Sterilab</td>
											<td>Nº " . $arrIsoS[$is]->iso_idses . "</td>
											<td>" . $arrIsoS[$is]->iso_lote . "</td>
											<td>" . $met->met_nome . "</td>
											<td>" . $rte->rte_nome . "</td>
											<td>" . $arrIsoS[$is]->iso_nreuso . "</td>
										</tr>";
							$is++;
						}
						// se o item do topo de arr estiver entre as datas de esterilizações Locais
						while($j < sizeof($arr) && $iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao > $arr[$j]['data']){
							if($arr[$j]['empresa'] == "-"){
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td colspan='6'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							} else {
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td>" . $arr[$j]['empresa'] . "</td>
												<td colspan='5'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							}
							$j++;
						}
						$met = MetodosController::getMetodo($iso->iso_idmetodo);
						$rte = ResponsaveisTecnicosController::getRTecnico($iso->iso_idrtecnico);
						if(count($qrcodenew) >= 2){
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao) . "</td>
											<td>Local</td>
											<td>" . $iso->qrcodenew ."</td>
											<td>" .$iso->qte ."</td>
											<td>" . $iso->iso_loteequipamento . "</td>
											<td>" . $iso->iso_lote . "</td>
											<td>" . $met->met_nome . "</td>
											<td>" . $rte->rte_nome . "</td>
											<td>" . $iso->iso_nreuso . "</td>
										</tr>";
							$i++;
						}
						else{
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao) . "</td>
											<td>Local</td>
											<td>" . $iso->iso_loteequipamento . "</td>
											<td>" . $iso->iso_lote . "</td>
											<td>" . $met->met_nome . "</td>
											<td>" . $rte->rte_nome . "</td>
											<td>" . $iso->iso_nreuso . "</td>
										</tr>";
							$i++;							
						}
					}// fin del foreach
					// exibe o restante das esterilizações Sterilab, se houve alguma
					while($is < sizeof($arrIsoS)){
						// se o item do topo de arr estiver entre as datas de esterilizações Sterilab
						while($j < sizeof($arr) && $arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao > $arr[$j]['data']){
							if($arr[$j]['empresa'] == "-"){
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td colspan='6'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							} else {
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td>" . $arr[$j]['empresa'] . "</td>
												<td colspan='5'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							}
							$j++;
						}
						$met = MetodosController::getMetodoSterilab($arrIsoS[$is]->iso_idmetodo);
						$rte = ResponsaveisTecnicosController::getRTecnicoSterilab($arrIsoS[$is]->iso_idrtecnico);
						$html .= "	<tr>
										<td>" . DefaultHelper::converte_data($arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao) . "</td>
										<td>Sterilab</td>
										<td>Nº " . $arrIsoS[$is]->iso_idses . "</td>
										<td>" . $arrIsoS[$is]->iso_lote . "</td>
										<td>" . $met->met_nome . "</td>
										<td>" . $rte->rte_nome . "</td>
										<td>" . $arrIsoS[$is]->iso_nreuso . "</td>
									</tr>";
						$is++;
					} // fin del while
					// percorre restante de arr e exibe os itens restantes, se houver algum
					while($j < sizeof($arr)){
						if($arr[$j]['empresa'] == "-"){
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
											<td colspan='6'>" . $arr[$j]['texto'] . "</td>
										</tr>";
						} else {
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
											<td>" . $arr[$j]['empresa'] . "</td>
											<td colspan='5'>" . $arr[$j]['texto'] . "</td>
										</tr>";
						}
						$j++;
					}// fin del segundo while
					$html .= "	</table>";
				} else {
					//quando o produto COMUN não é encontrado
					$html .= "<label>Produto comun não encontrado.</label>";
				}
				//---------------------parte del else-----------------
			}else{
				


			   $cons = ProdutosConsignadoController::getProdutosConsignado("pro_qrcode = '" . $_REQUEST['filtro'] . "'");
			   $vcons = new	ProdutosConsignadoModel();
			   $vcons = $cons[0];	

			 
				if(!empty($vcons->pro_id)){


					$gma = GruposMateriaisController::getGrupoMateriais($vcons->pro_idgrupomateriais);
					$cli = ClientesController::getCliente($vcons->pro_idcliente);
					$usu = UsuariosController::getUsuario($vcons->pro_idusuario);	

					$html = "	<script>
									$('#btPrint').show();
								</script>";	 



				  $html .= "
	                            <label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
								<br>
								<!-- Tabla para mensaje de Produto  -->

									<table class='tableLinhas'>
									
									<th class='dark'><center><p>PRODUTO CONSIGNADO</p></center></th>
									
									</table>
									<!-- Tabla para mensaje de Produto  -->
									<br>
	                            <table class='tableLinhas'>
									<tr>
										<th class='dark'>QRCode: " . $vcons->pro_qrcode . "</th>
										<th colspan='3' class='dark'>Produto: " . $vcons->pro_nome . "</th>
									</tr>
									<tr>
										<th class='dark'>COMPOSIÇÃO: " . $nomepai . ' - QRCode: ' .$qrcode. "</th>
										<th colspan='3' class='dark'></th>
									</tr>
									<tr>
										<td width='23%'>Calibre: " . $vcons->pro_calibre . "</td>
										<td width='23%'>Curvatura: " . $vcons->pro_curvatura . "</td>
										<td width='23%'>Comprimento: " . $vcons->pro_comprimento . "</td>
										<td>Diâmetro interno: " . $vcons->pro_diametrointerno . "</td>
									</tr>
									<tr>
										<td colspan='3'>Fabricante: " . $vcons->pro_fabricante . "</td>
										<td>Data de fabricação: " . DefaultHelper::converte_data($vcons->pro_datafabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Marca: " . $vcons->pro_marca . "</td>
										<td>Anvisa: " . $vcons->pro_anvisa . "</td>
										<td>Lote de fabricação: " . $vcons->pro_lotefabricacao . "</td>
									</tr>
									<tr>
										<td colspan='3'>Referências: " . $vcons->pro_referencias . "</td>
										<td>Validade: " . DefaultHelper::converte_data_permanente($vcons->pro_validacaofabricacao) . "</td>
									</tr>
									<tr>
										<td colspan='2'>Grupo de material: " . $gma->gma_nome . "</td>
										<td colspan='2'>Qtde. máxima de reprocessamento: " . $vcons->pro_maxqtdprocessamento . "</td>
									</tr>
									<tr>
										<td colspan='3'>Cliente: " . $cli->cli_nome . "</td>
										<td>Cadastrado por: " . (($usu->usu_login != "") ? $usu->usu_login : "-") . "</td>
									</tr>
								</table> 

								<br>

								<table class='tableLinhas'>
									<tr>
										<td width='46%'>Data de cadastro no sistema: " . DefaultHelper::converte_data($vcons->pro_data) . "</td>
										<td>Estado atual: " .
											(($vcons->pro_descarte == '*')
												? "DESCARTADO"
												: (($vcons->pro_status == '1')
														? "PRONTO PARA USO"
														: "NÃO PRONTO - NECESSITA ESTERILIZAÇÃO!"
													)
											) .
										"</td>
									</tr>
								</table>

								<br>


									<table class='tableLinhas'>
									<tr>
										<th width='120px'>Data</th>
										<th width='50px'>Empresa</th>
										<th width='80px'>Solicitação</th>
										<th width='100px'>Lote</th>
										<th>Método de esterilização</th>
										<th>Responsável técnico</th>
										<th width='35px'>Reuso</th>
									</tr>";
					// ocorrências Locais do produto
					$arrOco = OcorrenciasProdutosController::getOcorrenciasProdutos("opr_idproduto = " . $vcons->pro_id);
					// retiradas do produto (saída de materiais)
					$arrIsa = ItensSaidaController::getItensSaidaConsignado("isa_idproduto = " . $vcons->pro_id);
					// esterilizações do item realizadas pela Sterilab
					$arrIsoS = ItensSolicitacoesController::relItensSterilab("iso_idproduto = " . $vcons->pro_id, "iso_dataesterilizacao, iso_horaesterilizacao");

					print_r($arrIsa);

					// ocorrências Sterilab do produto
					$arrOcoS = OcorrenciasProdutosController::getOcorrenciasProdutosSterilab("opr_idproduto = " . $vcons->pro_id);

					// arr recebe ocorrências Locais, ocorrências Sterilab e saída de materias
					$arr = array();
					$iArr = 0;
					for($i = 0; $i < sizeof($arrOco); $i++){
						$oco = OcorrenciasController::getOcorrencia($arrOco[$i]->opr_idocorrencia);
						$arr[$iArr]['data'] = $arrOco[$i]->opr_data;
						$arr[$iArr]['empresa'] = "Local";
						$arr[$iArr]['texto'] = "Ocorrência: " . $oco->oco_nome . (($arrOco[$i]->opr_obs != "") ? " | ".$arrOco[$i]->opr_obs : "");
						$iArr++;
					}
					for($i = 0; $i < sizeof($arrOcoS); $i++){
						$oco = OcorrenciasController::getOcorrenciaSterilab($arrOcoS[$i]->opr_idocorrencia);
						$arr[$iArr]['data'] = $arrOcoS[$i]->opr_data;
						$arr[$iArr]['empresa'] = "Sterilab";
						$arr[$iArr]['texto'] = "Ocorrência: " . $oco->oco_nome . (($arrOcoS[$i]->opr_obs != "") ? " | ".$arrOcoS[$i]->opr_obs : "");
						$iArr++;
					}
					for($i = 0; $i < sizeof($arrIsa); $i++){

						$sma = SaidaMateriaisController::getSaidaMateriais($arrIsa[$i]->isa_idsaida);

						error_log( print_r( $sma , true ) );

						$set = SetoresController::getSetor($sma->sma_idsetor);
						$arr[$iArr]['data'] = $arrIsa[$i]->isa_data;
						$arr[$iArr]['empresa'] = "-";
						$textoRetirada = ($arrIsa[$i]->isa_reuso == "") ? "Primeiro uso" : "Retirada";

						if( $sma->sma_tiposaida === 'T' ){
							$arr[$iArr]['texto'] = "Transfer&ecirc;ncia para: {$set->set_nome}";
						}
						else{
							$arr[$iArr]['texto'] = 	"{$textoRetirada}: PRONTUÁRIO {$sma->sma_prontuario}, " .
													"{$sma->sma_paciente}, {$arrIsa[$i]->isa_sala}, {$set->set_nome}," .  
													(($arrIsa[$i]->isa_obs != "") ? "<br><i>Obs.: " . 
													$arrIsa[$i]->isa_obs . "</i>" : "");
						}
						$iArr++;

					}

							// ordenador de arr por data
					for ($i = 0; $i < sizeof($arr); $i++){
						for ($j = $i + 1; $j < sizeof($arr); $j++){
							if($arr[$i]['data'] > $arr[$j]['data']){
								$aux = $arr[$j];
								$arr[$j] = $arr[$i];
								$arr[$i] = $aux;
							}
						}
					}
					//
					$i = 0;
					$j = 0;
					$is = 0;

					foreach (ItensSolicitacoesController::relItensConsignado("iso_idproduto = " . $vcons->pro_id, "iso_dataesterilizacao, iso_horaesterilizacao") as $iso){


						// se a esterilização na Sterilab do topo da fila estiver entre as datas
						while($is < sizeof($arrIsoS) && $iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao > $arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao){
							// se o item do topo de arr estiver entre as datas de esterilizações Sterilab
							while($j < sizeof($arr) && $arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao > $arr[$j]['data']){
								if($arr[$j]['empresa'] == "-"){
									$html .= "	<tr>
													<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
													<td colspan='6'>" . $arr[$j]['texto'] . "</td>
												</tr>";
								} else {
									$html .= "	<tr>
													<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
													<td>" . $arr[$j]['empresa'] . "</td>
													<td colspan='5'>" . $arr[$j]['texto'] . "</td>
												</tr>";
								}
								$j++;
							}
							$met = MetodosController::getMetodoSterilab($arrIsoS[$is]->iso_idmetodo);
							$rte = ResponsaveisTecnicosController::getRTecnicoSterilab($arrIsoS[$is]->iso_idrtecnico);
							$html .= "	<tr>
											<td>" . DefaultHelper::converte_data($arrIsoS[$is]->iso_dataesterilizacao." ".$arrIsoS[$is]->iso_horaesterilizacao) . "</td>
											<td>Sterilab</td>
											<td>Nº " . $arrIsoS[$is]->iso_idses . "</td>
											<td>" . $arrIsoS[$is]->iso_lote . "</td>
											<td>" . $met->met_nome . "</td>
											<td>" . $rte->rte_nome . "</td>
											<td>" . $arrIsoS[$is]->iso_nreuso . "</td>
										</tr>";
							$is++;
						}
						// se o item do topo de arr estiver entre as datas de esterilizações Locais
						while($j < sizeof($arr) && $iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao > $arr[$j]['data']){
							if($arr[$j]['empresa'] == "-"){
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td colspan='6'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							} else {
								$html .= "	<tr>
												<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
												<td>" . $arr[$j]['empresa'] . "</td>
												<td colspan='5'>" . $arr[$j]['texto'] . "</td>
											</tr>";
							}
							$j++;
						}
						$met = MetodosController::getMetodo($iso->iso_idmetodo);
						$rte = ResponsaveisTecnicosController::getRTecnico($iso->iso_idrtecnico);

						$html .= "	<tr>
										<td>" . DefaultHelper::converte_data($iso->iso_dataesterilizacao." ".$iso->iso_horaesterilizacao) . "</td>
										<td>Local</td>
										<td>Nº " . $iso->iso_idses . "</td>
										<td>" . $iso->iso_lote . "</td>
										<td>" . $met->met_nome . "</td>
										<td>" . $rte->rte_nome . "</td>
										<td>" . $iso->iso_nreuso . "</td>
									</tr>";
						$i++;
					} // fin del foreach

					// percorre restante de arr e exibe os itens restantes, se houver algum
							while($j < sizeof($arr)){
							if($arr[$j]['empresa'] == "-"){
								
								$html .= "	<tr>
								<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
								<td colspan='6'>" . $arr[$j]['texto'] . "</td>
								</tr>";
						
							} else {
								$html .= "	<tr>
								<td>" . DefaultHelper::converte_data($arr[$j]['data']) . "</td>
								<td>" . $arr[$j]['empresa'] . "</td>
								<td colspan='5'>" . $arr[$j]['texto'] . "</td>
								</tr>";
							}
							$j++;
						}
								
						
								  $html .= "	</table>";					
						} else {
									//quando o produto COMUN não é encontrado
									$html .= "<label>Produto consignado não encontrado.</label>";
						}

			

					}echo $html;


				}  else {
						$html .= "<label>Produto não encontrado.</label>";
					}//fin del if inicial
		?>
	</div>

<?php
	include "view/helper/rodape.php";
?>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2013
*/
?>