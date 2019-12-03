<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 9));
	
	include "view/helper/cabecalho.php";
?>
	<style type="text/css">
		.hidediv {
			display: none;
		}
		.chooseReportType {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			z-index: 999999;
			width: 100vw;
			height: 100vh;
			background: rgba(255,255,255,.8);
		}

		.report-modal {
		  	position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -50%);
		  	width: 550px;
		  	height: auto;
		  	background-color: #ffffff;
		  	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}

		.report-modal-header h2{
			text-align: center;
			margin-top: 22px;
		}

		.report-modal-body{
			position: relative;
			top: 0;
			left: 0;
			width: 100%;
			height: auto;
			display: flex;
			justify-content: space-around;
			padding: 1em 0;
		}

		.report-modal-body .left img{
			width: 150px;
		}

		.report-modal-body .right img{
			width: 150px;
		}

		.left, .right {
			cursor: pointer;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
		}

		.left h3 {
			transform: translateX(-15px);
		}

		.report-modal-close {
			position: relative;
			top: -20px;
			left: 530px;
			width: 20px;
			height: 20px;
			background: black;
			color: white;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
		}


		@media print {
			html {
				margin: 0;
				padding: 0;
				height: 100px;
			}
	  		body * {
	    		visibility: hidden;
	  		}
		  	 #section-to-print, #section-to-print * {
		    	visibility: visible;
		    	margin-bottom: 0;
		 	 }
		 	 #section-to-print {
		   		position: absolute;
		    	left: 0;
		    	top: 0;
	  		}

	  		.divTransf{
	  			line-height: 18px;
	  		}
		}
	</style>
	
	<h1>Relatório de Transferência de Produtos</h1>
	
	<form >
		
		<?php 
			$old_data1 = isset($_REQUEST['data1']) ?  $_REQUEST['data1'] : '';
			$old_data2 = isset($_REQUEST['data2']) ?  $_REQUEST['data2'] : '';
			$setor = isset($_REQUEST['setor']) ? $_REQUEST['setor'] : '';
		?>
		<input type="hidden" id="setor" value="<?= $setor ?>">
		<div class="row-fluid" id="form1">
			<div class="span12">
				<div style="display: flex;">
					<div style="display: flex; flex-direction: row;">
						<label style="margin-right: 1em;">Intervalo:</label>
						<input style="margin: 0 1em 1em 0;" type="text" name="data1" id="data1" value="<?= $old_data1 ?>" class="input-small data" maxlength="10" autocomplete="off">	
					</div>
					
					&nbsp;até&nbsp;
					<input style="margin: 0 1em 0 1em;"  type="text" name="data2" id="data2" value="<?= $old_data2 ?>" class="input-small data" maxlength="10" autocomplete="off" style="margin-right: 16px">
					<div style="width: 355px; display: flex; flex-direction: row;">
						<label style="margin: 0 1em 0 0;">Destino:</label> 
						<?php echo SolicitacoesHelper::populaComboSetor(); ?>
					</div>
					<button type="submit" name="submit" value="1" href="#" id="btSearch" class="btn" style="margin: 0 0 11px 0px">
						<i class="icon-search"></i> Procurar
					</button>
				</div>
			</div>
		</div>
	</form>
	
	<hr>

	<?php if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 1){ ?>
	<div id="search-results" class="search-results">
		<?php 

		$data_inicio = DefaultHelper::converte_fullDate($_REQUEST['data1']) . " 00:00:00";
		$data_fim = DefaultHelper::converte_fullDate($_REQUEST['data2']) . " 23:59:59";
		$idsetor = $_REQUEST['setor'];
		$results = SaidaMateriaisController::getSaidasMateriaisRange($data_inicio, $data_fim, $idsetor);
		$table = <<<EOT
		<div style='width:100%; height: 30px; display:flex; justify-content: flex-start; background:#efefef'>
			<div style='width:100%; font-weight: bold;' id='total'></div>
		</div>
		<table class='tableLinhas'>
			<tr>
				<th>DATA</th>
				<th>DESTINO</th>
				<th>USUÁRIO</th>
				<th>RETIRADO POR</th>
				<th>QUANTIDADE</th>
				<th>AÇÃO</th>
			</tr>
EOT;
		echo $table;
		?>

		<?php if($results) { ?> 
			<?php foreach($results as $result) { ?>
				
				<tr class="tr-result">
				    <td style='width: 120px;'><?= DefaultHelper::converte_fullDate($result['sma_data']) ?></td>
				    <td><?= $result['set_nome'] ?></td> 
				    <td><?= $result['sma_idusuario'] ?></td>
				    <?php $retiradopor = $result['sma_retirado_por'] == 'sistema' ?  $result['sma_idusuario'] : strtoupper(utf8_decode($result['sma_retirado_por'])) ?>
				    <td><?= $retiradopor ?></td>
				    <td style="width: 30px;text-align: center; font-size: 1.2em;"><?= strtoupper(utf8_decode($result['qtde_itens'])) ?></td>
				    <td style='width: 150px; text-align: center'>
				    	<a onclick="showEsterilizationDetails(<?= $result['sma_id'] ?>);" class='btn'>
				    		<i class='icon-arrow-right'></i>
				    		<span id="<?php echo 'btn'.$result['sma_id'] ?>"> Abrir</span>
				    		<span style="display: none;" id="<?php echo 'btn'.$result['sma_id'].'close' ?>"> Fechar</span>
				    	</a></td>
  				</tr>
  				<tr id="<?= $result['sma_id'] ?>">
  					<td colspan="12" style="padding: 0; margin: 0; border: none;">
  						<div style="width: 100%; height: auto; background: #bad3e0;" class="hidediv transf-details"></div>
  						<input type="hidden" id="<?php echo 'toggle'. $result['sma_id']?>" value="off">
  					</td>
  				</tr>
  				
			<?php } ?>
  		<?php } ?>

		</table>
	</div>
<?php } ?>

	<div id="section-to-print" style=" width: 96%; height: auto; display: flex; flex-direction: column; justify-content: center; padding: 1em; page-break-after: always;">
		<!-- precisa inserir os css e js aqui para que sejam carregados junto da div para impressão -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/print.css">
		<script src="js/print.js"></script>
		<?php
		$logo = (($_SESSION['usu_cli_logo'] != '') ? $_SESSION['usu_cli_logo'] : 'logoEmpresa');
		$imprimir = isset($_REQUEST['imprimir']) ? true : false;
		$imprimirSintetico = isset($_REQUEST['imprimirSintetico']) ? true : false;
		//if($_REQUEST['gerar'] == 1){
		if($imprimir) {

			$itens = ItensSaidaController::selectIntensBySolicitacao($_REQUEST['printTransf']); //O id é da solicitação

			function getSetorName($id){
				return ItensSaidaController::getSetorName($id);
			}

			 $transf_data = SaidaMateriaisController::getTransfData($_REQUEST['printTransf'])[0];
			 $data_da_transf = DefaultHelper::converte_fullDate($transf_data['sma_data']);
			 $usu_nome = $transf_data['usu_nome'];
			 $sma_retirado_por = strtoupper(utf8_decode($transf_data['sma_retirado_por']));

			$etq = <<<EOT

		<script>
			$('#btPrint').show();
			$('#btSearch').hide();
			$('#search-results').hide();
		</script>
		<div class='onlyPrint'>
			<div class='row-fluid'>
				<img src='img/tms.png' width='100px' class='pull-left'>
				<img src='img/{$logo}.png' width='120px' class='pull-right'>
			</div>
				<h4 style='margin-top: 0;'>Relatório de Transferência de Produtos</h4>
				<div class='divTransf' style='margin: 2em 0;'>
					<p>Data da transferência: {$data_da_transf}</p>
					<p>Usuário: {$usu_nome}</p>
					<p>Retirado por: {$sma_retirado_por} </p>
					<p>Ass: _________________________________________ .</p>
				</div>
				
		</div>
		<table class='tableLinhas' style='margin-top: -1em;'>
			<tr>
				<th>Produto</th>
				<th>QRCode</th>
				<th>Origem</th>
				<th>Destino</th>
			</tr>
EOT;
		echo $etq;
		?>
		<?php foreach ($itens as $item) { ?>
			<tr>
			    <td><?= $item['isa_nome_produto'] ?></td>
			    <td><?= $item['isa_qrcode_produto'] ?></td> 
			    <td><?= getSetorName($item['isa_idsetorigem']) ?></td>
			    <td><?= getSetorName($item['isa_idsetordestino']) ?></td>
  			</tr>
		<?php } ?>
			
		</table>
		</div>
			
		<?php echo "<script>window.print(); setTimeout(function(){ window.close(); }, 1000);</script>"; ?>	
	<?php } ?>	
	<?php 

	if($imprimirSintetico) {

			$itens = ItensSaidaController::selectIntensBySolicitacao($_REQUEST['printTransf']); //O id é da solicitação

			function getSetorName($id){
				return ItensSaidaController::getSetorName($id);
			}

			 $transf_data = SaidaMateriaisController::getTransfData($_REQUEST['printTransf'])[0];
			 $data_da_transf = DefaultHelper::converte_fullDate($transf_data['sma_data']);
			 $usu_nome = $transf_data['usu_nome'];
			 $sma_retirado_por = strtoupper(utf8_decode($transf_data['sma_retirado_por']));

			$etq = <<<EOT

		<script>
			$('#btPrint').show();
			$('#btSearch').hide();
			$('#search-results').hide();
		</script>
		<div class='onlyPrint'>
			<div class='row-fluid'>
				<img src='img/tms.png' width='100px' class='pull-left'>
				<img src='img/{$logo}.png' width='120px' class='pull-right'>
			</div>
				<h4 style='margin-top: 2em;'>Relatório de Transferência de Produtos</h4>
				<div style='margin: 2em 0;'>
					<p>Data da transferência: {$data_da_transf}</p>
					<p>Usuário: {$usu_nome}</p>
					<p>Retirado por: {$sma_retirado_por} </p>
					<p>Ass: _________________________________________ .</p>
				</div>
				
		</div>
		<table class='tableLinhas' style='margin-top: 1em;'>
			<tr>
				<th>Produto</th>
				<th>Quantidade</th>
			</tr>
EOT;
		echo $etq;
		$names = array();
		foreach ($itens as $iten) {
			array_push($names, $iten['isa_nome_produto']);
		}

		$count_values = array_count_values($names);
		
		?>

		<?php foreach ($count_values as $idx => $value ) { ?>
			<tr>
			    <td><?= $idx?></td>
			    <td><?= $value ?></td> 
  			</tr>
		<?php } ?>
			
		</table>
		</div>
			
		<?php echo "<script>window.print(); setTimeout(function(){ window.close(); }, 1000);</script>"; ?>	
	<?php } ?>
	</div>



	
<div class="chooseReportType" id="chooseReportType">
	<input type="hidden" id="transfToPrint">
	<div class="report-modal">
		<div class="report-modal-close">X</div>
		<div class="report-modal-header">
			<h2>Escolha o tipo de impressão</h2>
			<hr>
		</div>
		<div class="report-modal-body">
			<div class="left" onclick="imprimirAnalitico()">
				<img src="img/report-analitic.png">
				<h3>Analítico</h3>
			</div>
			<div class="right" onclick="imprimirSintetico()">
				<img src="img/report-sintetic.png">
				<h3>Sintético</h3>
			</div>
		</div>
	</div>
</div>







<?php
	include "view/helper/rodape.php";
?>

<script type="text/javascript">

	function imprimir(id){

		$('#transfToPrint').val(id);
		$('#chooseReportType').css({
			display: 'block',
		});
		
	}

	function closeModal(){
		$('#chooseReportType').css({
			display: 'none',
		});
	}

	function imprimirAnalitico(){
		var id = $('#transfToPrint').val();
		console.log('Imprimimindo relatório analítico, intens da Transferência: ' + id);
		var janela = window.open('produtosTransferencia?imprimir=true&printTransf='+id, '_blank');
		closeModal();
	}

	function imprimirSintetico(){
		var id = $('#transfToPrint').val();
		console.log('Imprimimindo relatório Sintético, intens da Transferência: ' + id);
		var janela = window.open('produtosTransferencia?imprimirSintetico=true&printTransf='+id, '_blank');
		closeModal();
	}

	function showEsterilizationDetails(id) {
			

			var div = $('#'+id+' td div');

			if($('#toggle'+id).val() == 'off'){
				$('#btn' + id).hide();
				$('#btn' + id + 'close').show();
			}else{
				$('#btn' + id).show();
				$('#btn' + id + 'close').hide();
				
			}
			
			div.toggleClass('hidediv');
			
			if($('#toggle'+id).val() == 'off'){

				$('#toggle'+id).val('on')
			}else{
				$('#toggle'+id).val('off')
				return
			}
			
			

				$.post('RelatorioTransfHelper', {acao: 'buscar', id:id}).done(function(data, textStatus, xhr) {

				//alert('done');
				data = JSON.parse(data);
				var div = $('#'+id+' td div');
				
				var html = '<div style="width:100%; height:30px; background:#7b7b7b; padding: .4em 0; display:flex; justify-content: space-between; align-items:center">\
				<div style="width:55%; display: flex; justify-content: flex-end">\
					<span style="color:white; font-size: 1.5em;">ITENS DA TRANSFERÊNCIA</span>\
				</div>\
					<div style="width:150px; display: flex; justify-content: center;" >\
					<a  class="btn btn-success" id="imprimir" style="margin-right:4%;" onclick="imprimir('+id+')"><i class="icon-print"></i> Imprimir </a>\
					</div>\
				</div>';

				html += '<div style="width:100%; height:30px; border-bottom:1px solid black; display:flex; justify-content: space-between; align-items:center">\
				<div style="width:25%; font-size: 1.2em;  padding-left: 1em;"><b>Produto</b></div>\
				<div style="width:25%; font-size: 1.2em;  padding-left: 1em;"><b>QRCode</b></div>\
				<div style="width:25%; font-size: 1.2em;  padding-left: 1em;"><b>Origem</b></div>\
				<div style="width:25%; font-size: 1.2em;  padding-left: 1em;"><b>Destino</b></div>\
				</div>';

				var html2 = '';

				var selects = [];
					var select_options = $("#slSetor").find("option"); 
					select_options.each(function(index, el) {
						selects[el.value] = el.innerText;
					});

				$.each(data, function(index, val) {
						
						var origem = '';
						var destino = ''; 
						selects.map(function(atual, indice, arr){
						if(indice == val.isa_idsetorigem){origem = atual};
						if(indice == val.isa_idsetordestino){destino = atual};
						if(indice == 0){origem = 'PRODUTO NOVO'}
						});	

					 html2 += '<div style="width:100%; border-bottom: 1px solid black; height:30px; display:flex; justify-content: space-between; align-items:center">\
					<div style="width:25%;  padding-left: 1em;">'+val.isa_nome_produto+'</div>\
					<div style="width:25%;  padding-left: 1em;">'+val.isa_qrcode_produto+'</div>\
					<div style="width:25%;  padding-left: 1em;">'+origem+'</div>\
					<div style="width:25%;  padding-left: 1em;">'+destino+'</div>\
					</div>';
					});

					div.html(html + html2);
				
				});

		}



	$(document).ready(function(){

		$('.report-modal-close').click(function(event) {
			$('#chooseReportType').css({
				display: 'none',
			});
		});

		function dataAtualFormatada(){
   		 	var data = new Date(),
	        dia  = data.getDate().toString(),
	        diaF = (dia.length == 1) ? '0'+dia : dia,
	        mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
	        mesF = (mes.length == 1) ? '0'+mes : mes,
	        anoF = data.getFullYear();
    		return diaF+"/"+mesF+"/"+anoF;
		}

		if($('.data').val().length == 0){
			$('.data').val(dataAtualFormatada());	
		}
		
		var setor = $('#setor').val();

		if(setor == 0){
			$('#total').text('TRANSFERÊNCIAS TODOS OS SETORES NO PERÍODO TOTAL: ' + $('.tr-result').length);	
		}

		if(setor != 0){
			$('#total').text('TRANSFERÊNCIAS PARA O SETOR\
			 ' + $("#slSetor option[value='"+setor+"']").text() + ' ENTRE: '+ $('#data1').val()+' E '+ $('#data2').val() +' TOTAL:  ' + $('.tr-result').length)
		}

		$('#slSetor option[value="'+setor+'"]').prop("selected", true);

	});
</script>

<?php
/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 * 
 * Brothers Soluções em T.I. © 2013
*/
?>