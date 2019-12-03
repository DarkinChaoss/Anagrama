<?php
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	
		<script>
	
	
		
		$(document).ready(function(){
		$( "#Clique" ).click(function() {
		
		   $("#escondido").css("display","block");
				
		});
		  
		  
		  $(".cl").click(function(){
			
			alert("quier");
			
			/*var radioValue = $("input[name='type']:checked").val();
			
		    
			console.log(radioValue);
			var url = window.location.href;
			//console.log(url);
			
			var a1 = url.indexOf("&type");
			console.log(a1);
			
						
			if(a1 == -1){
				
				console.log("asiganr radioValue");
				var url_limpia = url.replace("#","");
				var re = url_limpia+"&type="+radioValue;	
           		console.log(re);			
			   $( location ).attr("href", re);
				
			}else if(a1 > 1 && radioValue == "Completo") {
				
				   //var  url = window.location.href;
				   var re = url.replace("Reduzido", "Completo");
				   $( location ).attr("href", re);
				   //console.log("troca a completo");
				
			}else if(a1 > 1 && radioValue == "Reduzido"){
				
				
				//var  url = window.location.href;
				   var re = url.replace("Completo", "Reduzido");
				   $( location ).attr("href", re);
				   
			
				
			}*/
			

			
			
						
				
				
		});
		
	
		  
		  
		  
		  
		  
		  });

	
	</script>
	
	
	<style>
		.modal-cs{
			background:#fff !important;
			width:900px !important;
			z-index:999;
			position: fixed;
			margin: 5% auto; /* Will not center vertically and won't work in IE6/7. */
			left: 0;
			right: 0;
			top:20px;
			-webkit-border-radius: 8px;
			-moz-border-radius: 8px;
			border-radius: 8px;
		}
		
.modal-backdrop{
	z-index:998 !important;
}
			
/* Grid */
.grid {
	display: grid;
}


/* minmax define um valor m?nimo e m?ximo para a coluna */
.grid-template-columns-3 {
	grid-template-columns: minmax(200px, 1fr) 1fr 1fr;
}
		#escondido{
		
		  display:none;
		}
		
/*tabla de */

.info, td, th{
	
	
  border: 1px solid #dddddd;
  text-align: center;
  padding: 4px;

	
}

#container_lote{
display:flex;
}

#expurgo{

width:50%;
}

#esterilizacao{

width:50%;
}
			
	</style>
	

	
	<h1>Controle de Esteriliza??o</h1>
	
	<form>
		<div class="row-fluid">
			<div class="span4">
				<label>Data:</label>
				<input type="text" name="data" class="input-small data" maxlength="10" required autocomplete="off">
				<label>
					Equipamento: 
					<?php 
					echo  $peo = SolicitacoesHelper::populaComboEEsterilizacao();
											
			    	?>
					<br>
								
				</label>
				

						
			
			
			
			</div><!--Fin del span4 -->
			
			<div class="pull-right">
				<button class="btn btn-primary"><i class="icon-search icon-white"></i> Pesquisar</button>
			</div>
				
					
		
		</div><!--Fin de row-fluid -->

	</form>
	<?php
		
		if(isset($_REQUEST['submit'])){
			
		$dados = array(
				'infl_temperatura' => $_POST['infl_temperatura'], 
				'infl_iniciociclo' => $_POST['infl_iniciociclo'],
				'infl_finciclo' => $_POST['infl_finciclo'],
				'infl_responfin' => $_POST['infl_responfin'],
				'infl_horario' => $_POST['infl_horario'],
				'infl_leitura' => $_POST['infl_leitura'],
				'infl_retiradacarga' => $_POST['infl_retiradacarga'],
				'infl_incubacao' => $_POST['infl_incubacao'],
		        'infl_responfinal' => $_POST['infl_responfinal'],
				'infl_horario_incubacao' => $_POST['infl_horario_incubacao'],
				'infl_data_incubacao' => $_POST['infl_data_incubacao'],
				'infl_lote' => $_REQUEST['lote']	
			);	

		      $lote = strtoupper($_REQUEST['lote']);//aqui pegamos a variable 
			 
		    //Preparamos as variables para jogar nos campos inputs
		      $infoslote = InfoLoteController::getInfo(trim($lote));
			  $infl_lote = $infoslote->infl_lote;
			 // echo $infl_lote;

				if(strtoupper($lote) == $infl_lote){
					InfoLoteController::update($dados);
				}else {
					InfoLoteController::insert($dados);
				}	
		}
				
		      $lote = strtoupper($_REQUEST['lote']);//aqui pegamos a variable 
			 
		    //Preparamos as variables para jogar nos campos inputs
		      $infoslote = InfoLoteController::getInfo($lote);
	
			
		
		      $infl_lote_id = $infoslote->id;
			  $infl_lote = $infoslote->infl_lote;
		      $infl_temperatura = $infoslote->infl_temperatura;
			  $infl_iniciociclo = $infoslote->infl_iniciociclo;
			  $infl_finciclo = $infoslote->infl_finciclo;
			  $infl_responfin = $infoslote->infl_responfin;
			  $infl_horario = $infoslote->infl_horario;
			  $infl_leitura = $infoslote->infl_leitura;
			  $infl_retiradacarga = $infoslote->infl_retiradacarga;
			  $infl_incubacao = $infoslote->infl_incubacao;
			  $infl_responfinal = $infoslote->infl_responfinal;
			  $infl_horario_incubacao = $infoslote->infl_horario_incubacao;
			  $infl_data_incubacao = $infoslote->infl_data_incubacao;
			
		
			  
			  if($lote == $infl_lote){
				
				
					$infl_temperatura = $infoslote->infl_temperatura;
					$infl_iniciociclo = $infoslote->infl_iniciociclo;
					$infl_finciclo = $infoslote->infl_finciclo;
					$infl_responfin = $infoslote->infl_responfin;
					$infl_horario = $infoslote->infl_horario;
					$infl_leitura = $infoslote->infl_leitura;
					$infl_retiradacarga = $infoslote->infl_retiradacarga;
					$infl_incubacao = $infoslote->infl_incubacao;
					$infl_responfinal = $infoslote->infl_responfinal;
					$infl_horario_incubacao = $infoslote->infl_horario_incubacao;
			        $infl_data_incubacao = $infoslote->infl_data_incubacao;
			  		  
			  }else{
                    
					$infl_temperatura = "";
					$infl_iniciociclo = "";
					$infl_finciclo = "";
					$infl_responfin = "";
					$infl_horario = "";
					$infl_leitura = "";
					$infl_retiradacarga = "";
					$infl_incubacao = "";
					$infl_responfinal = "";
					$infl_horario_incubacao = "";
					$infl_data_incubacao = "";
					
					}
			  

		  
	?>
	

	
	<div class="hide" id="formulario">
						  
						  <button id="Clique" class="btn btn-primary">Mais dados</button>
						  <br><br>
						  	
						  	<div id="escondido">
									
								<form id="formp" method="POST" ">
								<section class="grid grid-template-columns-3">


									<div class="item"><label for="usr">Temperatura:</label>
												<input type="text" name="infl_temperatura" class="form-control" id="temp" value="<?php echo $infl_temperatura ?>"></div>
									<div class="item"><label for="usr">In?cio do Ciclo:</label>
												<input type="text" name="infl_iniciociclo" class="form-control" id="ic" value="<?php echo $infl_iniciociclo ?>"></div>
									<div class="item"><label for="usr">Final do Ciclo:</label>
												<input type="text" name="infl_finciclo" class="form-control" id="fc" value="<?php echo $infl_finciclo ?>"></div>
									<div class="item"><label for="usr">Respons?vel leitura final:</label>
												<input type="text" name="infl_responfin" class="form-control" id="lf" value="<?php echo $infl_responfin ?>"></div>
									<div class="item"><label for="usr">Hor?rio da retirada de carga:</label>
												<input  type="time" name="infl_horario" class="form-control" name="hora" id="tem" value="<?php echo $infl_horario ?>"></div>
									<div class="item"><label for="usr">Leitura do resultado:</label>
												<input type="text" name="infl_leitura" class="form-control" id="lre" value="<?php echo $infl_leitura ?>"></div>
									<div class="item"><label for="usr">Respons?vel retirada de carga:</label>
												<input type="text" name="infl_retiradacarga" class="form-control" id="retcarga" value="<?php echo $infl_retiradacarga ?>"></div>
									<div class="item"><label for="usr">Respons?vel incuba??o:</label>
												<input type="text" name="infl_incubacao" class="form-control" id="incub" value="<?php echo $infl_incubacao ?>"></div>
																		
									<div class="item"><label for="usr">Respons?vel leitura final:</label>
												<input type="text" name="infl_responfinal"  class="form-control" id="rlf"  value="<?php echo $infl_responfinal ?>"></div>
								
  								  			
									<div class="item"><label for="usr">Hora da incuba??o:</label>
												<input  type="time" name="infl_horario_incubacao" class="form-control"  id="hora_incu" value="<?php echo $infl_horario_incubacao ?>"></div>	
												
											
									<div class="item"><label for="usr">Data da incuba??o:</label>
												<input type="date" name="infl_data_incubacao" maxlength="14"  value="<?php echo $infl_data_incubacao ?>"></div> 				
										
												
												
																					
									<div class="item"></div>	
																						
								</section>
								
								<input type="submit" name="submit" onClick="history.go(-1);" >
								
																
								</form>	<!-- Fin do formulario "formp"-->
								
								
								
						     </div><!-- Fin do id escondido-->
						
		</div><!-- Fin do id formulario-->
	<hr>

	
     
        <div id = "rc" class="hide">
		
		<input  type="radio" name="type" class="cargar reduzido" value="Reduzido"  <?php  echo(($_REQUEST['type'] == 'Reduzido')? checked :" "); ?>  /> Reduzido
		<input type="radio" name="type" class="cargar"  value="Completo" <?php  echo(($_REQUEST['type'] == 'Completo')? checked :" "); ?>   /> Completo
	  
	  </div>
	
	<?php
		if(isset($_REQUEST['data']) && $_REQUEST['data'] != "") {
			
			echo "<label class='parametro'>Data: " . $_REQUEST['data'] . "</label>";
			echo "<div id='container_lote'>";
			
			
			$whereqe = '';

			if($_REQUEST['eqEsterilizacao'] != 0 && $_REQUEST['eqEsterilizacao'] != ''){
				$whereqe = ' AND iso_idequipamento = '.$_REQUEST["eqEsterilizacao"].'';
			}else{
				$whereqe = '';
			}
			
			
			$lista = "
			<br><br>
						<br>
						<div id='expurgo'>
						<table class='tableLinhas'>
							<thead>
								<tr>
									<th>Equipamento-Expurgo</th>
									<th>Lotes na data</th>

									
									
								</tr>
							</thead>
							<tbody>";
							
							$t = ItensSolicitacoesController::getLotes("iso_dataesterilizacao = '" . DefaultHelper::converte_data($_REQUEST['data']) . "' ".$whereqe." ");
							
		
					
			$i = 0;
						foreach ($t as $iso) {
							 
							  $datac = (DefaultHelper::converte_data($iso->iso_dataesterilizacao));
							  // echo $datac; 
							   
							  $idequipoeqa = $iso->iso_idequipamento;
							 // echo $idequipoeqa;
							  $equicontroleqa = EquipamentoController::getEqupipamento($idequipoeqa); 
                              $maquinaeqa = $equicontroleqa->eq_descricao;
							 // echo $maquinaeqa.'iso_idequipamento<br>';
							  
							   $idequipoet = $iso->iso_idequipamentoet;
							  // echo $idequipoet;
							   $equicontrolet = EquipamentoController::getEqupipamento($idequipoet); 
                               $maquinaet = $equicontrolet->eq_descricao;
							 //  echo $maquinaet.'aqui para iso_idequipamentoet <br>';
                             
	
								if($iso->iso_lote == $iso->iso_loteequipamento){
								$lista .= "	<tr>
								<td>".$maquinaeqa."</td>
								<td><a  href='?lote=" . $iso->iso_loteequipamento . "&gerar=1&idequipament=". $iso->iso_idequipamento ."&datab=".$datac."  '>" . $iso->iso_loteequipamento . "</a></td>
								<td></td>
								<td></a></td>
							</tr>";
							
							
							}else{
								
								$lista .= "	<tr>
								<td>".$maquinaeqa."</td>
								<td><a  href='?lote=" . $iso->iso_loteequipamento . "&gerar=1&idequipament=". $iso->iso_idequipamento ."&datab=".$datac."  '>". $iso->iso_loteequipamento . "</a></td>
								</tr>";
							}
														

/*

								<td>".$maquinaet."</td>
								<td><a  href='?lote=" . $iso->iso_lote . "&gerar=1&idequipamentoet=". $iso->iso_idequipamentoet ."&datab=".$datac."  '>". $iso->iso_lote . "</a></td>
*/														
							
						
				
				$i ++;
			}
	
	
	
			$lista2 = "<div id='esterilizacao'>
						<table class='tableLinhas'>
							<thead>
								<tr>
									<th>Equipamento-Esteriliza??o</th>
									<th>Lotes na data</th>
									
								</tr>
							</thead>
							<tbody>";
							
							$t = ItensSolicitacoesController::getLotes("iso_dataesterilizacao = '" . DefaultHelper::converte_data($_REQUEST['data']) . "' ".$whereqe." ");
							
	
	
			$t2 = ItensSolicitacoesController::getLotesEt("iso_dataesterilizacao = '" . DefaultHelper::converte_data($_REQUEST['data']) . "' ".$whereqe." ");
			$i2 = 0;			
			foreach ($t2 as $iso) {
				 
				  $datac = (DefaultHelper::converte_data($iso->iso_dataesterilizacao));
				  // echo $datac; 
				   
				  $idequipoeqa = $iso->iso_idequipamentoet;
				 // echo $idequipoeqa;
				  $equicontroleqa = EquipamentoController::getEqupipamento($idequipoeqa); 
				  $maquinaeqa = $equicontroleqa->eq_descricao;
				 // echo $maquinaeqa.'iso_idequipamento<br>';
				  
				   $idequipoet = $iso->iso_idequipamentoet;
				  // echo $idequipoet;
				   $equicontrolet = EquipamentoController::getEqupipamento($idequipoet); 
				   $maquinaet = $equicontrolet->eq_descricao;
				   
					$lista2 .= "<tr>
					<td>".$maquinaeqa."</td>
					<td><a  href='?lote=" . $iso->iso_lote . "&gerar=1&idequipamentoet=". $iso->iso_idequipamentoet ."&datab=".$datac."  '>". $iso->iso_lote . "</a></td>

					</tr>";
						$i2 ++;
					
			}

				

			if ($i2 == 0) {
				$lista2 .= "	<tr>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
							</tr>";
			}
			$lista2 .= "		</tbody>
						</table></div>";

			
			if ($i == 0) {
				$lista .= "	<tr>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
								<td align='center'>Nenhum registro encontrado.</td>
							</tr>";
			}
			$lista .= "		</tbody>
						</table></div>";
			echo $lista ;
			echo $lista2 ;
			echo "</div>";
			
		}
	?>

<!-- the final of teh divi -->


<!--Inicio do select idequipamentoet -->

<?php 

if($_REQUEST['idequipamentoet'] != ''){
$id_link = $_REQUEST['idequipamentoet'];}else{
$id_link = $_REQUEST['idequipament'];
}

?>

<?php 
if($_REQUEST['idequipamentoet'] != ''){
	//echo"existe red y com para idequipamentoet   ";
	
	$equipamento = EquipamentoController::getEqupipamento($_REQUEST['idequipamentoet']); 
    $eqEsterilizacao = $equipamento->eq_formatoimp;
	$datab = $_REQUEST['datab'];
	$equipo = $equipamento->eq_descricao;
	$lote = $_REQUEST['lote'];
	//echo 'Data: ('. $datab.')<br><br>' ;
	//echo 'Equipamento: ('. $equipo.')<br><br>' ;
	//echo 'Lote: ('. $lote.')' ;
    //echo("<p id=teste>$eqEsterilizacao</p>");
$tablep = " 
	<span class='bold'>Informa??o:</span>
	<table class=' tableLinhasinfo'>
	<thead bgcolor='#d1d1d1'  > 
		<tr >
			<th  > Data</th>
			<th>Equipamento</th>
			<th >Lote</th>
		</tr>
	</thead>
		<tbody bgcolor= '#e6e6e6'>
			<tr  >
			<td class='info'>$datab</td>
			<td>$equipo</td>
			<td>$lote</td>
			</tr>
		</tbody>
	</table><br>
	";
	echo $tablep;
	
}else if($_REQUEST['idequipament'] != ''){
	//echo"existe RED y COM   ";
	
	
	$equipamento = EquipamentoController::getEqupipamento($_REQUEST['idequipament']); 
    $eqEsterilizacao = $equipamento->eq_formatoimp;
	$datab = $_REQUEST['datab'];
	$equipo = $equipamento->eq_descricao;
	$lote = $_REQUEST['lote'];
	//echo 'Data: ('. $datab.')<br><br>' ;
	//echo 'Equipamento: ('. $equipo.')<br><br>' ;
	//echo 'Lote: ('. $lote.')' ;
	

	//echo("<p id=teste>$eqEsterilizacao</p>");
	$tablep = " 
	<span class='bold'>Informa??o:</span>
	<table class=' tableLinhasinfo'>
	<thead bgcolor='#d1d1d1'  > 
		<tr >
			<th  > Data</th>
			<th>Equipamento</th>
			<th >Lote</th>
		</tr>
	</thead>
		<tbody bgcolor= '#e6e6e6'>
			<tr  >
			<td class='info'>$datab</td>
			<td>$equipo</td>
			<td>$lote</td>
			</tr>
		</tbody>
	</table><br>
	";
	echo $tablep;
	
    
}
  


?>


<!--Fin do select idequipamentoet -->
			
	
    <a href="/durazzo_consignados/esterilizacoesControle?data=<?php echo $datab; ?>&eqEsterilizacao=0" class="btn  onlyScreen hide" value="voltar" id="voltar">Voltar</a>
      
	<a href='#' id='btPrint' class='btn pull-right onlyScreen hide' value="jesus"><i class='icon-print'></i> Imprimir</a>
	<!--<a href='#' id='btPrintPQ' class='btn pull-right onlyScreen hide'><i class='icon-print'></i> ImprimirPequeno</a>-->
	<br><br><br>
	<div id="divPrint">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impress?o -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		if($_REQUEST['gerar'] == 1){
			$linhasPorPag = 15;
			
			$lote = trim($_REQUEST['lote']);
			$_REQUEST['idequipamentoet'];
			$datab = DefaultHelper::converte_data($_REQUEST['datab']);
			
			
			$queryw = '';
			
			if(isset($_REQUEST['idequipament'])){
				$datab1 = $datab . ' 00:00:00';
				$datab2 = $datab . ' 23:59:59';
				$queryw = "iso_loteequipamento ='". $lote ."' AND iso_idequipamento = '". $_REQUEST['idequipament'] ."' AND iso_data BETWEEN '$datab1' AND '$datab2' GROUP BY pro_nome";
			}else{
				$queryw = "iso_lote ='". $lote ."' AND iso_idequipamentoet = '". $_REQUEST['idequipamentoet'] ."' AND iso_dataesterilizacao = '$datab' GROUP BY pro_nome";
			}
			
			
			$arrIso = ItensSolicitacoesController::relControleEsterilizacao($queryw, "pro_nome", $datab);
			//$arrIso = ItensSolicitacoesController::relControleEsterilizacao("iso_loteequipamento = '" . $_REQUEST['loteq'] . "' GROUP BY pro_nome", "pro_nome");
			//print_r($arrIso);
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
							$('#voltar').show();
							$('#btPrint').show();
							$('#formato').show();
							
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
				
				if($eqEsterilizacao == ""){
					
				}else{
			


			$qte = count($arrIso);
			$dif = 15 - $qte;

			foreach ($arrIso as $iso){
				
				$i++;
			
				$linha = "	<tr class='tela'>

								<td style='text-align:left !important;'>" . $iso->iso_pro_nome . "</td>
								<td class='center'>" . $iso->n . "</td>
								<td class='onlyPrint' > </td>
							</tr>";
						
		
				$html .= $linha;
				$htmlPrint .= $linha;			
				
				//
				if($qte < 15 && $i == $qte){

					for ($j=0; $j < $dif ; $j++) { 
						$linha = "	<tr class='tela'>
										<td class='onlyPrint' style=' border:none;	border-collapse: collapse;'> </td>
										<td class='onlyPrint' style=' border:none;	border-collapse: collapse;'> </td>
										<td class='onlyPrint' style=' border:none;	border-collapse: collapse;'> </td>
									</tr>";

						$html .= $linha;
						$htmlPrint .= $linha;	
					}					
				}
			
			
					/* en esta parte del codigo puedo colocar el salto d linea*/
			if($eqEsterilizacao == 'COM'){
				if ($i % $linhasPorPag == 0 && $i == 15 && $valor > 16){

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
										<input type='checkbox' value='' class='myinput'><span class='nameinput'>  N?o</span>	
										
										</td>
										<td colspan=''class='right_transparent'>
											<b>Respons?vel pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfin."</label>
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
											<b>Respons?vel pela retirada de carga</b><label class='parametro onlyPrint'>".$infl_retiradacarga."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='right_transparent'>
											<b>Indicador Biol?gico</b> <hr class='linea1'>
										</th>
									</tr>
									<tr class='tr-large'>
										<td colspan='1' class=''>
											<b>Respons?vel pela incuba??o</b><label class='parametro onlyPrint'>".$infl_incubacao."</label>
										</td>
										<td colspan='' class=''>
											<b>Data</b><label class='parametro onlyPrint'>".DefaultHelper::converte_data($infl_data_incubacao)."</label>
										</td>
										<td colspan='' class='right_transparent'>
											<b>Hor?rio</b><label class='parametro onlyPrint'>".$infl_horario_incubacao."</label>
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
											<b>Respons?vel pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfinal."</label>
										</td>
									</tr>
																		
									</tbody>
									 									 									 								 
									 </table>
									  <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
							
							
				}else if ($i % $linhasPorPag == 0 && $i > 16){
				
					//echo "ingres?2 al $i % $linhasPorPag == 0 && $i > 16";
					
			/*	$htmlPrint .= "	</tbody>
							</table>

							        
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);*/
				
				
				} else if($i == $valor && $valor < 16 ){
				
				//echo "ingres?3 al $i == $valor && $valor < 15" ;
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
										<input type='checkbox' value='' class='myinput'><span class='nameinput'>  N?o</span>	
										
										</td>
										<td colspan=''class='right_transparent'>
											<b>Respons?vel pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfin."</label>
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
											<b>Respons?vel pela retirada de carga</b><label class='parametro onlyPrint'>".$infl_retiradacarga."</label>
										</td>
									</tr>
									<tr>
										<th colspan='4' class='right_transparent'>
											<b>Indicador Biol?gico</b> <hr class='linea1'>
										</th>
									</tr>
									<tr class='tr-large'>
										<td colspan='1' class=''>
											<b>Respons?vel pela incuba??o</b><label class='parametro onlyPrint'>".$infl_incubacao."</label>
										</td>
										<td colspan='' class=''>
											<b>Data</b><label class='parametro onlyPrint'>".DefaultHelper::converte_data($infl_data_incubacao)."</label>
										</td>
										<td colspan='' class='right_transparent'>
											<b>Hor?rio</b><label class='parametro onlyPrint'>".$infl_horario_incubacao."</label>
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
											<b>Respons?vel pela leitura final</b><label class='parametro onlyPrint'>".$infl_responfinal."</label>
																
										</td>
										
										
									</tr>
									
									
									
									</tbody>
									 
									 
									 
									 
									 
									 </table>
									 <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							";
				
				}
				
			}else if($eqEsterilizacao == 'RED'){
				
				
				if ($i % $linhasPorPag == 0 && $i == 15 && $valor > 16){
					//echo "ingres?1 al $i % $linhasPorPag == 0 && $i == 15 || $i == $valor";
					
					
					$htmlPrint .= "	</tbody>
									
								
							</table>
							           
									  <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
							
							
				}else if ($i % $linhasPorPag == 0 && $i > 16){
				
					//echo "ingres?2 al $i % $linhasPorPag == 0 && $i > 16";
					
				$htmlPrint .= "	</tbody>
							</table>

							        
							<div style='page-break-after: always'></div>". cabecalhoPagina(ceil(($i + 1) / $linhasPorPag), $totalPags, true);
				
				
				} else if($i == $valor && $valor < 16 ){
				
				//echo "ingres?3 al $i == $valor && $valor < 15" ;
				$htmlPrint .= "	</tbody>
							</table>
	
									 <span><b>Total Caixa:</b>" .$total_caixa."</span>
									 <span><b>Total Avuso:</b>" .$total_avuso."</span>
							";
				
				}		
				
			}// The final of the else
			
				
				
			}//This is the final if the foreach
			
				}
			//---------THE NEW TABLE ITERATION--------------
								
	


			
			//Aqui llenamos os campos da tabela
			// rodap?
			
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
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Termosens?vel</span>
										</td>";*/
							
							
							
				$html .= $pe;
				$htmlPrint .= $pe 
							. (($rodapeSeparado) 
									? "<div style='page-break-after: always'></div>" . cabecalhoPagina($totalPags, $totalPags, false) 
									: "") 
							. $peDados;
			} else if(i == 0 && $eqEsterilizacao == "") {
				$pe = "		<tr><td colspan='3' align='center' class ='bold;'><p class='bold'>Nota:Voc? deve selecionar um tipo de impress?o para o equipamento:".$equipo."
				.
				 <a href='http://localhost/durazzo_consignados//equipamento_new?populate=1&id=$id_link'>Clique aqui!</a>  </p> </td>
				</tr>
							</tbody>
						</table>";
				$html .= $pe;
				$htmlPrint .= $pe;
			}
			else {
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

	
	function cabecalhoPagina($pag, $pags, $comTable) {
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
		global $eqEsterilizacao;
		global $arr;
		
		if($eqEsterilizacao == 'RED'){
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
											<input type='checkbox' class='myinput'> <span class='nameinput'>Pl?stico</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Silicone</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Inox</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Vidro</span>
											<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Misto</span>
										</td>
									</tr>
									<tr class='tr-large'>
										<td colspan='' class=''>
											<b>Tipo de Ciclo</b><label class='parametro onlyPrint'></label>
										</td>
										<td colspan='' class=''>
											<b>In?cio do Ciclo (Hor?rio)</b><label class='parametro onlyPrint'>".$infl_iniciociclo."</label>

										</td>
										<td colspan='' class='right_transparent aparelho'>
											<b>Final do Ciclo (Hor?rio)</b><label class='parametro onlyPrint'>".$infl_finciclo."</label>

										</td>
										
										
									</tr>
								<tr class='tr-large'>
										<td colspan='4'class='right_transparent'>
											<b>Respons?vel</b><label class='parametro onlyPrint'>" .$arr. "</label>
										</td>
									</tr>
								</tbody>";} else{
							$html .="	<tr class='tr-large'>
										<td colspan='4'class='right_transparent'>
											<b>Respons?vel</b><label class='parametro onlyPrint'>" .$arr. "</label>
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
											
	
											
										</tr>
									</thead>
									<tbody>			
				
				";
			
				/*$html .= "
				<table class='tableLinhas '>
							<thead>
								<tr>
									<th width='70px' style='text-align: center;'>Quantidade</th>
									<th>Descri??o do material</th>
									<th width='300px'>Observa??o</th>
								</tr>
								<tr>
								</tr>
							</thead>
							<tbody>";*/
			}		
		}else if($eqEsterilizacao == 'COM'){
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
												<input type='checkbox' value='' class='myinput'> <span class='nameinput'>Termosens?vel</span>
											</td>
										</tr>
										<tr class='tr-large'>
											<td colspan='' class=''>
												<b>Temperatura</b><label class='parametro onlyPrint'>".$infl_temperatura."</label>
											</td>
											<td colspan='' class=''>
												<b>In?cio do Ciclo</b><label class='parametro onlyPrint'>".$infl_iniciociclo."</label>

											</td>
											<td colspan='' class=''>
												<b>Final do Ciclo</b><label class='parametro onlyPrint'>".$infl_finciclo."</label>

											</td>
											<td colspan='' class='right_transparent'>
												Cont?m implantes?<br>
												<input type='checkbox' value='' class='myinput'><span class='nameinput'> Sim</span>
												<input type='checkbox' value='' class='myinput'><span class='nameinput'>  N?o</span>

											</td>
											
										</tr>
									<tr class='tr-large'>
											<td colspan='4'class='right_transparent'>
												<b>Respons?vel</b><label class='parametro onlyPrint'>" .$arr. "</label>
											</td>
										</tr>
									</tbody>";} else{
								$html .="	<tr class='tr-large'>
											<td colspan='4'class='right_transparent'>
												<b>Respons?vel</b><label class='parametro onlyPrint'>" .$arr. "</label>
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
											<th class='onlyPrint' width='400px'>
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
									<th>Descri??o do material</th>
									<th width='300px'>Observa??o</th>
								</tr>
								<tr>
								</tr>
							</thead>
							<tbody>";*/
			}			
		}else if($eqEsterilizacao == ''){
			$html = "";
		}

		return $html;
	}
	
	include "view/helper/rodape.php";
?>










<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Solu??es em T.I. ? 2015
*/
?>