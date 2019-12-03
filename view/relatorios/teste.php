
	
	
<div id="divPrintP" class="">

<?php include 'teste.php'; ?>

</div>

 
	


<!-- the final of teh divi -->
			

   
	<a href='#' id='btPrint' class='btn pull-right onlyScreen hide'><i class='icon-print'></i> Imprimir</a>
	<a href='#' id='btPrintPQ' class='btn pull-right onlyScreen hide'><i class='icon-print'></i> ImprimirPequeno</a>
	<br><br><br>
	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressão -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		if($_REQUEST['gerar'] == 1){
			$linhasPorPag = 15;
			$arrIso = ItensSolicitacoesController::relControleEsterilizacao("iso_lote = '" . $_REQUEST['lote'] . "' GROUP BY pro_nome", "pro_nome");
		
			$lote_detergente = $arrIso[0]->iso_lotedetergente;
			$data_esterilizacao = $arrIso[0]->iso_dataesterilizacao;
			$id_equipamento = $arrIso[0]->iso_idequipamento;
				
			$equipamento = EquipamentoController::getEqupipamento($id_equipamento);	
				
			$equipamento = $equipamento->eq_descricao;	
				
			$totalPags = ceil(sizeof($arrIso) / $linhasPorPag);
			$valor = sizeof($arrIso);
			
			 $arr = ItensSolicitacoesController::responsavel_tecnico("iso_lote = '" . $_REQUEST['lote']. "'" );

		   //  print_r($arr);

		  //  print_r($arr);

		  
			/*  if (sizeof($arrIso) % $linhasPorPag > $linhasPorPag - 10) {
				$va = sizeof($arrIso) % $linhasPorPag;
				
				$rodapeSeparado = true;
				$totalPags ++;
				
			} else {
				$rodapeSeparado = false;
			} */
			
			$html = "		<div class='onlyScreen'>";
			$htmlPrint = "	<div class='onlyPrint'>";
			
			$cabeca = "	<script>
							$('#formulario').show();	
							$('#btPrint').show();
							$('#btPrintPQ').show();
						</script>" . cabecalhoPagina(1, $totalPags, true);
			$html .= $cabeca;
			$htmlPrint .= $cabeca;
			
			
			$totalItens = 0;
			$total_caixa = 0;
			$total_avuso = 0; 
			
			foreach ($arrIso as $iso){
			if($iso->iso_pro_composto == 1)
				$total_caixa++;
				$totalItens += $iso->n;
				$total_avuso = $totalItens - $total_caixa;
			}
			
			$i = 0;
		
			
			foreach ($arrIso as $iso){
				
				$i++;
			
				$linha = "	<tr class='tela'>
								
								<td>" . $iso->iso_pro_nome . "</td>
								<td class='center'>" . $iso->n . "</td>
								<td class='onlyScreen' > </td>
							</tr>";
						
		
				$html .= $linha;
				$htmlPrint .= $linha;			
				
			
			
					/* en esta parte del codigo puedo colocar el salto d linea*/
			
				if ($i % $linhasPorPag == 0 && $i == 15 && $valor > 16){
					//echo "ingresó1 al $i % $linhasPorPag == 0 && $i == 15 || $i == $valor";
					
					
					$htmlPrint .= "	</tbody>
									
								
							</table>
							           <table class='tableLinhas1 onlyPrint tabela'>
								<thead>
									<tr class='tr-normal'>
										<th colspan='4' class='right_transparent'>
										<b>Bowie Dick</b> <hr class='linea'>
										</th>					
									</tr>
								</thead>
									 
										<tbody>
									<tr class='tr-large'>
										<td class=''>
										Liberado?<br>
										<input type='checkbox' value='' class='myinput'><span class='nameinput'> Sim</span>
										<input type='checkbox' value='' class='myinput'><span class='nameinput'>  Não</span>	
										
										</td>
										<td colspan=''class='right_transparent'>
											<b>Responsável pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfin."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='tr-normal right_transparent'>
											<b>Liberador de Carga <hr class='linea1'></b>
										</th>
									</tr>
									<tr class=''>
											<td colspan='' class='td-super'>
												<b>Horario da retirada de carga</b><label class='parametro onlyPrint'>".$infl_horario."</label>
											</td>
											<td class='td-super'>
												<b>Leitura do resultado<b><label class='parametro onlyPrint'>".$infl_leitura."</label>
											</td>
											<td colspan='2' class='tam right_transparent'>
												<p class='border_interno' ><b >Fixar indicador aqui</b></p>
											</td>
									</tr>
									<tr class='tr-large'>
										<td colspan='4' class='right_transparent'>
											<b>Responsável pela retirada de carga</b><label class='parametro onlyPrint'>".$infl_retiradacarga."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='right_transparent'>
											<b>Indicador Biológico</b> <hr class='linea1'>
										</th>
									</tr>
									<tr class='tr-large'>
										<td colspan='1' class=''>
											<b>Responsável pela incubação</b><label class='parametro onlyPrint'>".$infl_incubacao."</label>
										</td>
										<td colspan='' class=''>
											<b>Data</b><label class='parametro onlyPrint'>".DefaultHelper::converte_data($infl_data_incubacao)."</label>
										</td>
										<td colspan='' class='right_transparent'>
											<b>Horário</b><label class='parametro onlyPrint'>".$infl_horario_incubacao."</label>
										</td>
									</tr>
									<tr class=''>
                                            <td colspan='' class='td-super'>
										       <b>Resultado final</b><br>
											   <input type='checkbox' value='' class='myinput'><span class='nameinput'> Positivo</span>
											   <input type='checkbox' value='' class='myinput'><span class='nameinput'> Negativo</span> 
											
											</td>
											<td class='tam'>
											      <p class='border_interno' ><b>Etiqueta contra teste</b></p>
											</td>
											<td colspan='2' class='tam right_transparent'>
												 
												   <p class='border_interno' ><b>Etiqueta teste</b></p>
												 
											</td>
									</tr>
									
									<tr class='tr-large'>
										<td colspan='4' class='right_transparent'>
											<b>Responsável pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfinal."</label>
										</td>
									</tr>
									
									
									
									</tbody>
									 
									 
									 
									 
									 
									 </table>
									  <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
							
							
				}else if ($i % $linhasPorPag == 0 && $i > 16){
				
					//echo "ingresó2 al $i % $linhasPorPag == 0 && $i > 16";
					
				$htmlPrint .= "	</tbody>
							</table>

							        
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
				
				
				} else if($i == $valor && $valor < 16 ){
				
				//echo "ingresó3 al $i == $valor && $valor < 15" ;
				$htmlPrint .= "	</tbody>
							</table>
							        <table class='tableLinhas1 onlyPrint tabela'>
								<thead>
									<tr class='tr-normal'>
										<th colspan='4' class='right_transparent'>
										<b>Bowie Dick</b> <hr class='linea'>
										</th>					
									</tr>
								</thead>
									 
										<tbody>
									<tr class='tr-large'>
										<td class=''>
										Liberado?<br>
										<input type='checkbox' value='' class='myinput'><span class='nameinput'> Sim</span>
										<input type='checkbox' value='' class='myinput'><span class='nameinput'>  Não</span>	
										
										</td>
										<td colspan=''class='right_transparent'>
											<b>Responsável pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfin."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='tr-normal right_transparent'>
											<b>Liberador de Carga <hr class='linea1'></b>
										</th>
									</tr>
													<tr class=''>
											<td colspan='' class='td-super'>
												<b>Horario da retirada de carga</b><label class='parametro onlyPrint'>".$infl_horario."</label>
											</td>
											<td class='td-super'>
												<b>Leitura do resultado<b><label class='parametro onlyPrint'>".$infl_leitura."</label>
											</td>
											<td colspan='2' class='tam right_transparent'>
												<p class='border_interno' ><b>Fixar indicador aqui</b></p>
											</td>
									</tr>
									<tr class='tr-large'>
										<td colspan='4' class='right_transparent'>
											<b>Responsável pela retirada de carga</b><label class='parametro onlyPrint'>".$infl_retiradacarga."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='right_transparent'>
											<b>Indicador Biológico</b> <hr class='linea1'>
										</th>
									</tr>
									<tr class='tr-large'>
										<td colspan='1' class=''>
											<b>Responsável pela incubação</b><label class='parametro onlyPrint'>".$infl_incubacao."</label>
										</td>
										<td colspan='' class=''>
											<b>Data</b><label class='parametro onlyPrint'>".DefaultHelper::converte_data($infl_data_incubacao)."</label>
										</td>
										<td colspan='' class='right_transparent'>
											<b>Horário</b><label class='parametro onlyPrint'>".$infl_horario_incubacao."</label>
										</td>
									</tr>
																		
									<tr class=''>
                                            <td colspan='' class='td-super'>
										       <b>Resultado final</b><br>
											   <input type='checkbox' value='' class='myinput'><span class='nameinput'> Positivo</span>
											   <input type='checkbox' value='' class='myinput'><span class='nameinput'> Negativo</span> 
											
											</td>
											<td class='tam'>
											      <p class='border_interno' ><b>Etiqueta contra teste</b></p>
											</td>
											<td colspan='2' class='tam right_transparent'>
												 
												   <p class='border_interno' ><b>Etiqueta teste</b></p>
												 
											</td>
									</tr>
									
									<tr class='tr-large'>
										<td colspan='4' class='right_transparent'>
											<b>Responsável pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfinal."</label>
																
										</td>
										
										
									</tr>
									
									
									
									</tbody>
									 
									 
									 
									 
									 
									 </table>
									 <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							";
				
				}
				
				
				
				
			}
			
			
			//---------THE NEW TABLE ITERATION--------------
								
	


			
			//Aqui llenamos os campos da tabela
			// rodapé
			
			if($i != 0){
				$pe = "	
							
								
				</tbody>
						</table>
						<br>
						<b class='onlyScreen'>Total: " . $totalItens . "</b>";
						
						
						
				/*$peDados = "
							<td colspan='4' class='tr-large right_transparent'>
											<b>Tipo de carga</b>
											<br>
											<input type='checkbox' class='myinput'> <span class='nameinput'>Bowie Dick</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Instrumental</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Pacote(roupa)</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Termosensível</span>
										</td>";*/
							
							
							
				$html .= $pe;
				$htmlPrint .= $pe 
							. (($rodapeSeparado) 
									? "<div style='page-break-after: always'></div>" . cabecalhoPagina($totalPags, $totalPags, false) 
									: "") 
							. $peDados;
			} else {
				$pe = "		<tr><td colspan='3' align='center'>Nenhum registro encontrado.</td></tr>
							</tbody>
						</table>";
				$html .= $pe;
				$htmlPrint .= $pe;
			}
			
			$html .= "		</div>";
			$htmlPrint .= "	</div>";
			
			echo $html . $htmlPrint;
		}
		?>
	</div>
	
<?php

	
	function cabecalhoPaginapq($pag, $pags, $comTable) {
		global $infl_temperatura;
	    global $infl_iniciociclo;
		global $infl_finciclo;
		global $infl_responfin;
		global $infl_horario;
		global $infl_leitura;
		global $infl_retiradacarga;
		global $infl_incubacao;
		global $infl_responfinal;
		global $line;
		global $linhasPorPag;
		global $lote_detergente;
		global $data_esterilizacao;
		global $equipamento;
		global $arr;
		$html .= " <table class='tableLinhas1 onlyPrint tabela '>
								";
								if($pag == 1){
							$html .="	<thead >
									<tr class='head' class=''>
										<th colspan='3' class='registro'><p>Registro de funcionamento da autoclave</p></th>
										<th colspan='2' class='logo right_transparent'><div>
						<!--	<img src='img/tms.png' width='100px' class='pull-left'> -->
							<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
						</div></th>
									</tr>
								</thead>
								<tbody>
									<tr class='tr-large'>
										<td id='' class='aparelho'<b>Data</b><label class='parametro onlyPrint'>" . date("d/m/Y", strtotime($data_esterilizacao)) . "</label></td>
										<td class='aparelho'><b>Aparelho</b><label class='parametro onlyPrint'>".$equipamento."</label></td>
										<td class='right_transparent'><b>Ciclo</b><label class='parametro onlyPrint'>".$lote_detergente."</label></td>
										
									</tr>
									<tr style='border-top:none !important;'>
										<td colspan='4' class='tr-large right_transparent'>
											<b>Tipo de carga</b>
											<br>
											<input type='checkbox' class='myinput'> <span class='nameinput'>Bowie Dick</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Instrumental</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Pacote(roupa)</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Termosensível</span>
										</td>
									</tr>
									<tr class='tr-large'>
										<td colspan='' class=''>
											<b>Temperatura</b><label class='parametro onlyPrint'>".$infl_temperatura."</label>
										</td>
										<td colspan='' class=''>
											<b>Início do Ciclo</b><label class='parametro onlyPrint'>".$infl_iniciociclo."</label>

										</td>
										<td colspan='' class=''>
											<b>Final do Ciclo</b><label class='parametro onlyPrint'>".$infl_finciclo."</label>

										</td>
										<td colspan='' class='right_transparent'>
											Contém implantes?<br>
											<input type='checkbox' value='' class='myinput'><span class='nameinput'> Sim</span>
											<input type='checkbox' value='' class='myinput'><span class='nameinput'>  Não</span>

										</td>
										
									</tr>
								<tr class='tr-large'>
										<td colspan='4'class='right_transparent'>
											<b>Responsável</b><label class='parametro onlyPrint'>" .$arr. "</label>
										</td>
									</tr>
								</tbody>";} else{
							$html .="	<tr class='tr-large'>
										<td colspan='4'class='right_transparent'>
											<b>Responsável</b><label class='parametro onlyPrint'>" .$arr. "</label>
										</td>
									</tr>";
								
								}
															
						"								
														

				     </table>";
		if ($comTable){
		
			$html .= "
			<table class='tableLinhas1  tabela ' style='font-size:12px !important; border-top:1px #fff !important;' >
								<thead >
									<tr>
										<th class='onlyScreen' width=''>
											<b>Itens</b>
										</th >
										<th class='onlyScreen' width='105px'>
											<b>Quant.</b>
										</th>
										<th class='onlyScreen' width='400px'>
											<b>Obs.</b>
										</th>

										<th class='onlyPrint' width=''>
											<b>Itens</b>
										</th >
										<th class='onlyPrint' width='105px'>
											<b>Quant.</b>
										</th>
										
";

										if($pag == 1){
											$html .= "<th class='onlyPrint' width='200px'>Etiquta de Controle de Ciclo</th>";
										}
"
										
									</tr>
								</thead>
								<tbody>			
			
			";
		
			/*$html .= "
			<table class='tableLinhas '>
						<thead>
							<tr>
								<th width='70px' style='text-align: center;'>Quantidade</th>
								<th>Descrição do material</th>
								<th width='300px'>Observação</th>
							</tr>
							<tr>
							</tr>
						</thead>
						<tbody>";*/
		}
		return $htmlpq;
	}
	
	
?>










<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2015
*/
?>