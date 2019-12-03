
$(document).ready(function(){
	$('.correct-pn').html('<i class="fas fa-check"></i>')
			
	$("input[name='optradio']").live("click", function(){
		var optradio = $("input[name='optradio']:checked").val()
		if(optradio == 'pn'){
			$('.correct-pn').html('<i class="fas fa-check"></i>')
			$('.correct-pc').html('')

		}else if(optradio == 'pc'){
			$('.correct-pc').html('<i class="fas fa-check"></i>')
			$('.correct-pn').html('')			
		}
	});

	document.getElementById('txQrcode').focus();

	$('#txQrcode').click(function(){
		//$('#txQrcode').focus();
		setTimeout(function() {
			$('#txQrcode').focus();
		}, 1000);

		
	});

	//Todos os botï?½es da tela expedicao passam por aqui
	$(document).on('click', '.btn-success', function(e) {
		var qte = $(this).closest('tr').find(".qte").text();
		document.getElementById('qtdeItem').value = qte;
		document.getElementById('qtdeItem').max = qte;
		document.getElementById('txQrcode').value = $(this).val();
		document.getElementById('txQrcode').focus();
		buscarProd();
	});


	// cleverson matias
	// carrega dinamicamente os totais de itens comuns e consignados
	contaComunEtiquetagem();
	contaConsigEtiquetagem();
	
	// scroll comum
	$(document).on('click', '#cs-comuns', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#comuns')).offset().top
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
	
	// cleverson controle tipo de produto a ser buscado -> comuns
	//$("#txQrcode").attr("placeholder", "Buscar produto comun...").placeholder();
	
	$('#cs-co').on('change', function(){ // on change of state
		checkedContext('pn');
	   $("#txQrcode").attr("placeholder", "Buscar produto comun...").placeholder();
	})
	//cleverson controle tipo de produto a ser buscado -> consignados
	$('#cs-consig').on('change', function(){ // on change of state
		checkedContext('pc');
	   $("#txQrcode").attr("placeholder", "Buscar produto consignado...").placeholder();
	})
	
	

	$("#tlaEtiquetarProduto").on('shown', function(){
		$("#txQrcode").hide();
		$("#txQrcode").val("");
		$("#lbProdutoinvertido").hide();
		$("#btImprimir").focus();
	});

	$("#tlaEtiquetarProduto").on('hide', function(){
		$("#txQrcode").show();
		$("#txQrcode").focus();
	});

	$("#btVoltar").click(function(){
		location.href = "etiquetagem";
	});

	// Reimpress?£o de etiqueta
	$("#btReimpressao").click(function(){
		$("#tlaReimpressaoEtiqueta").modal();
	});

	// Gerar etiqueta ACS 05/09/2017
	$("#btGeraEtiqueta").click(function(){

		clearTimeout(time_exec);
		time_exec = setTimeout(function(){
			// Monta Cï?½digo de Barras e Envia para tela de impressï?½o
			$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeEtiqueta").val(), function(){
				var parametros = "qrCode=" + $("#txQrcodeEtiqueta").val();
				window.open("etiquetaAvulsa?" + parametros);
				$("#txQrcodeEtiqueta").val('');
				$("#txQrcodeEtiqueta").focus();
			});
		}, 150);
	});

	// Ao digitar o QrCode Para impressï?½o ACS 05/09/2017
	var time_exec;
	$("#btGeraEtiqueta").keyup(function(e){
		//alert(1);
		clearTimeout(time_exec);
		time_exec = setTimeout(function(){

			var tecla = (e.keyCode?e.keyCode:e.which);
			if( $("#btGeraEtiqueta").val().length > 0 /* && tecla == 13 */ ){
				$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeEtiqueta").val(), function(){
					var parametros = "qrCode=" + $("#txQrcodeEtiqueta").val();
					window.open("etiquetaAvulsa?" + parametros);
					$("#txQrcodeEtiqueta").val('');
					$("#txQrcodeEtiqueta").focus();
				});
			}

		}, 150);
	});
    
	// Ao abrir modal - Foca no campo do QrCode
	$("#tlaReimpressaoEtiqueta").on('shown', function(){
		$("#txQrcodeReimpressao").focus();
	});

	// Ao digitar o QrCode Para impress?£o
	var time_exec;
	$("#txQrcodeReimpressao").keyup(function(e){
		$('#itensSolicitacaoReimpressao').html(''); // Limpa lista

		clearTimeout(time_exec);
		time_exec = setTimeout(function(){

			var tecla = (e.keyCode?e.keyCode:e.which);
			if( $("#txQrcodeReimpressao").val().length > 0 /* && tecla == 13 */ ){
				$.post("etiquetagem_new", {acao:'reimpressaoEtiqueta', qrcode:$.trim( $("#txQrcodeReimpressao").val() )}, function(data){
					
					$('#solicitacoesReimpressao').removeClass('hide').show();
					$('#itensSolicitacaoReimpressao').html(data);
					$('input[name="dataLimite[]"]').mask("99/99/9999"); // Mascara de Data
				});
			}

		}, 150);
	});

	// Bot?£o para reimpress?£o do item
	$(".btReimprimir").live('click', function(){
		var id = $(this).attr('id');

		// Verifica se exixte input dataLimite no item clicado
		if( $(this).parent().parent().find('input[name="dataLimite[]"]').length ){

			// Verifica se o input est?? vazio ou ?? inv??lido
			var dataLimite = $(this).parent().parent().find('input[name="dataLimite[]"]').val();
			if( dataLimite == '' || dataLimite.length < 10 ){ // dd/mm/aaaa
				$(this).parent().parent().find('input[name="dataLimite[]"]').focus();
				return false;
			} else{
				$.post("etiquetagem_new", {acao:'alteraValidade', id:id, dataLimite:dataLimite}, function(data){
					$(this).parent().parent().find('input[name="dataLimite[]"]').attr('disabled','disabled'); // Desativa o Campo Data
				}); // Altera a data no Banco
			}
		}

		// Monta C?³digo de Barras e Envia para tela de impress?£o
		$.get("view/Barcode2/gerabarras.php?code=" + $("#txQrcodeReimpressao").val(), function(){
			var parametros = "acao=reimpressao&item=" + id + "&qrCode=" + $("#txQrcodeReimpressao").val();
			window.open("etiqueta?" + parametros);
		});
	});
	
	
	$("#boxQtde").hide();
	$("#labelQtd").hide();
	
	var intervalo;
	
	$(".txQrcodecl").on('keyup', function(e){

		var qrcode = $("#txQrcode").val();
		qrcode = qrcode.split('.');
		if(qrcode.length == 2){
			$.post("etiquetagem_new",{acao:'getqte', qrcode:$("#txQrcode").val()}, function(data){
				data = JSON.parse(data);
				document.getElementById('qtdeItem').max = data;
				document.getElementById('qtdeItem').value = data;
				buscarProd()
			});
		}
		if(qrcode.length == 3){
			qrcodeNormal = qrcode[0]+'.'+qrcode[1];
			lote = qrcode[2];
			$.post("etiquetagem_new",{acao:'getdados', qrcode:qrcodeNormal, lote:lote}, function(data){
				data = JSON.parse(data);
				console.log(data);
				$('#qrcodeRe').text(data['pro_qrcode']);
				$('#nomeProdutoRe').text(data['pro_nome']);
				$('#spanMetodoRe').text(data['met_nome']);
				$('#dataEsterilizacaoRe').text(data['iso_dataesterilizacao']);
				$('#spanLimiteUsoRe').text(data['iso_datalimite']);
				$('#qr').text(data['qr']);
				$('#itemRe').text(data['item']);
				$('#tlaReetiquetagem').modal('show');
			});
		}else{
			buscarProd()
		}	
	});	
	

	function buscarProd(){
		if($("#txQrcode").val().length > 0){
			clearTimeout(intervalo);
				intervalo = window.setTimeout(function() {
					$("#imgLoading").show();
					//cleverson matias add extra data to send with post (kind of product) 
					$.post("etiquetagem_new", {acao:'buscar', qrcode:$("#txQrcode").val(), product:$("input[name='optradio']:checked").val()/*, ses:$("#txId").val()*/}, function(data){
						data = $.trim(data);
						
						var context = $("input[name='optradio']:checked"). val();
						if (data != "NULO"){
							if (data != "ERRO"){
								adata = data.split("*;*");
		
								//alert(adata[9]);
								//console.log(adata);
								//console.log(adata[16]);
								if( adata[0] != "" ){
									$("#tlaEtiquetarProduto").modal();  
								}else{
									$('#lbProdutoinvertido').show();
									$('#lbProdutoNaoCadastrado').show();
									
									var chProduto = $("input[name='optradio']:checked").val()			
									
									if(chProduto == 'pc'){
										$('.type-product').text('consignado');
									}else{
										$('.type-product').text('comun');
									}
	
								}
								$("#idItem").val(adata[0]);
								$("#idProduto").val(adata[1]);
								$("#qrcode").text(adata[2]);
								$("#nomeProduto").text(adata[3]);
								$("#dadosProduto1").text(adata[4]);
								$("#dadosProduto2").text(adata[5]);
								$("#setor").text(adata[6]);
	
								/*console.log(adata[8]);
								$("#slMEsterilizacao").val(adata[8]);*/
	
								if(context == 'pn'){
	
									$.post("solicitacoes_new",{acao:'verautorizacao', id: $("#idProduto").val()}, function(data){
										
										if($.trim(data) == 'nao'){
											$('#btImprimir').hide();
											$('#btOcorrencia').hide();
											$('#aviso').html('Este material nï?½o pode etiquetada pois estï?½ faltando um item e sua <br> permissï?½o foi negada.');
										}else if($.trim(data) == 'sim'){
											$('#btImprimir').show();
											$('#btOcorrencia').show();
										}else{
											//conficional if else para verificar se os outros sï?½o filhos do mesmo id pai da caixa, se for bloqueia tbm 
											
											$.post("solicitacoes_new",{acao:'verificafilhos', id: $("#idProduto").val()}, function(data){
												//console.log(data)
												if($.trim(data) == 'nao'){
													$('#btImprimir').hide();
													$('#btOcorrencia').hide();
													$('#aviso').html('Este item pertence a um material de autorizaï?½ï?½o negada, <br> impossï?½vel prosseguir.');												
												}else{
													$('#btImprimir').show();
													$('#btOcorrencia').show();											
												}
											});								
										}
									});								
								}
								
	
								if(context == 'pn' && adata[16] > 0){
	
									$("#labelQtd").hide();
									$('#reuso').hide();
							
								}else{
									$("#labelQtd").hide();
									$('#reuso').show();
									$("#qtdProcessada").text(adata[9]);
									
								}
								
								if(context == 'pc' && adata[16] > 0){
									$("#reuso").show();
									$("#qtdPro").text(adata[16]);
									$("#labelQtd").show();
								}
								
								$("#qtdMaxima").text(adata[10]);
								//$("#txLote").val(adata[11]);
								$("#dataEsterilizacao").text(adata[12]);
								$("#composto").val(adata[15]);
								//etiquetagem
	
								if (adata[14] == '1') { // se item j??Â?Â½ tiver sido finalizado
									$("#imgOkModal").show();
									$(".modal-header").attr("style", "background: #adfe9d;");
									$(".modal-footer").attr("style", "background: #adfe9d;");
									// $("#txLote").attr("readonly", true);
									$("#slLimiteUso").hide();
									$("#labelMetodo").hide();
									$("#dataLimitedataLimite").show();
									$("#dataLimite").text(adata[13]);
									$("#boxQtde").hide();
									$("#qtdename").hide();
									$("#qtdename2").hide();
									$("#qtdeItem2").hide();
									$("#qtdeItem").hide();
									$("#metodo").html(adata[18]);							
								} else {
									$("#imgOkModal").hide();
									$(".modal-header").attr("style", "background: white;");
									$(".modal-footer").attr("style", "background: white;");
									// $("#txLote").attr("readonly", false);
									$("#slLimiteUso").show();
									$("#spanLimiteUso").html(adata[13]);
									$("#metodo").html(adata[18]);
									$("#equipamento_metodo").html(adata[19]);
									
								}
	
								if(adata[16] > 0){
									$("#boxQtde").show();		
								}else{
									$("#boxQtde").hide();
								}
	
								if(adata[17] > 0){
									$("#qtdeItem").change(function(e){
										if($("#qtdeItem").val() == ''){
											alert("Insira uma quantidade.");
											$("#btImprimir").hide();
										}
									});									
	
									/*$("#qtdeItem").keyup(function(e){
										
										if($("#qtdeItem").val() > parseInt(adata[17])){
											alert('A quantide inserida excede a quantidade atual em etiquetagem');
											$("#btImprimir").hide();
										}else{
											$("#btImprimir").show();
										}
									});*/
									$("#qtdeItem2").val(adata[17]);
									//este campo valida a qeustï?½o de que o produto tem quantidade e precisa ser inserido quantidade para validar 
									$("#qtdeItem3").val(adata[16]);
								}
								
								if($("input[name='optradio']:checked").val() == 'pc'){
									
									$("#boxQtde").hide();
								}
	
							}else {
							}
						}
						$("#imgLoading").hide();
					});
				}, 300);			
			};
	}

	//$("#txQrcode").keypress(function(e){
	$("#txQrcode").on('change, blur', function(e){	

		/*
		 * Verifica se o evento ??Â?Â½ Keycode (IE e outros)
		 * Se n??Â?Â½o for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		clearTimeout(intervalo);
		intervalo = window.setTimeout(function() {

		var qrcodenew = $("#txQrcode").val();
		qrcodeQte = qrcodenew.split('.');
			if($("#txQrcode").val().length > 0 && qrcodeQte.length != 3){
				$("#imgLoading").show();
				//cleverson matias add extra data to send with post (kind of product) 
				$.post("etiquetagem_new", {acao:'buscar', qrcode:$("#txQrcode").val(), product:$("input[name='optradio']:checked").val()/*, ses:$("#txId").val()*/}, function(data){
					data = $.trim(data);
					
					var context = $("input[name='optradio']:checked"). val();
					if (data != "NULO"){
						if (data != "ERRO"){
							adata = data.split("*;*");

							//alert(adata[9]);
							//console.log(adata);
							//console.log(adata[16]);
							if( adata[0] != "" ){
								$("#tlaEtiquetarProduto").modal();
							}else{
								$('#lbProdutoinvertido').show();
								$('#lbProdutoNaoCadastrado').show();
								
								var chProduto = $("input[name='optradio']:checked").val()			
								
								if(chProduto == 'pc'){
									$('.type-product').text('consignado');
								}else{
									$('.type-product').text('comum');
								}

							}
							$("#idItem").val(adata[0]);
							$("#idProduto").val(adata[1]);
							$("#qrcode").text(adata[2]);
							$("#nomeProduto").text(adata[3]);
							$("#dadosProduto1").text(adata[4]);
							$("#dadosProduto2").text(adata[5]);
							$("#setor").text(adata[6]);

							/*console.log(adata[8]);
							$("#slMEsterilizacao").val(adata[8]);*/

							if(context == 'pn'){

								$.post("solicitacoes_new",{acao:'verautorizacao', id: $("#idProduto").val()}, function(data){
									
									if($.trim(data) == 'nao'){
										$('#btImprimir').hide();
										$('#btOcorrencia').hide();
										$('#aviso').html('Este material nï?½o pode etiquetada pois estï?½ faltando um item e sua <br> permissï?½o foi negada.');
									}else if($.trim(data) == 'sim'){
										$('#btImprimir').show();
										$('#btOcorrencia').show();
									}else{
										//conficional if else para verificar se os outros sï?½o filhos do mesmo id pai da caixa, se for bloqueia tbm 
										
										$.post("solicitacoes_new",{acao:'verificafilhos', id: $("#idProduto").val()}, function(data){
											console.log(data)
											if($.trim(data) == 'nao'){
												$('#btImprimir').hide();
												$('#btOcorrencia').hide();
												$('#aviso').html('Este item pertence a um material de autorizaï?½ï?½o negada, <br> impossï?½vel prosseguir.');												
											}else{
												$('#btImprimir').show();
												$('#btOcorrencia').show();											
											}
										});								
									}
								});								
							}
							

							if(context == 'pn' && adata[16] > 0){

								$("#labelQtd").hide();
								$('#reuso').hide();
						
							}else{
								$("#labelQtd").hide();
								$('#reuso').show();
								$("#qtdProcessada").text(adata[9]);
								
							}
							
							if(context == 'pc' && adata[16] > 0){
								$("#reuso").show();
								$("#qtdPro").text(adata[16]);
								$("#labelQtd").show();
							}
							
							$("#qtdMaxima").text(adata[10]);
							//$("#txLote").val(adata[11]);
							$("#dataEsterilizacao").text(adata[12]);
							$("#composto").val(adata[15]);
							//etiquetagem

							if (adata[14] == '1') { // se item j??Â?Â½ tiver sido finalizado
								$("#imgOkModal").show();
								$(".modal-header").attr("style", "background: #adfe9d;");
								$(".modal-footer").attr("style", "background: #adfe9d;");
								// $("#txLote").attr("readonly", true);
								$("#slLimiteUso").hide();
								$("#labelMetodo").hide();
								$("#dataLimitedataLimite").show();
								$("#dataLimite").text(adata[13]);
								$("#boxQtde").hide();
								$("#qtdename").hide();
								$("#qtdename2").hide();
								$("#qtdeItem2").hide();
								$("#qtdeItem").hide();								
							} else {
								$("#imgOkModal").hide();
								$(".modal-header").attr("style", "background: white;");
								$(".modal-footer").attr("style", "background: white;");
								// $("#txLote").attr("readonly", false);
								$("#slLimiteUso").show();
								$("#spanLimiteUso").html(adata[13]);
								$("#metodo").html(adata[18]);
								$("#equipamento_metodo").html(adata[19]);
								
							}

							if(adata[16] > 0){
								$("#boxQtde").show();		
							}else{
								$("#boxQtde").hide();
							}

							if(adata[17] > 0){
								$("#qtdeItem").change(function(e){
									if($("#qtdeItem").val() == ''){
										alert("Insira uma quantidade.");
										$("#btImprimir").hide();
									}
								});									

								/*$("#qtdeItem").keyup(function(e){
									
									if($("#qtdeItem").val() > parseInt(adata[17])){
										alert('A quantide inserida excede a quantidade atual em etiquetagem');
										$("#btImprimir").hide();
									}else{
										$("#btImprimir").show();
									}
								});*/
								$("#qtdeItem2").val(adata[17]);
								//este campo valida a qeustï?½o de que o produto tem quantidade e precisa ser inserido quantidade para validar 
								$("#qtdeItem3").val(adata[16]);
							}
							
							if($("input[name='optradio']:checked").val() == 'pc'){
								
								$("#boxQtde").hide();
							}

						}else {
						}
					}
					$("#imgLoading").hide();
				});
			}
		}, 300);

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});

	$("#slLimiteUso").live('change', function(){
		calculaLimite();
	});

	var qtdgeral = null;	

	function enviaImpressao(ctx, qtdeGeral, data, datacomposto, qrnew){
		var qtdProcessada = '';
		var qtdeConsignado = '';
		if (ctx == 'pn' && qtdeGeral > 0){
			 qtdProcessada = '';
		}else{
			qtdProcessada = $("#qtdProcessada").text();
		}
		
		if(ctx == 'pc' && qtdeGeral > 0){
			qtdProcessada = $("#qtdProcessada").text();;	
			qtdeConsignado = qtdeGeral;		
			
		}

		var qrn = '';

		if (qrnew != '') {
			qrn = qrnew;
		}else{
			qrn = $("#qrcode").text();
		}

		console.log(qrn)

		if(document.getElementById('qtdeItem').value > 1){
			var parametros =  "qrCode=" + qrn
			+ "&nomeProduto=" + $("#nomeProduto").text()
			+ "&qteprod=" + document.getElementById('qtdeItem').value
			+ "&dadosProduto1=" + $("#dadosProduto1").text()
			+ "&dadosProduto2=" + $("#dadosProduto2").text()
			+ "&nomeSetor=" + $("#setor").text()
			+ "&metodo=" + $("#metodo").text()
			+ "&qtdeProc=" + qtdProcessada
			+ "&qtdeConsignado=" + qtdeConsignado
			+ "&lote=" + $("#txLote").val()
			+ "&item=" + $("#idItem").val()
			+ "&composicao=" + datacomposto;
		} else{
			var parametros =  "qrCode=" + qrn
			+ "&nomeProduto=" + $("#nomeProduto").text()
			+ "&dadosProduto1=" + $("#dadosProduto1").text()
			+ "&dadosProduto2=" + $("#dadosProduto2").text()
			+ "&nomeSetor=" + $("#setor").text()
			+ "&metodo=" + $("#metodo").text()
			+ "&qtdeProc=" + qtdProcessada
			+ "&qtdeConsignado=" + qtdeConsignado
			+ "&lote=" + $("#txLote").val()
			+ "&item=" + $("#idItem").val()
			+ "&composicao=" + datacomposto;
		}

		
		var optradio = $("input[name='optradio']:checked").val()
		
		var oJan = window.open("etiqueta?" + parametros);

		setTimeout(function(){ 
			$("#listaItens").load(location.href+" #listaItens>*","");
			contaComunEtiquetagem()
			contaConsigEtiquetagem()
			$('#tlaEtiquetarProduto').modal("hide");
			location = location;
			
			
		}, 300);			
	}	
	//slEEsterilizacao
	
	function save(){

	
		var ctx = $("input[name='optradio']:checked"). val();
		

			qteqr = $('#qrcode').html().split('.');
			if(qteqr.length >= 2){
				qteitem = $("#qtdeItem").val();
			}
			else{
				qteitem = 0;
			}
			
			$.post("etiquetagem_new",{ctx:ctx, iditem:$("#idItem").val(), dataEsterilizacao:$("#dataEsterilizacao").text(), dataLimite:$("#dataLimite").text(),
			 lote:$("#txLote").val(), idSolicitacao:$("#txNumero").text(), limiteUso:$("#slLimiteUso").val(), composto:$("#composto").val(), qtde:qteitem, idproduto:$("#idProduto").val(), metEsterilizacao:$("#slMEsterilizacao").val(), eqEsterilizacaoet:$("#slEEsterilizacaoet").val()},
				 function(data){
					 
					qteqr = $('#qrcode').html().split('.');
					if(qteqr.length >= 2){

						var teste = data.split('*');
		
						
						if (teste[2] == 0){
							var qrcodenew = $("#qrcode").text() 	
						}
						else{
							var qrcodenew = $("#qrcode").text() + '.' + teste[2]
						}
					}
					else{
						var qrcodenew = $("#qrcode").text() 	
					}
					
					$.get("view/Barcode2/gerabarras.php?code=" + qrcodenew, function(){
						var ctx = $("input[name='optradio']:checked"). val();
					
					});		

					var datacomposto = $.trim(data).split('**')[1];


					var qtdeGeral = null;

					if(ctx == 'pn'){
						 $.post("etiquetagem_new", {idpn:$("#idProduto").val(), acao:'getquantidadepn'}, function(data){
							  qtdeGeral = data; 
							  enviaImpressao(ctx, qtdeGeral, data, datacomposto ,qrcodenew);
						 });
					}else{
						 $.post("etiquetagem_new", {idpc:$("#idProduto").val(), acao:'getquantidadepc'}, function(data){
							 //alert(data);
							  qtdeGeral = data; 					
							  enviaImpressao(ctx, qtdeGeral, data, datacomposto, qrcodenew );
						 });
					 }			 
				});
		//});			
	}
	
	$("#btImprimir").click(function(){
		
		if ( parseInt(document.getElementById('qtdeItem').value) >  parseInt(document.getElementById('qtdeItem').max) ){
			alert('A quantidade maxima desse produto é: '+document.getElementById('qtdeItem').max );
		}
		else if($('#slMEsterilizacao').val() == '0'){
			alert('selecione o método de esterilização!');
			$('#slMEsterilizacao').focus();
		}
		else if($('#slEEsterilizacaoet').val() == '0'){
			alert('selecione o equipamento!');
			$('#slEEsterilizacaoet').focus();
		}
		else if($('#slLimiteUso').val() == '0'){
			alert('selecione a data limite!');
			$('#slLimiteUso').focus();
		}
		else{
			var ultimo_lote = $('#txLote').val();
			$.post('etiquetagem_new', {acao:'setLote', ultimo_lote:ultimo_lote},function(data){});

			if($("#slLimiteUso").val() == 0){
				if( $('#txLote').val() == "" )
					alert('Escolha a data limite de uso!');
			} else if ($("#txLote").val() == ""){
				alert("Informe o lote do item!");
				$("#txLote").focus();
			} else {
				var ctx = $("input[name='optradio']:checked").val();
				if(parseInt($("#qtdeItem3").val()) > 0 && ctx == 'pn'){
					if($("#slMEsterilizacao").val() == 0){
						alert('Por favor escolha um método');
					}else if(!parseInt($("#qtdeItem").val()) > 0){
						alert('Por favor insira uma quantidade');
					}else{
						// cleverson matias
						$.post('etiquetagem_new', {acao:'setProntos', value:$("#qtdeItem").val(), idproduto: $("#idProduto").val()},function(data){
						});
						save();
					}
				}else{
					save();					
				}
				/*
	*/
			}
		}
	});
	
	$("#btImprimirRE").click(function(){
		var parametros = "acao=reimpressao&item=" + $('#itemRe').html() + "&qrCode=" + $("#qr").html()+"&remp=true";
		window.open("etiqueta?" + parametros);
		
	});	

	$("#lbProdutoDescartado").hide();
	$("#btOcorrencia").click(function(){ // fun??Â?Â½??Â?Â½o adaptada em solicitacoes.js como lancarOcorrencia()
		$.post("ocorrenciasprodutos", {acao:"ocorrenciasProduto", produto:$("#idProduto").val()}, function(data){
			$.post("ocorrenciasprodutos", {acao:'buscar', qrcode:$("#qrcode").text()}, function(data){
				if ($.trim(data) != "ERRO" && $.trim(data) != ""){
					var aux = $.trim(data).split("*;*");
					$("#idProduto").val(aux[0]);
					console.log(aux[0])
					if (aux[2] == "*") {
						$("#lbProdutoDescartado").show();
						$("#txQrcode").select();
					} else {
						$.post("ocorrenciasprodutos", {acao:'ocorrenciasProduto', produto:aux[0]}, function(data){

							//$("#produtoAlvo").text(aux[1]);
							console.log(data)
							$("#produtoPai").text('Caixa: ' + aux[4]);
							$("#ProdutoPai").val(aux[4]);
							$("#ProdutoPaiId").val(aux[5]);
							$("#produtoAlvo").text($("#nomeProduto").text());
							$("#divOcorrenciasProduto").html(data);
							$("#slOcorrencia").val("0");
							$("#descricaoOcorrencia").text("");
							$("#txObs").val("");
							$("#lbObs").hide();
							$("#btConfirmarOcorrencia").hide();
							$("#tlaLancarOcorrencia").modal();
						});
					}
				} else {
					$("#lbProdutoNaoCadastrado").show();
				}
			});

		});
	});

	$("#slOcorrencia").change(function(){
		$.post("ocorrenciasprodutos",{ocorrencia:$("#slOcorrencia").val()}, function(data){
			$("#descricaoOcorrencia").text(data);
			if($("#slOcorrencia").val() == 0){
				$("#txObs").val("");
				$("#lbObs").hide();
				$("#lbProduct").hide();
				$("#btConfirmarOcorrencia").hide();
			} else {
				$("#lbObs").show();
				$("#lbProduct").show();
				$("#btConfirmarOcorrencia").show();
			}
		});
	});

	$("#btConfirmarOcorrencia").click(function(){
		$.post("ocorrenciasprodutos",{idocorrencia:$("#slOcorrencia").val(), idproduto:$("#idProduto").val(), obs:$("#txObs").val(), produtopai: $("#ProdutoPai").val(), produtopaiid: $("#ProdutoPaiId").val()}, function(data){
			$("#tlaLancarOcorrencia").modal('hide');
			if($.trim(data) == "R") { // anula 1 reuso
				$("#qtdProcessada").text(parseInt($("#qtdProcessada").text()) - 1);
			}
		});
	});

	// Etiquetagem em Massa
	$("#btEtiquetagemMassa").click(function(){
		event.preventDefault();

		//if( confirm('Confirmar etiquetagem em massa ?' ) ){

			$.post("etiquetagem_new", {acao:'etiquetagemMassa'}, function(data){
				if( $.trim( data ) == 'OK' ){
					location.reload();
				}else{
					alert('Não foi possível concluir a solicitatação');
				}
			});

		//}

	});

});

function etiquetabtn(qrcode, is_pc = null){

	

	if($('#pn:checked').length == 1){
		$('#pc').removeAttr('checked');
		$('#pn').attr("checked", "checked");
	}

	if (is_pc == "pc") {
		$('#pn').removeAttr('checked');
		$('#pc').attr("checked", "checked");
	}

	$("#txQrcode").focus();

	setTimeout(function(){
		$("#txQrcode").val(qrcode);
	}, 1);

	
	
	
}

function calculaLimite(){
	var opt_text = $("#slLimiteUso option:selected").text().split(' ');
	var periodo = null;
	var opt_text = opt_text.map(function(index, elem) {
		return $.trim(index)
	})

	if(opt_text[0] == '**'){
		$("#dataLimiteQtd").hide();
		$("#dataLimite").hide();
		return
	}

	switch (opt_text[2]){
		case 'DIAS':
			periodo = 'd';
			break;
		case 'MESES':
			periodo = 'M';
			break;
		case 'ANOS':
			periodo = 'y';
			break;
	}

	var futureMonth = moment().add(opt_text[1], periodo).format('DD/MM/YYYY');

	$("#dataLimite").show();
	$("#dataLimiteQtd").show();
	$("#dataLimite").text(futureMonth);
	$("#dataLimiteQtd").text(futureMonth);


	// if( $("#slLimiteUso").val() == '0' ){
	// 	$("#dataLimite").hide();
		//$("#dataLimiteQtd").hide();
	// }else{
	// 	$.post("etiquetagem_new",{limiteUso:$("#slLimiteUso").find("option").filter(":selected").text()}, function(data){
	// 		$("#dataLimite").show();
	// 		$("#dataLimiteQtd").show();
	// 		$("#dataLimite").text($.trim(data));
	// 		$("#dataLimiteQtd").text($.trim(data));
	// 	});
	// }

}

function buscaSolicitacao() {
	$.post("etiquetagem_new",{acao:'buscar', numero:$("#txNpedido").val()}, function(data){
		if ($.trim(data) != "ERRO"){
			data = $.trim(data).split(";");
			$("#txId").val(data[0]);
			$("#txNumero").text(data[1]);
			$("#txSetor").text(data[2]);
			$("#txDataEntrada").text(data[3]);
			$("#txDataEsterilizacao").text(data[4]);
			$("#formPedido").hide();
			$("#btVoltar").show();
			$("#divPedidoEtiquetagem").show();
			$.post("etiquetagem_new",{acao:'listar', id:data[0]}, function(data){
				$("#listaItens").html(data);
			});
		} else {
			alert("Solicitação não encontrada! Verifique o número da solicitação.");
			$("#txNpedido").val("");
			$("#txNpedido").focus();
		}
	});
}

function noenter() {
	return !(window.event && window.event.keyCode == 13);
}



function contaComunEtiquetagem(){
	$.post("etiquetagem_new", {acao:"contaComum"}, function(data) {
		$("#cs-qtd-comuns").text("Comuns: " + data);
		$("#TotComuns").val(data);
	});
}

function contaConsigEtiquetagem(){
	$.post("etiquetagem_new", {acao:"contaConsig"}, function(data) {
		
		var total = parseInt($("#TotComuns").val()) + parseInt(data);

		$("#cs-qtd-consignados").text("Consignados: " + data);
		$("#cs-totalItens").text("Total de Itens: " + total);

		// timeout para esperar os dados do banco
		if(isNaN(total)){
			$("#cs-totalItens").html("Total de Itens" + '<i class="fas fa-spinner cs-spinner">');
			setTimeout(function(){
				contaConsigEtiquetagem();
			}, 500);
			
		}
		
	});
}

function checkedContext(context){
	$.post("etiquetagem_new", {ctx: context});
}




//--------------------------------------------------------------------------------------------------------------------------------------------------------------
function enviaImpressaoQtd(ctx, qtdeGeral, data, datacomposto, qrcode){
	var qtdProcessada = '';
	var qtdeConsignado = '';
	if (ctx == 'pn' && qtdeGeral > 0){
		 qtdProcessada = '';
	}else{
		qtdProcessada = $("#qtdProcessadaQtd").text();
	}


	 var parametros =  "qrCode=" + qrcode
		+ "&nomeProduto=" + $("#nProduto").text()
		+ "&dadosProduto1=" + $("#dadosProduto1Qtd").text()
		+ "&dadosProduto2=" + $("#dadosProduto2Qtd").text()
		+ "&nomeSetor=" + $("#setor").text()
		+ "&metodo=" + $("#spanMetodoQtd").text()
		+ "&qtdeProc=" + qtdProcessada
		+ "&qtdeConsignado=" + qtdeConsignado
		+ "&lote=" + $("#txLoteQtd").val()
		+ "&item=" + $("#idProdutoQtd").val()
		+ "&composicao=" + datacomposto;
	
	var optradio = $("input[name='optradio']:checked").val()
	
	var params = [];

	setTimeout(function(){
		localStorage.setItem(qrcode, parametros);
	}, 300);
	



	// if(localStorage.getItem("imprimir") != null){	
		
	// 	params = JSON.parse(localStorage.getItem("imprimir"));
	// 	params.push(parametros);
	// 	console.log(params);
	
	// }else{
	// 	params.push(parametros);
	// }


	
	//localStorage.setItem("imprimir", JSON.stringify(params));

	

	//var oJan = window.open("etiqueta_qtd?" + localStorage.getItem("imprimir"));

	setTimeout(function(){ 
		$("#listaItens").load(location.href+" #listaItens>*","");
		contaComunEtiquetagem()
		contaConsigEtiquetagem()
		$('#tlaEtiquetarProduto').modal("hide");
	}, 300);

}	

//slEEsterilizacao
function saveQtd(data){
	$('#idProdutoQtd').val(data[1]);
	$('#qrcodeQtd').val(data[2]);
	//$('#metodoQtd').html(data[18]);
	//$('#iditemQtd').text(data[1]);

	$.get("view/Barcode2/gerabarras.php?code=" + data[2], function(){
	
		$.post("etiquetagem_new",{qrcodeQtd: data[2], ctx:'pn', iditem:data[0], dataEsterilizacao:$("#dataEsterilizacaoQtd").text(), dataLimite:$('#dataLimiteQtd').text(),
		 lote:$("#txLoteQtd").val(), idSolicitacao:$("#txNumero").text(), limiteUso:$("#dataLimiteQtd").val(), composto:data[15], qtde:$("#qtdeItem").val(), idproduto:data[1], metEsterilizacao:$("#slMEsterilizacao").val(), eqEsterilizacaoet:$("#slEEsterilizacaoet").val()},
			 function(data){
			 	data = data.split('*');
			 	qrcode = data.splice(0);
			 	qrcode = qrcode[1];
			 	data = qrcode[0] + qrcode[2];
				var datacomposto = data
				var ctx = 'pn';
				var qtdeGeral = null;
				

				if(ctx == 'pn'){
					
					 $.post("etiquetagem_new", {qrcodeQtd: qrcode, idpn:$("#idProdutoQtd").val(), acao:'getquantidadepn'}, function(data){
						  
						  data = data.split('*');
						  qtdeGeral = data[0];
						  qrcode = data[1];
						 
						  enviaImpressaoQtd(ctx, qtdeGeral, data, datacomposto, qrcode);
					 });
				}

			});

	});
}

$(document).ready(function(){
	
	$('.lablesToPrint').click(function(event) {

		var selected = false;
		var count = 0;


		$.each($('.lablesToPrint'), function(index, val) { 
			if(this.checked){ 
				selected = true;
				count++
			}
		});

		$('#qtd_to_print').val(count);

		if(selected){
			$('#imprimir_selecionados').prop('disabled', false );	
		}else{
			$('#imprimir_selecionados').prop('disabled', true);
		}
		
	});

	
});


function printProdComQuantidade () {

	localStorage.clear();
	var lablesToPrint = $('.lablesToPrint');
	var qrcode = [];
	var count = 0;
	$.each(lablesToPrint, function(index, val) { 
		if(this.checked){ 
			count++
			qrcode.push(this.value)
		}
	});

	var diferent_products = false;

	$.each(qrcode, function(index, val) {
	
		var qrfirst = qrcode[0].split('.')[0];
		var qratual = val.split('.')[0];
		 
		 if(qrfirst != qratual){
		 	alert('Por favor, não selecione produtos diferentes para etiquetagem em massa!');
		 	diferent_products = true;
		 	return false;
		 }

	});

	if(diferent_products){ return }

	if(qrcode.length == 0){
		alert('Nenhum produto selecionado para impressão');
		return
	}

	var temp = '';
	$.each(qrcode, function(index, val) {
		 temp += val + '--';
	});
	temp = $.trim(temp);
	$('#qrcodesToPrint').text(temp);

	$.post("etiquetagem_new", { 
		acao:'buscar', 
		qrcode:qrcode[0], 
		product:$("input[name='optradio']:checked").val() }, 
		function(data){
			data = $.trim(data);
			data = data.split("*;*");
			$('#nProduto').text(data[3]);
			$("#dadosProduto1Qtd").text(data[4]);
			$("#dadosProduto2Qtd").text(data[5]);
			$('#spanMetodoQtd').html(data[18]);
			$('#equipamento_metodoQtd').html(data[19]);
			$('#dataEsterilizacaoQtd').text(data[12]);
			$("#dataLimiteQtd").html(data[13]);
			$('#spanLimiteUsoQtd').html(data[13]);
			$('#qtdProcessadaQtd').text(data[9]);
			//$('#txLoteQtd').val(data[11]);
		});
	$('#qtd_itens').text('Quantidade de itens: ' + count);
	$("#tlaEtiquetarProdutoQuantidade").modal();
}

$(document).ready(function() {

	$("#btImprimirQtd").click(function(){

		var ultimo_loteQtd = $('#txLoteQtd').val();
		$.post('etiquetagem_new', {acao:'setLoteQtd', ultimo_loteQtd:ultimo_loteQtd },function(data){});

		$('.loader').show();
		var qrcodesToPrint = $('#qrcodesToPrint').text().split('--');
		qrcodesToPrint.push('end');
		var metodoEsterilizacao = $("#slMEsterilizacao").val();

		if($("#slLimiteUso").val() == 0)
		{
			alert('Escolha a data limite de uso!');

		}else if( $('#txLoteQtd').val() == "" )
            {
			    alert('Informe o lote do item!');
			    $("#txLoteQtd").focus();

			}else if($("#slMEsterilizacao").val() == 0)
				{
				alert('Por favor escolha um método de esterilização!');

				}else
				{
					

					// LOOP PARA SETAR OS PRODUTOS COMO PRONTOS NA TABELA ITENS_SOLICITAï?½ï?½O
					$.each(qrcodesToPrint, function(index, val) {
						
						setTimeout(function(){
							if(!(val === "end") && !(val === '')){
							
								 $.post("etiquetagem_new", {acao:'buscar', qrcode:val, product: 'pn'/*, ses:$("#txId").val()*/}, function (data) {
							 		data = $.trim(data);
									data = data.split("*;*");

							 		// Seta o produto como pronto na tabela de produtos
									$.post('etiquetagem_new', {acao:'setProntos', value:1, idproduto: data[1]}, function(data){});

									saveQtd(data);

								});

							}
						}, 150)
						
						
					});
				
					// imprimir todas de uma vez

					var qtd_to_print = $('#qtd_to_print').val();

					var done = false;
					
					var interval = setInterval(function(){

						
						//var data = localStorage.getItem("imprimir")

						var information = function () {

							    var values = [],
							        keys = Object.keys(localStorage),
							        i = keys.length;

							    while ( i-- ) {
							        values.push( localStorage.getItem(keys[i]) );
							    }

							    return values;
							}

			
											
						setTimeout(function(){
								data = JSON.stringify(information());

								if(information().length == 0){
								console.log('null');
							}else{

								if(!done){
									var count = information().length;
									
									$.post("etiqueta_qtd", {action: 'process', data: data, count:count}, function(data) {
										//console.log(data);
										data = data.split('***.***');

										$.post('etiqueta_qtd', {action:'store', nome_produto:$("#nProduto").text(), qtd:data[1], lote:$('#txLoteQtd').val(), data:data[0]}, function(data){
											console.log(data);
											if(data){
												var oJan = window.open("etiqueta_qtd?print=true&especific=off")
												$('.loader').hide();
												setTimeout(function(){ 
													//$("#listaItens").load(location.href+" #listaItens>*","");
													contaComunEtiquetagem()
													contaConsigEtiquetagem()
													$('#tlaEtiquetarProdutoQuantidade').modal("hide");
													$('#txQrcode').focus();
													
												}, 1500);
											
												location = location;
												
											}
										});

									})
									clearInterval(interval);
								}

								done = true;

							}


						}, qtd_to_print*1000);
							
						
						
						


					 
					}, 2000);

				
					
					
					
				}
				

		});
});


// Reimpressï?½o de etiquetas em massa
// --------------------------------------------------------------------------------------------------------------
function historicoDeImpressao() {
	$("#tlaHistoricodeImpressao").modal();
	$("#historicoInput").focus();
}

function dataReverse(data){
  var d = data.split(' ');
  var data_temp = d[0].split('/').reverse().join('-');
  var hora_temp = d[1];
  var new_data = data_temp + ' ' + hora_temp;
  return new_data
}

function sortTable(position) {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("historico_impressao_table");
  switching = true;

 $('.arr_down').each(function( index , element  ){
      $(element).css("display" , "none");
  });

var arr_to_show = '#arr_down' + position;
$(arr_to_show).show();
  
  while (switching) {
    
    switching = false;
    rows = table.rows;
    
    for (i = 1; i < (rows.length - 1); i++) {
      
      shouldSwitch = false;
     
      x = rows[i].getElementsByTagName("TD")[position];
      y = rows[i + 1].getElementsByTagName("TD")[position];
      

      if(position === 1){
      	if (Date.parse(dataReverse(x.innerHTML)) < Date.parse(dataReverse(y.innerHTML))) {
	        
	        shouldSwitch = true;
	        break;
      	}

      }else{

      	if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
	        
	        shouldSwitch = true;
	        break;
      	}
      }
      
    }
    if (shouldSwitch) {
      
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}

function setCalendarOnInput(){

	var selector = document.getElementById('historicoSelect');
  	var selectedValue = selector[selector.selectedIndex].value;

  	if(selectedValue == 1){
  		var inputb = document.getElementById('historicoInput_b');
  		inputb.style.display = 'block';
  		var input = document.getElementById('historicoInput');
  		input.style.display = 'none';

  	}else{
  		var inputb = document.getElementById('historicoInput_b');
  		inputb.style.display = 'none';
  		var input = document.getElementById('historicoInput');
  		input.style.display = 'block';
  	}
}


// Busca para tabela no modal histï?½rico de impressï?½o em massa
function buscaHistoricoImpressao(input_b = null) {

  // Parametro selecionado
  var selector = document.getElementById('historicoSelect');
  var selectedValue = selector[selector.selectedIndex].value;
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  if(input_b){
  	input = document.getElementById('historicoInput_b');
  }else{
  	input = document.getElementById('historicoInput');
  }
  
  filter = input.value.toUpperCase();
  table = document.getElementById("historico_impressao_table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[selectedValue];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


function printEspecifc(id){
	var oJan = window.open("etiqueta_qtd?print=true&especific=on&id=" + id)
}

/*
 * Desenvolvido por Gustavo H. M. Matias
 * e Augusto Cesar Scarpin
 *
 * Brothers Solu??Â?Â½??Â?Â½es em T.I. ??Â?Â½ 2013
*/