<?php
	include "helper/cabecalho.php";
	
	error_log("HOME");
	
	if(!AutenticaController::logado()){
		echo "	<script>
					$('#formLogin').show();
					$('#menu').hide();
					$('#opcoesUsuario').hide();
				</script>";
	} else {
		echo "	<script>
					$('#formLogin').hide();
					$('#menu').show();
					$('#opcoesUsuario').show();
				</script>";
	}
	
	if($_SESSION['usu_nivel'] == 3) {
		// tela inicial do etiquetador
		
		// se usuário CC do Centrinho USP, redireciona para tela Saída de Materiais
		if ($_SESSION['usu_id'] == 34) {
			echo "	<script>
						location.href = 'saidaMateriais';
					</script>";
		}
		// ... senão, redireciona para tela Etiquetagem 
		else {
			echo "	<script>
						location.href = 'etiquetagem';
					</script>";
		}
		
	}if($_SESSION['usu_nivel'] == 7) {
		//tela inicial conferente centro cirurgico
		
		echo "	<script>
						location.href = 'ConferenciaMaterial';
					</script>";
			
	}else {	

		if(isset($_SESSION['usu_login'])){
?>

	<div style="text-align: right;">
		<div style="margin-left: -10px; width: 16%; height: 80px; background: url(img/<?php echo (($_SESSION['usu_cli_logo'] != "") ? $_SESSION['usu_cli_logo'] : "logoEmpresa"); ?>.png)  no-repeat; background-size: contain;"></div>

		<div style="text-align: center; margin-bottom:15% !important;">
		  <p class="title-shortchut">Acesso Rápido</p>
		  <br><br>
			<div class="row-span">
			  <div class="span4-cs shortcut">
			  	<i class="fas fa-file-alt icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Solicitação esterilização</p>
			  	<br><br>
			  	<p class="sub-title">Faça uma solicitação de esterilização.</p>
			  	<a href="solicitacoes" class="btn">Ir até solicitações</a>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fas fa-boxes icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Conferência de montagem</p>
			  	<br>
			  	<p class="sub-title">Faça a conferência aqui.</p>
			  	<br>
				<form id="formBusca">
					<input type="text" name="qrcode" id="txqrcodebusca" placeholder="Qr code" class="input-medium" autofocus>
					<br><br>
					<button title="Buscar" type="button" onclick="buscaMaterial()" class="btn btn-primary" style="margin-top: -10px;">
						<i class="icon-search icon-white"></i>
					</button>		
					<button onclick="$('#formBusca').reset();" class="btn btn-warning" style="margin-top: -10px;">
						<i class="icon-remove icon-white"></i>
					</button>
				</form>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fa-tags" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Etiquetagem</p>
			  	<br><br>
			  	<p class="sub-title">Faça todo o processo de etiquetagem.</p>
			  	<br>
			  	<a href="etiquetagem" class="btn">Ir até etiquetagem</a>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fas fa-box-open icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Transferência de estoque</p>
			  	<br><br>
			  	<p class="sub-title">Transfira produtos entre estoques.</p>
			  	
			  	<a href="TransferenciaEstoque" class="btn">Ir até Transferências</a>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fas fa-check icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Conferência após procedimento</p>
			  	<br>
			  	<p class="sub-title">Confira após o procedimento.</p>
			  	<br>
			  	<a href="ConferenciaMaterial" class="btn">Ir até Conferencia</a>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fa-tag icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Histórico do produto</p>
			  	<br>
			  	<p class="sub-title">Obtenha o histórico do produto.</p>
			  	<br>
				<form class="form-search pull-right" method="GET" action="produtosHistorico?filtro=filtro&gerar=1">
					<input type="text" name="filtro" placeholder="Qr code" class="input-medium search-query" autofocus>
					<input type="hidden" name="gerar" class="input-medium search-query" value="1" autofocus>
					<br><br>
					<button type="submit" class="btn">Buscar</button>
				</form>
			  </div>
			  <div class="span4-cs shortcut">
			  	<i class="fas fa-box icons-access" style="font-size:30px !important; color:#828282 !important;"></i>
			  	<br><br>
			  	<p class="title-shortchut">Controle de estoque</p>
			  	<br><br>
			  	<p class="sub-title">Acesse o controle de estoque.</p>
			  	<br><br>
			  	<a href="produtosEstoque" class="btn">Ir até controle</a>
			  </div>
			  <br><br><br>
			</div>
			<br><br><br><br><br>
		<!--
		  <p class="title-shortchut">Central de Notificações</p>
		  <br><br>	
			<div class="row-shortcut row-central">
			  <div class="central-notification">
				<div class="central central-right">
				   <br>
					<div class="texts">
						<p class="title-texts titles-right">Titulo</p>
						<p class="txt-texts">Ponha o que desejtar aqui </p>
					</div>
					<div class="texts">
						<p class="title-texts titles-right">Titulo.</p>
						<p class="txt-texts">Ponha o que desejtar aqui</p>
					</div>
					<div class="texts">
						<p class="title-texts titles-right">Titulo.</p>
						<p class="txt-texts">Ponha o que desejtar aqui. </p>
					</div>
			    </div>		  	
			  </div>
			 </div> 
			-->
		</div>
	</div>
	<br><br>


<?php			
		}else{
?>


	<br><br><br><br>
	<div style="text-align: center;">
		<img src="img/logoEmpresa.png">
	</div>
	<br><br><br><br>

<?php
		}

		// tela inicial padrão
?>

	<script type="text/javascript" src='js/conferindoComposto.js'></script>
<?php
		// [usu_id] => 2 [usu_masterclient] => 6 [usu_login] => DURAZZO [usu_nivel] => 4 [usu_referencia] => 1 [usu_cli_logo] => 
		// [usu_id] => 17 [usu_masterclient] => 801 [usu_login] => CENTRINHO [usu_nivel] => 5 [usu_referencia] => 801 [usu_cli_logo] => clients/CentrinhoLogo
		//print_r($_SESSION);
	}
	
	include "helper/rodape.php";
?>