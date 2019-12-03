<link rel="shortcut icon" type="image/png" href="img/tms_icon.png"/>
<title><?php echo getenv("PAGE_HEADER"); ?></title>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/esterilizacoesControle.css">
<link rel="stylesheet" href="css/datepicker.css">
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen"
     href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<?php
// Request variables
$date = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
$lote = isset($_REQUEST['lote']) ? $_REQUEST['lote'] : '';
$equipment = isset($_REQUEST['equipment']) ? $_REQUEST['equipment'] : '';
$equipment_id = isset($_REQUEST['equipment_id']) ? $_REQUEST['equipment_id'] : '';
if($_REQUEST['print'])

// Variables and info
$logo_url = $_SESSION['usu_cli_logo'] != "" ? $_SESSION['usu_cli_logo'] : "logoEmpresa";
$logo_url = $_SESSION['usu_cli_logo'];
$responsavel_tecnico /*name*/ = ItensSolicitacoesController::responsavel_tecnico_esterilizacao($lote, $equipment_id, $date);
$itensData = ItensSolicitacoesController::getItensWhere($equipment_id, $lote, $date);
$itens_names = array();
$box_and_single = array();
$total_singles = 0;
$total_boxes = 0;
// Separate names from other info
foreach ($itensData as $item) {
	array_push($itens_names, $item['item_nome']);
	array_push($box_and_single, $item['is_box']);
}
// Count boxes and single items
foreach ($box_and_single as $value) {
	if($value == 0){
		$total_singles++;
	}else{
		$total_boxes++;
	}
}
// Count itens by the name ex. ['item_sample'] => 10
$itens_count = array_count_values($itens_names);
$itens_names = array_unique($itens_names);
// Remove duplicated from $itens_names
$itens_names_first_group = array_slice($itens_names, 0, 15); // First 15 itens names

// insert empty entries to fill $itens_names array til 15 registers else do nothing
if((15 - count($itens_names_first_group)) > 0){
	$i = 15 - count($itens_names_first_group);
	while ($i >= 0) {
		array_push($itens_names_first_group, '');
		$i--;
	}
}
// Rest of the itens
$itens_names_second_group = array_slice($itens_names, 15);

?>



<div id="section-to-print">
<div class="cl-main">
<div class="cl-header">
	<h4>Registro de funcionamento da autoclave</h4>
	<img src="img/<?php echo $logo_url ?>.png">
</div>

	<table class="table table-bordered">
	  <thead>
	    <tr>
	      <th scope="col"><p>Data</p></th>
	      <th scope="col"><p>Aparelho</p></th>
	      <th scope="col"><p>Ciclo</p></th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <input type="hidden" id="data" value="<?php echo $date; ?>">
	      <td class="width-25p"><p id="data"><?php echo $date ?></p></td>
	      <td class="width-50p"><p id="equipment"><?php echo $equipment ?></p></td>
	      <td><p id="lote"><?php echo $lote; ?></p></td>
	    </tr>
	  </tbody>
	</table>

	<div>
		<p class="bold">Tipo de Carga</p>
		<input type="hidden" id="tipo_carga">
		<div class="inputs">
			<div><input type="checkbox" class="tipo_carga" name="bowiedick"> <span>Bowie Dick</span></div>
			<div><input type="checkbox" class="tipo_carga" name="instrumental"> <span>Instrumental</span></div>
			<div><input type="checkbox" class="tipo_carga" name="pacote"> <span>Pacote (roupa)</span></div>
			<div><input type="checkbox" class="tipo_carga" name="termosensivel"> <span>Termosensível</span></div>
		</div>
	</div>

	<table class="table table-bordered table2">
	  <thead>
	    <tr>
	      <th scope="col"><p>Temperatura</p></th>
	      <th scope="col"><p>Início do cíclo</p></th>
	      <th scope="col"><p>Final do cíclo</p></th>
	      <th scope="col"><p>Contém Implantes</p></th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td><input type="text" id="temperatura"></td>
	      <td>
	      	<div class="datetimepicker" class="input-append date" >
		      <input type="text" class="large-input" id="ini_ciclo">
		      <span class="add-on">
		        <i class="icon-calendar" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
		      </span>
			</div>
	      </td>
	      <td>
	      	<div class="datetimepicker" class="input-append date" >
		      <input type="text" class="large-input" id="final_ciclo">
		      <span class="add-on">
		        <i class="icon-calendar" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
		      </span>
			</div>
	      </td>
	      <td class="cl-reset-margin-padding">
	      	<div class="cl-flex-row">
	      		<input type="hidden" id="contem_implantes">
	      		<div><input type="checkbox" class="contem_implantes" id="implantes_sim"> <span>Sim</span></div>
	      		<div><input type="checkbox" class="contem_implantes" id="implantes_nao"> <span>Não</span></div>
	      	</div>
	      </td>
	    </tr>
	  </tbody>
	</table>

	<div>
		<p class="h5_reseted bold">Responsável</p>
		<p class="p_border_bottom"><?php echo $responsavel_tecnico; ?></p>
	</div>

	<div class="sector-separator"></div>
	<table class="table table-bordered cl-products">
	  <thead>
	    <tr>
	      <th class="width-60p" scope="col"><p>Itens</p></th>
	      <th class="width-15p" scope="col"><p>Quantidade</p></th>
	      <th class="width-25p" scope="col"><p>Etiqueta de controle de ciclo</p></th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php foreach ($itens_names_first_group as $item) { ?>
	  		<tr class="itens-rows">
	      		<td><p><?php echo $item; ?></p></td>
	      		<td><p><?php echo $itens_count[$item]; ?></p></td>
	      		<td class="last-child"><p></p></td>
	    	</tr>
	 	<?php } ?>
	  </tbody>
	</table>
	<?php if(count($itens_names) > 15){ ?>
		<span id="span-continuacao-up" >***continua na próxima página...</span>
	<?php } ?>
	
	<div class="sector-separator">
		<p class="bold" >Bowie Dick </p> 
	</div>

	<table class="table table-bordered">
	  <thead>
	    <tr>
	      <input type="hidden" id="liberado">
	      <th scope="col"><p>Liberado?</p></th>
	      <th scope="col"><p>Responsável pela leitura final</p></th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td class="cl-reset-margin-padding width-15p">
	      	<div class="cl-flex-row">
	      		<div class="space-left"><input class="liberado" id="liberado_sim" type="checkbox"> <span>Sim</span></div>
	      		<div><input class="liberado" id="liberado_nao" type="checkbox"> <span>Não</span></div>
	      	</div>
	      </td>
	      <td><input class="large-input" type="text" id="resp_leitura_final"></td>
	    </tr>
	  </tbody>
	</table>

	<div class="sector-separator">
		<p class="bold" >Liberador da carga</p> 
	</div>

	<table class="table table-bordered">
	  <thead>
	    <tr>
	      <th scope="col"><p>Horário da retirada de carga</p></th>
	      <th scope="col"><p>Leitura do resultado</p></th>
	      <th scope="col"></th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td><input class="large-input" type="text" id="horario_retirada"></td>
	      <td><input class="large-input" type="text" id="leitura_resultado"></td>
	      <td>
	      	<div class="square-bordered">
	      		<p>Fixar indicador aqui</p>
	      	</div>
	      </td>
	    </tr>
	  </tbody>
	</table>
	<label for="resp_retirada" >
		<div>
			<p class="bold">Responsável pela retirada da carga</p>
			<input id="resp_retirada" type="text" class="large-input"><br><p style="transform: translateY(-15px);">___________________________________________________________</p>
		</div>
	</label>
	<div class="sector-separator">
		<p class="bold">Indicador biológico</p>
	</div>
	<table class="table table-bordered">
	  <thead>
	    <tr>
	      <th scope="col"><p>Responsável pela incubação</p></th>
	      <th scope="col"><p>Data</p></th>
	      <th scope="col"><p>Horário</p></th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <td><p><input id="resp_incubacao" type="text" class="large-input"></p></td>
	      <td>
	      	<div class="datetimepickerD" class="input-append date" >
		      <input id="incub_data" type="text" class="large-input">
		      <span class="add-on">
		        <i class="icon-calendar" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
		      </span>
			</div>
	      </td>
	      <td>
	      	<div class="datetimepickerT" class="input-append date" >
		      <input id="incub_horario" type="text" class="large-input">
		      <span class="add-on">
		        <i class="icon-calendar" data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
		      </span>
			</div>	
	      </td>
	    </tr>
	    <tr>
	      <td class="width-25p">
	      	<div class="cl-flex-row-bottom">
	      		<div id="up"><p>Resultado Final</p></div>
	      		<div id="down">
	      			<input type="hidden" id="resultado">
	      			<div><input class="resultado" type="checkbox" id="resultado_sim"> <span>Positivo</span></div>
	      			<div><input class="resultado" type="checkbox" id="resultado_nao"> <span>Negativo</span></div>
	      		</div>
	      	</div>
	      </td>
	      <td><div class="square-bordered">
	      		<p>Etiqueta contra teste</p>
	      	</div></td>
	      <td> <div class="square-bordered">
	      		<p>Etiqueta teste</p>
	      	</div></td>
	    </tr>
	  </tbody>
	</table>
	<label for="resp_leitura_final_last">
		<div>
			<p class="bold">Responsável pela leitura final</p>
			<input id="resp_leitura_final_last" type="text" class="large-input"><br><p style="transform: translateY(-15px);">___________________________________________________________</p>
		</div>
	</label>
	<div class="totals">
		<p>Total caixa: <?php echo $total_boxes; ?>  Total avulso: <?php echo $total_singles; ?></p>
	</div>
</div>
<?php if(!empty($itens_names_second_group)){ ?>
	<div class="rest_of_itens">
		<table class="table table-bordered cl-products">
		  <thead>
		    <tr>
		      <th class="width-60p" scope="col"><p>Itens <span id="span-continuacao">(continuação...)</span></p></th>
		      <th class="width-15p" scope="col"><p>Quantidade</p></th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php foreach ($itens_names_second_group as $item) { ?>
		  		<tr class="itens-rows">
		      		<td><p><?php echo $item; ?></p></td>
		      		<td><p><?php echo $itens_count[$item] ?></p></td>
		    	</tr>
		 	<?php } ?>
		  </tbody>
		</table>
	</div>
<?php } ?>
</div>
<div class="spacer-div"></div>

<div id="save_msg">Registro salvo com sucesso!</div>
<button id="save" class="print-btn save">Salvar</button>

<button id="print" class="print-btn">Imprimir</button>
<button id="back" class="print-btn back">Voltar</button>

<script src="js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>
<script src="js/esterilizacaoControle.js"></script>
<script type="text/javascript">
	 $('.datetimepicker').datetimepicker({
        format: 'dd/MM/yyyy hh:mm:ss',
        language: 'pt-BR'
     });
	 $('.datetimepickerD').datetimepicker({
        format: 'dd/MM/yyyy',
        language: 'pt-BR'
     });
	 $('.datetimepickerT').datetimepicker({
        format: 'hh:mm:ss',
        language: 'pt-BR'
     });
</script>