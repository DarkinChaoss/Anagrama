<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}

	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5));

	include "view/helper/cabecalho.php";

	$filtro = '';
	$dataInicial = date('d/m/Y');
	$dataFinal = date('d/m/Y');
	$tipoRel = '';
	$slStatus = '';

	if($_REQUEST['gerar'] == 1){

	    $filtro = $_REQUEST['filtro'];
	    $data1 = implode('/',array_reverse(explode('-', $_REQUEST['Data1'])));
	    $data2 = implode('/',array_reverse(explode('-', $_REQUEST['Data2'])));
	    $tipoRel = $_REQUEST['tipo'];
	    $slStatus = $_REQUEST['status'];
	    $nome_produto = strtoupper($_REQUEST['nomeproduto']);
		$validade = $_REQUEST['validade'];	    

	}
?>
	<script>
		
		// scroll comum
		$(document).on('click', '#cs-comuns', function (event) {
			event.preventDefault();

			$('html, body').animate({
				scrollTop: $($('#comum')).offset().top
			}, 500);
		});

		// scroll consignados
		$(document).on('click', '#cs-consignados', function (event) {
			event.preventDefault();

			$('html, body').animate({
				scrollTop: $($('#consignados')).offset().top
			}, 500);
		});

		// scroll hide over 100
		$('#cs-linksInternos').hide();
		$(document).on('scroll', function () {
			var scrollActual = $(window).scrollTop();

			if(scrollActual > 100){
				$('#cs-linksInternos').fadeIn();
			}else{
				$('#cs-linksInternos').fadeOut();
			}
		});
		
		// replicar valores dos campos data inicial e data final
		$(document).ready(()=> {
			$('#txtData1').on('focusout', function(){ 
				setTimeout(function(){
					$('#txtData2').val($('#txtData1').val());
				}, 400);
		});
		});
		
	</script>
	<link rel="stylesheet" href="css/produtosEstoque.css">
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
	<h1>Controle de Estoque</h1>

	<div id="cs-linksInternos">
		<a id="cs-comuns" href="#comum" title="Ir até produtos comuns"><div class="cs-link"><i class="fas fa-tag"></i></div></a>
		<a id="cs-consignados" href="#consignados" title="Ir até produtos consignados"><div class="cs-link"><i class="fas fa-user-tag"></i></div></a>
	</div>
	<form>
		<div class="row-fluid">
			<div class="span2 hide">
				<label class="radio">
					<input type="radio" name="tipo" value="a" <?php echo ( $tipoRel == 'a' ? 'checked' : $tipoRel == '' ? 'checked' : ''  ) ?>>
					Analítico
				</label>
				<label class="radio">
					<input type="radio" name="tipo" value="s" <?php echo ( $tipoRel == 's' ? 'checked' : '' ) ?> >
					Sintético
				</label>
			</div>

			<div class="span4">
				<label>Setor:</label>
				<select class="input-xlarge" name="filtro">
					<option value="0"> Todos </option>
				<?php
    				foreach (SetoresController::getSetores() as $objSetor){
				        echo '<option value="'.$objSetor->set_id.'" '.( $objSetor->set_id == $filtro ? ' selected' : '' ).' > '.$objSetor->set_nome.' </option>';
    				}
				?>
				</select>
				<br>
				<label>Status:</label>
				<select class="input-xlarge" name="status">
					<option value="" <?php echo ( $slStatus == '' ? 'selected' : '' ) ?>> Todos </option>
					<option value="E" <?php echo ( $slStatus == 'E' ? 'selected' : '' ) ?>> Em estoque / Devolvido </option>
					<option value="U" <?php echo ( $slStatus == 'U' ? 'selected' : '' ) ?>> Em uso </option>
				</select>
				<label>Produto:</label>
				<input type="text" name="nomeproduto" id="txtnomeproduto" value='<?php echo $nome_produto?>' class="form-control input-lg" placeholder="Nome do Produto" autocomplete="off">
				<div>
					<label>Validade:</label>
					<select class="input-xlarge" name="validade">
						<option value=">5" <?php echo ( $validade == '>5' ? 'selected' : '' ) ?>> Superior &agrave; 5 dias </option>
						<option value="<=5" <?php echo ( $validade == '<=5' ? 'selected' : '' ) ?>> Igual ou menor que 5 dias </option>
						<option value="<0" <?php echo ( $validade == '<0' ? 'selected' : '' ) ?>> Vencido </option>
						<option value="" <?php echo ( $validade == '' ? 'selected' : '' ) ?>> Todos </option>					
					</select>						
				</div>
				<div>
					<label style="display: none;">Data:</label>
					<input style="display: none !important;" type="text" name="Data1" id="txtData1" value='<?php echo $data1?>' class="form-control input-ls data" placeholder="Data Inicial" autocomplete="off">
					<input style="display: none !important;" type="text" name="Data2" id="txtData2" value='<?php echo $data2?>' class="form-control input-lg data" placeholder="Data Final" autocomplete="off">					
					</select>						
				</div>		
			</div>

			<div class="pull-right">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margem">
						<button class="btn btn-primary" name="gerar" value="1">
							<i class="icon-file icon-white"></i> Gerar relat&oacute;rio
						</button>						
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margem">
						<button href="#" id="btPrint" type="button" class="btn btn-default hide">
							<i class="icon-print"></i> Imprimir
						</button>					
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margem">
						<button type="button" id='btClear' onclick="window.location='produtosEstoque'" class="btn btn-warning hide">
							<span>Limpar</span>
						</button>					
					</div>
				</div>
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

			$html = "	<script>
							$('#btPrint').show();
							$('#btClear').show();
						</script>";
			$tipo =  (($_REQUEST['tipo'] == "a") ? "Analítico" : "Sintético");

			$html .= "	<div class='onlyPrint'>
							<div class='row-fluid'>
								<img src='img/tms.png' width='100px' class='pull-left'>
								<img src='img/" . (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa") . ".png' width='120px' class='pull-right'>
							</div>
							<h4>Controle de Estoque</h4>
						</div>
						<br>
						<label class='parametro' style='margin-bottom:20px;'>
							<span class='pull-left'>Tipo: {$tipo}</span>
							<span class='pull-right text-right'>
								<span>Legenda da Validade: </span>
								<span class='acima_validade' style='border:1px solid #094812;'>Maior que 5 dias</span>
								<span class='dentro_validade' style='border:1px solid #686C02;'>Igual ou menor &agrave; 5 dias</span>
								<span class='vencido' style='border:1px solid #740A0A;'>Vencido</span>
							</span>
						</label>";

			$html .= "	<label class='parametro onlyPrint'>Impresso dia " . date("d/m/Y") . ", às " . date("H:i") . "</label>
						<br>";

			if($_REQUEST['tipo'] == "a"){ // ANALÍTICO
				$html .= "	<table class='tableLinhas' style='white-space: nowrap;' >
								<thead>
								<tr>
									<th width='60px'>Setor</th>
									<th width='250px'>Produto</th>
									<th width='130px'>QRCode</th>
									<th width='60px'>Validade</th>									
				                    <th width='110px'>Última Saída</th>
					                <th width='30px'>Uso</th>
				                    <th>Situação</th>
								</tr>
								</thead>
								<tbody>";
				$i = 0;
				$setorAnterior = "";
				$totalSetor = 0;
				$totalGeral = 0;
				$totalItensGeral = 0;

				
				$html .= '
				<tr>
					<td colspan="10"><h4 id="comum" style="float:left;">Produtos Comuns <i class="fas fa-tag cs-fa"></i></h4></td>	
				</tr>
				';
								
				
				foreach ( ControleEstoqueController::select($_REQUEST['filtro'], $_REQUEST['status'], $_REQUEST['nomeproduto'] , $_REQUEST['validade'], implode('-',array_reverse(explode('/', $_REQUEST['Data1']))), implode('-',array_reverse(explode('/', $_REQUEST['Data2']))) ) as $est ){
					
					$composition_info = ProdutosCompostosController::isCompoundSon($est->qrcode);
					//var_dump($composition_info['contagem_pai']); continue;
					if(is_null($composition_info['contagem_pai'])){
				
						$msg = '';
						
					}else{

						if($composition_info['contagem_pai'] > $composition_info['contagem_filho']){
							$setor_filho = ControleEstoqueController::getSetDestinoFilho($composition_info['filho']);

							$msg = ' <span style="color:red">(Produto pertence a seguinte composição: '.$composition_info['qrcode_pai'].', ' .$composition_info['nome_pai'].', '.$composition_info['nome_setor_pai'].')</span>';
							$est->setor = $setor_filho;
						}else{
							continue;
						}
						
					}


					// total em cada setor
					if($setorAnterior != $est->setor && $totalSetor > 0){
						$html .= "	<tr>
										<td colspan='6' class='bold right'>Total de itens em " . $setorAnterior . ": </td>
										<td class='bold left'>" . $totalSetor . "</td>
									</tr>";
						$totalSetor = 0;
					}

                    $classe_validade = '';
                    if( !empty( $est->validade ) ){
    					$classe_validade = ( ( $est->validade > 5 ) ? "acima_validade" : 
    											( ( $est->validade <= 5 AND $est->validade > 0 ) ? 'dentro_validade' : 'vencido'  ) );
                                                                        
                    }



					$html .= "	<tr situacao='{$est->situacao}' style='background-color:# !important;'>
									<td>" . $est->setor . "</td>
									<td >" . $est->nome_produto .  "</td>
									<td>" . $est->qrcode. "</td>
									<td class='{$classe_validade}' align='center'>" . ( !empty( $est->validade ) ? $est->validade : ' - ' ) . "</td>									
									<td align='center'>" . ( ( $est->ultima_saida == '00/00/0000 00:00:00') ? ' - ' : $est->ultima_saida ) . "</td>
				                    <td>" . $est->uso . "</td>
						            <td>" . ( empty( $est->status ) ? "Em estoque" : $est->status ) . "".$msg."</td>
								</tr>";
			        $setorAnterior = $est->setor;
			        $totalSetor ++;
			        $totalGeral ++;
			        $totalItensGeral ++;
			        $i++;

				}
				
				
				$consignados = ControleEstoqueController::selectConsignados($_REQUEST['filtro'], $_REQUEST['status'], $_REQUEST['nomeproduto'] , $_REQUEST['validade'], implode('-',array_reverse(explode('/', $_REQUEST['Data1']))), implode('-',array_reverse(explode('/', $_REQUEST['Data2']))) );

				// último total
				if($totalSetor > 0){
					$html .= "	<tr>
									<td colspan='6' class='bold right'>Total de itens em " . $setorAnterior . ": </td>
									<td class='bold left'>" . $totalSetor . "</td>
								</tr>";
				}

				if(!empty($consignados))
				{
					$html .= '
					<tr>
						<td colspan="10"><h4 id="consignados" style="float:left;">Produtos Consignados <i class="fas fa-tag cs-fa"></i></h4></td>
					</tr>
					';
				}

				foreach ( $consignados as $est ){
					
					// total em cada setor
					if($setorAnterior != $est->setor && $totalSetor > 0){
						$html .= "	<tr>
										<td colspan='6' class='bold right'>Total de itens em " . $setorAnterior . ": </td>
										<td class='bold left'>" . $totalSetor . "</td>
									</tr>";
						$totalSetor = 0;
					}

                    $classe_validade = '';
                    if( !empty( $est->validade ) ){
    					$classe_validade = ( ( $est->validade > 5 ) ? "acima_validade" : 
    											( ( $est->validade <= 5 AND $est->validade > 0 ) ? 'dentro_validade' : 'vencido'  ) );
                                                                        
                    }

					

					$html .= "	<tr situacao='{$est->situacao}'>
									<td>" . $est->setor . "</td>
									<td>" . $est->nome_produto .  "</td>
									<td>" . $est->qrcode. "</td>
									<td class='{$classe_validade}' align='center'>" . ( !empty( $est->validade ) ? $est->validade : ' - ' ) . "</td>									
									<td align='center'>" . ( ( $est->ultima_saida == '00/00/0000 00:00:00') ? ' - ' : $est->ultima_saida ) . "</td>
				                    <td>" . $est->uso . "</td>
						            <td>" . ( empty( $est->status ) ? "Em estoque" : $est->status ) . "</td>
								</tr>";
			        $setorAnterior = $est->setor;
			        $totalSetor ++;
			        $totalGeral ++;
			        $totalItensGeral ++;
			        $i++;

				}

				
				if( $i > 0 ){
					if(empty($consignados))
				{
					$html .= '
					<tr>
						<td colspan="10"><h4 id="consignados" style="float:left;">Produtos Consignados <i class="fas fa-tag cs-fa"></i></h4></td>
					</tr>
					<tr>
						<td colspan="10"><h6 id="consignados" style="float:left;">Nunhum Item Consignado.</h6></td>
					</tr>
					';
				}
				    $html .= "
								<tr>
									<td colspan='6' class='bold right dark'>Total de itens no relatório: </td>
									<td class='bold left dark'>" . $totalGeral . "</td>
								</tr>";
				} else{
					$html .= "<tr><td colspan='7' align='center'>Nenhum registro encontrado.</td></tr>";
				}

			} else { // SINTÉTICO
				$html .= "	<table class='tableLinhas'>
								<tr>
									<th>Produto</th>
									<th>Total de produtos</th>
								</tr>";
				$i = 0;
				$setorAnterior = "";
				$proAnterior = '';
				$totalPro = 0;
				$totalSetor = 0;
				$totalGeral = 0;

				/*

				foreach (ControleEstoqueController::select($_REQUEST['filtro'], $_REQUEST['status']) as $est){

					if( $totalGeral > 0  ){

						if( $setorAnterior != $est->setor ){

							$html .= "	<tr>
											<td class='bold right dark'> Total de itens em " . $est->setor . "</td>
											<td class='bold left dark'> " . $totalSetor . "</td>
										</tr>";

							$totalSetor = 0;
							$totalPro = 0;

							$proAnterior = $est->nome_produto;
							$setorAnterior = $est->setor;

						}
						else{

							if( $proAnterior != $est->nome_produto ){

							}
							else{
								$totalPro++;
								$totalSetor++;
								$proAnterior = $est->nome_produto;
								$setorAnterior = $est->setor;								
							}

						}


					}
					else{
						$proAnterior = $est->nome_produto;
						$setorAnterior = $est->setor;
						$totalPro++;
						$totalSetor++;
					    $html .= "	<tr>
										<td>" . $proAnterior . "</td>
										<td>" . $totalPro . "</td>
									</tr>";						
					}

	                $totalGeral++;

				}
				*/

				// último total
				if($totalSetor > 0 && $totalPro > 0 ){
					$html .= "
					           <tr>
									<td>" . $proAnterior . "</td>
									<td>" . $totalPro . "</td>
								</tr>
					           <tr>
									<td class='bold right dark'>" . $setorAnterior . "</td>
									<td class='bold left dark'>" . $totalSetor . "</td>
								</tr>";
				}

				if( $totalGeral > 0 ){
				    $html .= "
					    <tr>
							<td class='bold right dark'>Total de produtos no relatório: </td>
							<td class='bold left dark'>" . $totalGeral . "</td>
						</tr>";
				}else{
				    $html .= "<tr><td colspan='7' align='center'>Nenhum registro encontrado.</td></tr>";
				}


			}
			$html .= "</tbody>";			
			$html .= "</table>";
			echo $html;
		}
		?>
	</div>
<?php
	include "view/helper/rodape.php";

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Soluções em T.I. © 2013
*/