<?php
	if(isset($_GET['populate'])){
		$produto = ProdutosConsignadoController::getProdutoConsignado($_GET['id']);
		$detail = trim($produto->pro_detailproduct);
	}

	if($_POST['acao'] == "getPro"){
		$pro = ProdutosConsignadoController::getProdutosConsignados($_POST['id']);
		die(json_encode($pro));
	}

    if(isset($_POST['acao']) && $_POST['acao'] == 'getInfoProduto' ){
        $produto = ProdutosConsignadoController::getProdutoConsignadoByQrCode($_POST['qrcode']);
        
        if( $produto->pro_id ){
            $infoProduto    = $produto->pro_nome
            . (($produto->pro_diametrointerno != "") ? ", ".$produto->pro_diametrointerno : "")
            . (($produto->pro_curvatura != "") ? ", ". $produto->pro_curvatura : "")
            . (($produto->pro_calibre != "") ? ", ". $produto->pro_calibre : "")
            . (($produto->pro_comprimento != "") ? ", ". $produto->pro_comprimento : "");
            
            die( $infoProduto );
        }
        die( 'N' );
    }
    
    if(isset($_POST['acao']) && $_POST['acao'] == 'geraCodigo' ){
        die(ProdutosConsignadosHelper::geraCodigoProdutoConsignados());
    }

	// navegação da paginação
	if (isset($_POST['pag'])) {
		if($_POST['acao'] == "lista"){
			$res = ProdutosConsignadosHelper::listaProdutosConsignados($_POST['buscar'], $_POST['pag'], $_POST['descart']);
		} else {
			$res = ProdutosConsignadosHelper::paginacao($_POST['buscar'], $_POST['pag'], $_POST['descart']);
		}
		die($res);
	}
	
	if(isset($_POST['removerotulo'])){
		if(unlink("img/rotulos/" . $_POST['removerotulo'] . ".jpg"))
			die("OK");
		else
			die("ERRO");
	}
	
	if (isset($_POST['nome'])) {
	           
        $nome = trim( $_POST['nome'] );     
        $_POST['qrcode'] = strtoupper( $_POST['qrcode'] );
                
		// $nomes = NomesProdutosConsignadoController::getNomesProdutos( "nop_nome = '{$nome}'" );

        /*
        $val = false;
                        
		if( !empty( $nomes ) ){
            $val = true;		  
		}
        */
		// $val ==
		if( true ){

			if(empty($_POST['id'])) {
				$res = ProdutosConsignadoController::insert($_POST);
				$_SESSION['lastidgrupo'] = $_POST['grupomaterial'];
			} else{
				$res = ProdutosConsignadoController::update($_POST);
			}

			if($res) {
				die("OK");
			} else{
				die("ERRO");
			}

		}
		else{
			die("ERRO3");
		}
	}
	
	if(isset($_GET['delete'])){
		if(ProdutosConsignadoController::delete($_GET['id']))
			die("OK");
		else
			die("ERRO");
	}
	
	if($_POST['acao'] == "repetido"){
		$id = ($_POST['id'] == "") ? 0 : $_POST['id'];
		$arr = ProdutosConsignadoController::getProdutosConsignado("pro_qrcode = '" . $_POST['qrcode'] . "' AND pro_id <> " . $id . " AND pro_datadevolucao = 0000-00-00");
		if($arr[0] == ""){
			// também verifica se produto não está apagado no sistema, para evitar fraude
			$arr = ProdutosConsignadoController::getProdutosConsignadoApagados("pro_qrcode = '" . $_POST['qrcode'] . "' AND pro_id <> " . $id);
			if($arr[0] == ""){
				die("OK");
			} else {
				die("APAGADO");
			}
		} else {
			die("ERRO");
		}
	}
	
	if(!AutenticaController::logado()){
		header("Location: home");
		exit;
	}
	
	echo DefaultHelper::acessoPermitido(array('x', 2, 4, 5, 8));
	
	include "helper/cabecalho.php";
	
	// REPLICAR PRODUTOS
	if(isset($_POST['acao']) && $_POST['acao'] == 'salvarReplicar' ){
	    
	    $rtn = 'OK';
	    
	    $qrCode = $_POST['txQrcodeReplicar_origem'];
	    $arrProdutos_novos = $_POST['txQrcodeReplicar_novo'];
	    
	    $objProduto = ProdutosConsignadoController::getProdutoConsignadoByQrCode($qrCode);
	    
	    foreach ( $arrProdutos_novos as $qrCode_novo ){
	        $qrCode_novo = trim($qrCode_novo);
	        if( $qrCode_novo != '' ){
	            // VERIFICA SE QRCODE JÁ EXISTE
	            $verifica = ProdutosConsignadoController::getProdutoParaSolicitacao($qrCode_novo);
	            
	            if( $verifica->pro_id ){
	                if( $rtn == 'OK' ){
	                    $rtn = 'Os seguintes códigos já existem, por isso não foram cadastrados: '. $qrCode_novo;
	                }else{
	                    $rtn .= ', '.$qrCode_novo;
	                }
	            }else{
	                
	                $novo = $objProduto;
	                $novo->pro_qrcode = $qrCode_novo;
	                $novo->pro_status = '0';
	                $novo->insert();
	                
	            }
	        }
	    }
	    
	    if( $rtn == 'OK' ){
	        echo '<div class="alert alert-success" role="alert"> Produtos cadastrados com sucesso:';
	        echo implode( ',' , array_filter( $arrProdutos_novos ) );
	        echo '</div>';
	    }else{
	        echo '<div class="alert alert-alert" role="alert"> '.$rtn.' </div>';
	    }
	}
?>

	<script src="js/consignado.js?2" charset="ISO-8859-1"></script>
	<script>
		var mask = {
		 money: function() {
			var el = this
			,exec = function(v) {
			v = v.replace(/\D/g,"");
			v = new String(Number(v));
			var len = v.length;
			if (1== len)
			v = v.replace(/(\d)/,"0.0$1");
			else if (2 == len)
			v = v.replace(/(\d)/,"0.$1");
			else if (len > 2) {
			v = v.replace(/(\d{2})$/,'.$1');
			}
			return v;
			};

			setTimeout(function(){
			el.value = exec(el.value);
			},1);
		 }

		}

		$(function(){
		 $('.money').bind('keypress',mask.money);
		 $('.money').bind('keyup',mask.money)
		});
	</script>
	<h1>
		Produtos Consignados

		<?php		
			/*if( isset( $_GET['id'] ) ){
			?>
			<a id='btnSubstituir' href="javascript:modalSubs()" class="btn btn-primary configBtn">Substituir</a>
			<a href="javascript:modalConsultaSubs()" class="btn btn-default configBtn">Consultar</a>
			<?php
			}*/
		?>
        
		<small>Novo registro</small>
	</h1>
	
	<form id="formProdutos">
		<div class="row-fluid">
			<div class="span6">
				<input type="hidden" name="id" id="txId" class="input-mini" readonly>
				
				<?php 
				/*if(!isset($_GET['populate'])){
				 echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#telaReplicar"><i class="icon-random icon-white"></i> Replicar</a>
				        <br><br>';   
				}*/
				?>
				<!---<label class="checkbox">
					<input type="checkbox" name="ckqtde" id="ckQtde" value="1"> Existe quantidade?
				</label>
				<br>--->
				
				<?php
					if(isset($_GET['populate'])){
						
				?>
					<div id="">
						<label>Quantide:</label>
						<input type="number" name="qtde" id="txQtde" oninput="validity.valid||(value=value.replace(/\D+/g, ''))" min="1" max="99999" style="text-transform: uppercase;" class="input-mini"/>
						<br><br>
					</div>
				<?php
					}else{
				?>
					<div id="boxQtde">
						<label>Quantide:</label>
						<input type="number" name="qtde" id="txQtde" oninput="validity.valid||(value=value.replace(/\D+/g, ''))" min="1" max="99999" style="text-transform: uppercase;" class="input-mini"/>
						<br><br>
					</div>
				<?php					
					}					
				?>
				<label>QRCode:</label>
				<input type="text" name="qrcode" id="txQrcode" maxlength="50" style="text-transform: uppercase;" class="input-medium" <?php echo (!isset( $_GET['id']) ? 'autofocus' : '')?> />
                
				<br>
				<label>Nome:</label>
				<input type="text" name="nome" id="txNome" maxlength="70" autocomplete="off" class="input-xlarge" data-provide="typeahead">
				<br>
				<label>Calibre:</label>
				<input type="text" name="calibre" id="txCalibre" maxlength="15" class="input-mini">
				<br>
				<label>Curvatura:</label>
				<input type="text" name="curvatura" id="txCurvatura" maxlength="15" class="input-mini">
				<br>
				<label>Diametro interno:</label>
				<input type="text" name="diametrointerno" id="txDiametrointerno" maxlength="15" class="input-mini">
				<br>
				<label>Comprimento:</label>
				<input type="text" name="comprimento" id="txComprimento" maxlength="15" class="input-mini">
				<br>
				<label>Fabricante:</label>
				<input type="text" name="fabricante" id="txFabricante" maxlength="30" class="input-xlarge">
				<br>
				<label>Data de fabricação:</label>
				<input type="text" name="datafabricacao" id="txDatafabricacao" autocomplete="off" class="input-medium data" value="<?php echo date("d/m/Y"); ?>">
				<br>
				<label>Validade:</label>
				<input type="text" name="validacaofabricacao" autocomplete="off" id="txValidacaofabricacao" class="input-medium data">
				<br>
				<label>Nº de série:</label>
				<input type="text" name="numserie" id="txNumSerie" maxlength="15" class="input-medium">
				<br>
				<label>Marca:</label>
				<input type="text" name="marca" id="txMarca" maxlength="30" class="input-large">
				<br>
				<label>ANVISA:</label>
				<input type="text" name="anvisa" id="txAnvisa" maxlength="20" class="input-small">
				<br>
			</div>
			<div class="span6">
				<label>Lote de fabricação:</label>
				<input type="text" name="lotefabricacao" id="txLotefabricacao" maxlength="15" class="input-small">
				<br>
				<label>Referências:</label>
				<input type="text" name="referencias" id="txReferencias" maxlength="20" class="input-medium">
				<br>
				<label>Quantidade máxima de reprocessamento:</label>
				<input type="text" name="qtdmaxima" id="txQtdmaxima" maxlength="3" class="input-mini" value="200">
				<br>
				<label>Exibir alerta a cada:</label>
				<div class="input-prepend input-append">
					<input type="text" name="alerta" id="txAlerta" maxlength="3" value="0" class="input-mini" style="width: 25px;">
					<span class="add-on">esterilizações</span>
				</div>
				<br>
				<label>Mensagem de alerta:</label>
				<input type="text" name="alertamsg" id="txAlertaMsg" value="" class="input-block-level">
				<br>
				<label>Grupo do material:</label>
				<?php 
				if (isset($_GET['populate'])){
					$produto = new ProdutosConsignadoModel();
					$produto = ProdutosConsignadoController::getIdGMaterial($_GET['id']);
					$_SESSION['lastidrotulo'] = $_GET['id'];
					$last_grupo = $produto->pro_idgrupomateriais;
				} else {
					$last_grupo = $_SESSION['lastidgrupo'];
				}
				echo ProdutosConsignadosHelper::populaComboGMaterial($last_grupo);
				?>
				<br>
				<label>Data do cadastro:</label>
				<input type="text" name="data" disabled id="txData" class="input-medium data" value="<?php echo date("d/m/Y"); ?>">
				<br>
				<label>Controle:</label>
				<input type="text" name="controle" id="txControle" class="input-medium">
				<br>				
				<label>Custo do produto:</label>
				<input type="text" name="custo" id="txCusto" placeholder="R$" class="input-medium" data-thousands="." data-decimal="," data-prefix="R$ ">
				<br><br>
				<label>Validade da esterilização:</label>
				<input type="text" name="validadeesterilizacao" id="txValidadeEsterilizacao" class="input-medium data">
				<br>	
				<label>N° Processo de entrada:</label>
				<input type="text" name="numeroentrada" id="txNumeroEntrada" class="input-medium">
				<br><br>		
				<label>Detalhes do produto</label>
				<textarea rows="7" name="detailproduct" maxlength="200" class="input-block-level"><?php echo $detail;?></textarea>				
				<?php if (isset($_GET['populate'])){ ?>
				<label>N° Processo de saída:</label>
				<input type="text" name="numerosaida" id="txNumeroSaida" readonly class="input-medium">
				<br><br>

				<!--<label class="checkbox">
					<input type="checkbox" name="composto" id="ckCompostockComposto" value="1"> Produto composto
				</label>-->
				<br>				
				<label class="checkbox">
					<input type="checkbox" name="ckdevolvido" id="ckDevolvido" value="1"> Devolvido
				</label>				
				<br><br>
				<div id="boxDevolvido">
				<label>Data de devolução:</label>
				<input type="text" name="devolvido" id="txDatadevolucao" class="input-medium data" value="<?php echo date("d/m/Y"); ?>">
				<br>
				</div>
				<br>
				<?php } ?>
				<!--<label class="checkbox">
					<input type="checkbox" name="composto" id="ckComposto" value="1"> Produto composto
				</label>
				<br>-->

				<!--<a href="#" class="btn btn-primary hide" data-toggle="modal" data-target="#telaRotulo" id="btRotulo"><i class="icon-camera icon-white"></i> Rótulo</a>-->
				<a href="#" class="btn btn-success" id="btSalvar"><i class="icon-ok icon-white"></i> Salvar</a>
				<a href="#" class="btn btn-danger" id="btCancelar"><i class="icon-remove icon-white"></i> Cancelar</a>
			</div>
		</div>
		
		<br>
		<blockquote style="font-size: 11px"> Li e estou de acordo * <br> Declaro para os devidos fins que é de minha inteira responsabilidade as informações prestadas acima, no Cadastro de Produtos/Materiais são verdadeiras e estão em consonância com a RE 2606 de 11 de Agosto de 2006, especialmente de acordo com disposições previstas no artigo 8º do dispositivo supra mencionado, sob pena de incorrer sansões previstas tanto na esfera cível e penal, nos seus artigos 299 e 307. </blockquote>
		
	</form>
	
	<!-- Tela Replicar -->
	<form id="formReplicar" method="post">
		<div id="telaReplicar" class="modal hide fade" style="width: 900px; margin-left: -450px;">
			<div class="modal-header">
				<h3>Replicar Produto</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="acao" value="salvarReplicar">				
				<div class="span5">
    				<label> QRCode: (produto base) </label>
    				<input type="text" name="txQrcodeReplicar_origem" id="txQrcodeReplicar_origem" maxlength="50" class="input-xlarge" onkeypress="return noenter()" placeholder="Informe o QRCode" autofocus autocomplete="off">
    				<p class="txProdutoBase_nome"> </p>
				</div>
				<div class="span5">
					<label> QRCode: (novo produto) </label>
					<div class="camposReplicar_novo">
						<input type="text" name="txQrcodeReplicar_novo[]" maxlength="50" class="input-xlarge" onkeypress="return noenter()" placeholder="Informe o QRCode" autofocus autocomplete="off">						
						<a href="" class="btnGeraQrCodeReplicar_novo label btn-default" title="Gerar código"> <i class="fa fa-sm fa-random"></i> </a>
						<a href="" class="btnAddQrCodeReplicar_novo label btn-success" title="Adicionar campo"> <i class="fa fa-sm fa-plus"></i> </a>
						<a href="" class="btnRemoverQrCodeReplicar_novo label btn-danger hide" title="Remover Campo"> <i class="fa fa-sm fa-trash"></i> </a>						
					</div>
					
				</div> 
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-success" id="btSalvarReplicar"><i class="fa fa-check icon-white"></i> Finalizar</a>
				<a href="#" class="btn" data-dismiss="modal"><i class="fa fa-times icon-white"></i> Fechar</a>
			</div>
		</div>
	</form>
	
	<!-- Tela Rótulo -->
	<form id="formRotulo">
		<div id="telaRotulo" class="modal hide fade" style="width: 900px; margin-left: -450px;">
			<div class="modal-header">
				<h3>Rótulo do Produto</h3>
			</div>
			<div class="modal-body" style="width: 100%; height: 900px;">
				<table>
					<tr>
						<td valign="top">
							<script type="text/javascript" src="view/jpegcam/htdocs/webcam.js"></script>
							<script language="JavaScript">
								webcam.set_api_url( 'view/jpegcam/htdocs/test.php' );
								webcam.set_quality( 90 );
								webcam.set_shutter_sound( true );
							</script>
							<script language="JavaScript">
								document.write( webcam.get_html(350, 300) );
							</script>
							<br>
							<form>
								<h5>Câmera</h5>
								<a href="#" class="btn btn-warning" onClick="take_snapshot()"><i class="icon-ok icon-white"></i> Capturar!</a>
								&nbsp;&nbsp;
								<a href="#" class="btn" onClick="webcam.configure()"><i class="icon-wrench"></i></a>
							</form>
							<script language="JavaScript">
								webcam.set_hook( 'onComplete', 'my_completion_handler' );
								
								function take_snapshot() {
									// take snapshot and upload to server
									document.getElementById('upload_results').innerHTML = '<h3>Salvando...</h3>';
									webcam.snap();
								}
								
								function my_completion_handler(msg) {
									// extract URL out of PHP output
									if (msg.match(/(http\:\/\/\S+)/)) {
										var image_url = RegExp.$1;
										// show JPEG image in page
										document.getElementById('upload_results').innerHTML = '<img src="' + image_url + '">';
										// reset camera for another shot
										webcam.reset();
									}
									else alert("Erro de PHP: " + msg);
								}
							</script>
						</td>
						<td width=50>
							&nbsp;
						</td>
						<td valign=top>
							<div id="upload_results">
								<?php
								// verifica se possui imagem de rótulo salva
								$arquivo = "img/rotulos/" . $_GET['id'] . ".jpg";
								$rotulo = fopen($arquivo);
								if(is_file($arquivo))
									echo "	<img src='" . $arquivo . "'>
											<br>
											<h5>Imagem salva</h5>
											<a href='#' class='btn btn-danger' onClick='removeRotulo(" . $_GET['id'] . ")'><i class='icon-remove icon-white'></i> Remover</a>";
								else
									echo "	<h4>Sem rótulo</h4>";
								?>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger hide" id="btRemoverRotulo"><i class="icon-remove icon-white"></i> Remover</a>
				<a href="#" class="btn" id="btFecharRotulo" data-dismiss="modal">Fechar</a>
			</div>
		</div>
	</form>
	

	<div class="modal hide fade" id="mdl-substituir">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Substituir QRCode</h4>
				</div>
				<div class="modal-body">
					<form id='frm-substituir' role="form">
						<div class="form-group">

							<input type="hidden" name="idproduto" class="form-control">
							<input type="hidden" name="inserir" class="form-control">
							<input type="hidden" name="qrcode_atual" class="form-control">

							<div class="alert alert-danger">
								<h3>Motivo da Substitui&ccedil;&atilde;o!</h3>
								<p>Por motivos de seguran&ccedil;a e hist&oacute;rico ser&aacute; gerado uma ocorr&ecirc;ncia com o motivo da substitui&ccedil;&atilde;o do QRCode.</p>
							</div>
							<div class="form-group">
								<label for="txMotivoSubs">Motivo:</label>
								<textarea name="motivo_substituicao" required="required" class="form-control configTxtMotivo" rows="3" id="txMotivoSubs"></textarea>
							</div>										
							<div class="form-group">
								<label for="">Novo QRCode</label>
								<input type="text" class="form-control" id="newQRCode" name="newQRCode" required="required" placeholder="informe o QRCode" autofocus/>				
							</div>					
							<div class="checkbox">
								<label>
									<input type="checkbox" id='chkAumentar' name="aumentarProcessamento" value="1"/>
									Aumentar a quantidade m&aacute;xima de uso deste produto
								</label>
							</div>

						</div>
						<button type="submit" class="btn btn-primary hide">Submit</button>
						<button type="reset" class="btn btn-warning hide">Submit</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="$('#mdl-substituir').find('button[type=reset]').click();" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
					<button type="button" onclick="$('#mdl-substituir').find('button[type=submit]').click();" class="btn btn-primary pull-right">Salvar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal hide fade " id="mdl-consultar">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Consulta de QRCode</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<table class="table table-hover configConsulta">
								<thead>
									<tr>
										<th>QRCode</th>
										<th>Substituido Dia</th>
										<th>Quem Substituiu</th>
									</tr>
								</thead>
								<tbody>								
								<?php
									$lista = QrcodesController::select( " qrc_idproduto = {$_GET['id']}" );
									if( !empty( $lista ) ){
										foreach ($lista as $qrc) {
										?>
											<tr>
												<td><?php echo $qrc->qrc_antigo_qrcode ?></td>
												<td><?php echo $qrc->qrc_data_convertida ?></td>
												<td><?php echo $qrc->usu_nome ?></td>
											</tr>										
										<?php
										}
									?>
									<?php
									}
									else{
									?>
										<tr>
											<td colspan="3" class="text-center">Nenhum QRCode foi encontrado.</td>
										</tr>									
									<?php
									}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>

<?php
	echo ProdutosHelper::populaNomesProdutos();
			
	if(isset($_GET['populate'])){
		
		$produto = ProdutosConsignadoController::getProdutoConsignado($_GET['id']);

		echo ProdutosConsignadosHelper::populaCampos($produto);

	}

	if( isset( $_GET['substituir'] ) ){
		echo '	<script type="text/javascript">
					$("#btnSubstituir").click();
				</script> ';

	}
	
	include "helper/rodape.php";
?>