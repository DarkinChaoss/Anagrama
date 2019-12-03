<?php
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 6, 8));
	
	include "helper/cabecalho.php";
?>

	<script src="js/devolucaoMateriais.js"></script>

	<h1>Devolução de Materiais Não Utilizados</h1>
	
	<div style="display: flex; justify">
		<label>QRCode:</label>
		<input type="text" name="qrcode" id="txQrcode" class="input-medium" autofocus>
		
		<div id="boxQtde" style="margin-left: 25px; display: none;">
			<span style="float:left;">Qtde:
				<input type="number" name="qtde"  oninput="validity.valid||(value=value.replace(/\D+/g, ''))" id="txQtde"  min="1" max="99999" style="text-transform:;" class="input-mini"/>
			<br><br>
			</span>
			<span style="float:left;">
			Disponível:
			<input type="text" name="disponivel" id="disponivel" style="text-transform:;" readonly class="input-mini"/>
			<br><br>
			</span>
			<div style="clear:both !important;"></div>
		</div>
	</div>
	<br>
	<hr>
	
	<div id="wrapper">
		
	</div>
	
<?php
	include "helper/rodape.php";
?>