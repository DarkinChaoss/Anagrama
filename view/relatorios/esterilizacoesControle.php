<?php	

if(!AutenticaController::logado()){
	header("Location: home");
	exit;
}

echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));

include "view/helper/cabecalho.php";
?>
<meta charset="utf-8">
<style type="text/css">
	.options-main{
		width: 98%;
		margin: 0 auto;
		height: 100px;
		background-color: #dedede;
		display: flex;
		align-items: center;
	}
	.options{
		display: flex;
		align-items: center;
	}
	.equipamentos-main {
		width: 100%;
		margin: 0 auto;
		display: flex;
		justify-content: ;
	}
	.equipamentos-main .left{
		padding: 1em;
		width: 50%;
		
	}
	.equipamentos-main .right{
		padding: 1em;
		width: 50%;
		
	}
	.table-header{
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	.data-picker{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: flex-start;
		margin-left: 1em;
	}
	.equipment{
		margin-left: 1em;
	}
	.space-left{
		margin-left: 1em;
	}
	.btn-procurar{
		height: 1.4em !important;
		transform: translateY(3px);
		margin-left: 1em;
	}
	.page-title{
		width: 78%;
		margin: 0 auto;
	}
	.page-spacer{
		height: 20vh;
	}

	@media screen and (min-width: 1200px){
		.options-main{
			width: 78%;
		}
		.equipamentos-main {
			width: 80%;
		}
	}
</style>

<?php
	
	
	$search_data = isset($_REQUEST['search_data']) ? $_REQUEST['search_data'] : date('Y-m-d');
	$equipamento = isset($_REQUEST['combo_equip_val']) ? (int) $_REQUEST['combo_equip_val'] : 0;
	$_SESSION['search_data'] = $search_data;
	$_SESSION['equipamento'] = $equipamento;
	// retreive data from itensSolicitação about expurgo to show on table.
	$data_expurgo = ItensSolicitacoesController::getEquipamentDataExpurgo($search_data, $equipamento); // params: data, id do equipamento
	$filtered_data_expurgo = array(); // data to show on table
	$temp_arr_lote  = array(); // data to hold temp values
	foreach ($data_expurgo as $value) {
		if(in_array($value['iso_idequipamento'] . ' ' . $value['iso_loteequipamento'], $temp_arr_lote)){
			continue;
		}
		array_push($filtered_data_expurgo, $value);
		array_push($temp_arr_lote, $value['iso_idequipamento'] . ' ' . $value['iso_loteequipamento']);
	}

	// retreive data from itensSolicitação about esterilização to show on table.
	$data_esterilizacao = ItensSolicitacoesController::getEquipamentDataEsterilizacao( $search_data, $equipamento); // params: data, id do equipamento
	$filtered_data_esterilizacao = array(); // data to show on table
	$temp_arr_lote  = array(); // data to hold temp values
	foreach ($data_esterilizacao as $value) {
		if(in_array($value['iso_idequipamentoet'] . ' ' . $value['iso_lote'], $temp_arr_lote)){
			continue;
		}
		if($value['iso_lote'] == '' && $value['iso_idequipamentoet'] == 0){
			continue;
		}
		array_push($filtered_data_esterilizacao, $value);
		array_push($temp_arr_lote, $value['iso_idequipamentoet'] . ' ' . $value['iso_lote']);
	}
 ?>

<div class="page-title">
	<h1>Controle de Esterilização</h1>
</div>
<div class="options-main">
	<div class="options">
		<label>
		<div class="data-picker">
			DATA:
			<input id="search_data" value="<?php echo DefaultHelper::converte_data($search_data); ?>" class="input data" type="text" name="" placeholder="Escolha uma data">
		</div>
		</label>
		<label>
			<span class="space-left">EQUIPAMENTO:</span>
			<input type="hidden" id="equipamento_id" value="<?php echo $_SESSION['equipamento']; ?>">
			<div class="equipment">
				<?php echo  SolicitacoesHelper::populaComboEEsterilizacao()?>
			</div>
		</label>
		<a href="#" class="btn btn-success btn-procurar">PROCURAR</a>
	</div>
</div>
<div class="equipamentos-main">
	<div class="left">
		<div class="table-header">
			<h4>Expurgo</h4>
			<p>Data <?php echo DefaultHelper::converte_data($search_data); ?></p>
		</div>
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">Equipamento</th>
		      <th scope="col">Lote</th>
		      <th scope="col">Acão</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php   foreach ($filtered_data_expurgo as $item) {?>
			<tr>
		      	<td scope="col"><?= $item['iso_nome_equipamento'] ?></td>
		      	<td scope="col" class="unique"><?= $item['iso_loteequipamento'] ?></td>
		      	<td scope="col">
		      		<a href="#" 
		      		data-lote="<?= $item['iso_loteequipamento'] ?>" 
		      		data-equip="<?= $item['iso_nome_equipamento'] ?>" 
		      		data-equipid="<?= $item['iso_idequipamento'] ?>"
		      		data-date="<?= DefaultHelper::converte_data($search_data)?>" 
		      		class="btn btn-default js_abrir_expurgo">Abrir
		      		</a>
		      	</td>
		    </tr>
			<?php 	} ?>
		  </tbody>
		</table>
	</div>
	<div class="right">
		<div class="table-header">
			<h4>Esterilização</h4>
			<p>Data <?php echo DefaultHelper::converte_data($search_data); ?></p>
		</div>
		<table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">Equipamento</th>
		      <th scope="col">Lote</th>
		      <th scope="col">Acão</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php   foreach ($filtered_data_esterilizacao as $item) {?>
			<tr>
		      	<td scope="col"><?= $item['iso_nome_equipamento'] ?></td>
		      	<td scope="col"><?= $item['iso_lote'] ?></td>
		      	<td scope="col">
		      		<a href="#" 
		      		data-lote="<?= $item['iso_lote'] ?>" 
		      		data-equip="<?= $item['iso_nome_equipamento'] ?>"
		      		data-equipid="<?= $item['iso_idequipamentoet'] ?>" 
		      		data-date="<?= DefaultHelper::converte_data($search_data)?>" 
		      		class="btn btn-default js_abrir_esterilizacao ">Abrir
		      		</a>
		      	</td>
		    </tr>
			<?php 	} ?>
		  </tbody>
		</table>
	</div>
</div>
<div class="page-spacer"></div>
<script src="js/esterilizacaoControle.js"></script>

<?php include "view/helper/rodape.php"; ?>