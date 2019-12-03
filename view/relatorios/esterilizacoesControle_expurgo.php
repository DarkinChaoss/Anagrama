<link rel="shortcut icon" type="image/png" href="img/tms_icon.png"/>

<title><?php echo getenv("PAGE_HEADER"); ?></title>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/esterilizacoesControle.css">
<link rel="stylesheet" href="css/datepicker.css">
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen"
     href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">

<?php
// data=09/11/2019 &equipment_id=7 &lote=lote_equip_1550 &equipment=TERMODESINFECTORA%2002
// Request variables
$date = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
$lote = isset($_REQUEST['lote']) ? $_REQUEST['lote'] : '';
$equipment = isset($_REQUEST['equipment']) ? $_REQUEST['equipment'] : '';
$equipment_id = isset($_REQUEST['equipment_id']) ? $_REQUEST['equipment_id'] : '';

// Variables and info
$logo_url = $_SESSION['usu_cli_logo'] != "" ? $_SESSION['usu_cli_logo'] : "logoEmpresa";
$responsavel_tecnico /*name*/ = ItensSolicitacoesController::responsavel_tecnico_expurgo($lote, $equipment_id, $date);
$itensData = ItensSolicitacoesController::getItensWhereExpurgo($equipment_id, $lote, $date);
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
// Remove duplicated from $itens_names
$itens_names = array_unique($itens_names);


// insert empty entries to fill $itens_names array til 15 registers else do nothing
if((15 - count($itens_names)) > 0){
	$i = 15 - count($itens_names);
	while ($i >= 0) {
		array_push($itens_names, '');
		$i--;
	}
}

?>

<div id="section-to-print">
<div class="cl-main">
<div class="cl-header">
	<div>
		<h4>Central de Materiais e Esterilização</h4>
		<h5 class="sub-title">Registro de Funcionamento da Lavadora</h5>
	</div>
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
		<input type="hidden" id="tipo_carga_expurgo">
		<div class="inputs">
			<div><input type="checkbox" class="tipo_carga_expurgo" name="plastico"> <span>Plástico</span></div>
			<div><input type="checkbox" class="tipo_carga_expurgo" name="silicone"> <span>Silicone</span></div>
			<div><input type="checkbox" class="tipo_carga_expurgo" name="inox"> <span>Inox</span></div>
			<div><input type="checkbox" class="tipo_carga_expurgo" name="vidro"> <span>Vidro</span></div>
			<div><input type="checkbox" class="tipo_carga_expurgo" name="termosensivel"> <span>Misto</span></div>
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
	      <th class="width-80p" scope="col"><p>Itens</p></th>
	      <th class="" scope="col"><p>Quantidade</p></th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php foreach ($itens_names as $item) { ?>
	  		<tr class="itens-rows">
	      		<td><p><?php echo $item; ?></p></td>
	      		<td><p><?php echo $itens_count[$item]; ?></p></td>
	    	</tr>
	 	<?php } ?>
	  </tbody>
	</table>
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
	 $('.datetimepickerT').datetimepicker({
        format: 'dd/MM/yyyy hh:mm:ss',
        language: 'pt-BR'
     });
</script>