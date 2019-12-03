$(document).ready(function(){

	$('#pro_img').click(function(){
		$('#img_pop_up_main').toggle();

		$("body").click(function(event) {
			var target = event.target.getAttribute('id');
 			if(target !== 'pro_img_big' && target !== 'pro_img'){
 				$('#img_pop_up_main').fadeOut(300);
  			}
  		})
	})


	$.post('setores_new', {action: 'getCmeId'}, function(data){
		$('#sector_id').val($.trim(data));

	})


	var urlParams = new URLSearchParams(window.location.search);

	if(urlParams.get('id') == '' || urlParams.get('id') == 'undefined'){
		location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
	}
	
	//Estamos trabjando en eventos ventilatorio_INICIO
	
	$('#disponivel').hide();
	$('#qtdeProduct').hide();
	$('#hidde_teste').hide();
	$('#vent').hide();
	$('#inst').hide();
	
		$("#equip").change(function(){
			
	});

	
	 $( "#slEEsterilizacao option:selected" ).each(function() {
		//console.log($( this ).val());
		
		$('#hidde_teste').show();	
	 });


	 $( "input[name='estandar']:checked" ).each(function() {
		var estandar = $("input[name='estandar']:checked").val()
		//console.log('fgdfgfdg');
		if(estandar == '0'){
		
			$('#vent').hide();
			$('#inst').show();
		}else if(estandar == '1'){
		
			$('#vent').show();
			$('#inst').hide();
		}

		$.post("solicitacoes_new", { acao: 'searchLote', idequip: $( "select#slEEsterilizacao option:checked" ).val(), tipocampo: estandar}, function(data){
			
			if(estandar == '0'){
				$('#in').val(data);
			}else{
				$('#vn').val(data);
			}
			
		});
	 });
	 
	 
		$("input[name='estandar']").live("click", function(){
			var estandar = $("input[name='estandar']:checked").val()
			if(estandar == '0'){
				//alert("es Ventilatorio ");
					$('#inst').show();
					$('#vent').hide();
			}else if(estandar == '1'){
						//alert("es Instrumental ");
						$('#inst').hide();
						$('#vent').show();
			}

			$.post("solicitacoes_new", { acao: 'searchLote', idequip: $( "select#slEEsterilizacao option:checked" ).val(), tipocampo: estandar}, function(data){
				
				if(estandar == '0'){
					$('#in').val(data);
				}else{
					$('#vn').val(data);
				}
				
			});		
		});
		
		
		

	/*$("#ventilatorio").on("click", function(){
	
		alert('teste');
		$('#vent').show();
			
	});
	
		$("#instrumental").on("click", function(){
	
		alert('teste');
		$('#inst').show();
			
	});*/
	
	

	
	//----------------------------
	
	//Estamos trabjando en eventos ventilatorio_FIN
	
	

	$('#lbProdutoinvertido').hide();
	
	$('.correct-pn').html('<i class="fas fa-check"></i>')
		
	$("input[name='chProduto']").live("click", function(){
		var chProduto = $("input[name='chProduto']:checked").val()
		if(chProduto == 'pn'){
			$('.correct-pn').html('<i class="fas fa-check"></i>')
			$('.correct-pc').html('')

		}else if(chProduto == 'pc'){
			$('.correct-pc').html('<i class="fas fa-check"></i>')
			$('.correct-pn').html('')			
		}
	});
	

	
	/*$("#bt-pn").live("click", function(){
		$('.correct-pn').html('<i class="fas fa-check"></i>');
	});

	$("#bt-pc").live("click", function(){
		$('.correct-pc').html('<i class="fas fa-check"></i>');
	});*/
	
	countComunQtde();
	//console.log($("#txIdSol").val());
		// cleverson matias
	/*function contaItens(){
		var totalComum = parseInt($('.count-comuns').length);
		var totalConsig = parseInt($('.count-consignados').length);

		var hiddenElements = $( "body" ).find( ":hidden" ).not( "script" );

		totalComum = (totalComum < 0) ? 0 : totalComum;
		totalConsig = (totalConsig < 0) ? 0 : totalConsig;

		console.log(totalComum);
		console.log(totalConsig);
		console.log(hiddenElements.length);

		var total = totalComum + totalConsig;

		$(".txQtdeItens").text("Total de produtosasaas: " + total);
		$("#totalComum").text("Comuns:");

	}*/

	// scroll comum
	$(document).on('click', '.comum', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#comum')).offset().top
	    }, 500);
	});

	// scroll consignados
	$(document).on('click', '.consignados', function (event) {
	    event.preventDefault();

	    $('html, body').animate({
	        scrollTop: $($('#consignados')).offset().top
	    }, 500);
	});

	// scroll hide over 100
	$('#ancoras').hide();
	$(document).on('scroll', function () {
	    var scrollActual = $(window).scrollTop();

	    if(scrollActual > 100){
	    	$('#ancoras').fadeIn();
	    }else{
	    	$('#ancoras').fadeOut();
	    }
	});

	$('#frm-substituir').submit(function(event) {
		event.preventDefault();
		inserir(this);
	});

	$(".pagination li").live("click", function(){
		if($(this).attr('class') != "disabled" && $(this).attr('class') != "active"){
			if($(this).attr('pag') == "prev"){
				var pag = parseInt($("#pagAtiva").val()) - 1;
			} else if($(this).attr('pag') == "next"){
				var pag = parseInt($("#pagAtiva").val()) + 1;
			} else {
				var pag = $(this).attr('pag');
			}
			$.post("solicitacoes_new", { buscar:$("#txBuscar").val(), pag:pag }, function(data){
				$("#paginacao").html(data);
				$.post("solicitacoes_new", { acao:"lista", buscar:$("#txBuscar").val(), pag:pag }, function(dataLista){
					$("#lista").html(dataLista);
				});
			});
		}
	});

	$("#tlaProduto").on("shown", function(){
		$("#txQrcode").focus();
	});

	if($("#txNpedido").val() == "" || $("#txNpedido").val() == "0") {
		$("#btAdProd").hide();
	} else {
		$("#btAdProd").show();
	}

	$("#txNpedido").keyup(function(){
		if($("#txNpedido").val() != "" && $("#txNpedido").val() != "0") {
			$("#btAdProd").show();
		} else {
			$("#btAdProd").hide();
		}
	});

	$("#btAdProd").click(function(){
		$("#divRestoItem").hide();
		$("#txQrcode").val('');
		$("#txSetor").text('');
		$("#txQtdmaxima").text('');
		$("#txGmaterial").text('');
		$("#slMEsterilizacao").val('0');
		$("#slRTecnico").val('0');
		$("#qtdProc").text('');
		$("#txReuso").val('');
		$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
		$("#btSalvarPro").hide();
		$("#btOcorrenciaPro").hide();
		$("#lbProdutoinvertido").hide();
		$("#btDescartarPro").hide();
		$("#lbProdutoDescartado").hide();
		$("#lbProdutoNaoCadastrado").hide();
		var valores = $("#formSolicitacoes").serialize();
		valores += "&setor=" + $("#slSetor").val();
		
		//alert(valores);
		$.post("solicitacoes_new", valores, function(data){
			//alert(data);
			if($.trim(data) != "")
				$("#txId").val($.trim(data));
			$("#txIdSolicitacao").text($("#txId").val());
			$("#txIdSol").val($("#txId").val());
		});
	});

	$("#btSalvarPro").click(function(){

		$.post('solicitacoes_new', {acao:'setLote', lote: $('#loteEquip').val()});

		var ventilatorio = $('#ventilatorio').prop('checked') ? 'ventilatorio' : 'no';
		var instrumental = $('#instrumental').prop('checked') ? 'instrumental' : 'no';
		$.post('solicitacoes_new', {acao:'setLoteDetergente', ventilatorio:ventilatorio, instrumental:instrumental});

		if($("#disponivel").val() != ''){

			if($("#txQtde").val() == '' && $("#disponivel").val() > 0){
				alert('Este produto tem quantidade, adicione a quantide para prosseguir');
			}else if($("#disponivel").val() == 0){
				alert('a solicitação já contém a quantidade máxima deste produto.');
				$("#btSalvarPro").hide();
				$("#txQtde").prop( "disabled", true );
			}else{

				if($("#slMEsterilizacao").val() == 0){
					alert('Escolha um método de esterilização!');
				} else if ($("#slRTecnico").val() == 0){
					alert('Escolha um responsável técnico!');
					$("#slRTecnico").focus()
				} 
				 else if ($("#slEEsterilizacao").val() == 0){
					alert('Escolha o Equipamento!');
					$("#slEEsterilizacao").focus()
				}
				else if ($("#loteEquip").val() == ''){
					alert('Informe o lote do equipamento ');
					$("#loteEquip").focus()
				} else {
			
					var valores = $("#formSolicitacoesProduto").serialize();


				
				///////////////////////////////////////////////////////////////////////////////////////////////////
					
					$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:$('#txQrcode').val(), tesID: '', verifica_filhotrans: 'produtopai_sistema'}, function(data){
						//alert('1')
					if ($.trim(data) != "ERRO" && $.trim(data) != "ERROII" && $.trim(data) != "ERROIII" && $.trim(data) != "ERROIV" && $.trim(data) != "ERROV"
						&& $.trim(data) != "ERROVI" && $.trim(data) != "ERROVII" && $.trim(data) != "ERROVIII" && $.trim(data) != "ERROIX"){
						$.post('transferenciaEstoque_new',{acao: 'transferir', tesID: $.trim(data), setID:$('#slSetor').val(), retiradoPor:'sistema'},function(data){
							data = $.trim(data);
						});

					}});
				///////////////////////////////////////////////////////////////////////////////////////////////////


					


					$.post("solicitacoes_new", valores, function(data){
						listaItens($("#txIdSol").val());
						listaItensConsignados($("#txIdSol").val());

						if ($.trim(data) == "OK"){

							// limpa a tela e deixa pronta para o proximo item ao inves de fecha-la
							$("#divRestoItem").hide();
							$("#txQrcode").val('');
							$("#txQtde").val('');
							$("#ckQtde").val('');
							$("#qtdeProduct").val('');
							$("#boxQtde").hide();
							$("#txProduto").text('');
							$("#txSetor").text('');
							$("#txQtdmaxima").text('');
							$("#txGmaterial").text('');
							$("#slMEsterilizacao").val('0');
							$("#slEEsterilizacao").val('0');
							$("#slRTecnico").val('0');
							$("#txQrcode").focus();
							$("#qtdProc").text('');
							$("#txReuso").val('');
							$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
							$("#btSalvarPro").hide();
							$("#btOcorrenciaPro").hide();
							$("#btDescartarPro").hide();
							$("#lbProdutoDescartado").hide();
							$("#lbProdutoNaoCadastrado").hide();
							$("#txQrcode").focus();
						} else{

							var aux1 = $.trim(data).split("*");
							
							
								
							if( aux1[0] == "OKCOMPOSTO"){
								// fecha a tela e chama a tela de listagem de produto composto para leitura de seus produtos filhos
								$("#txProdutoPai").text($("#txProduto").text());
								$.post("produtosCompostos", {idpai:$("#txIdproduto").val(), modo:"2", idsol: aux1[1]}, function(data){

									var aux = $.trim(data).split("*;*");

								});
								$("#tlaProduto").modal("hide");
								$("#telaListaProdutos").modal();
						
							} else {
								//alert("Erro ao efetuar cadastro!");
							}

						} 
					});
				}
			}
		}else{

			if($("#slMEsterilizacao").val() == 0){
				alert('Escolha um método de esterilização');
			} else if ($("#slEEsterilizacao").val() == 0){
				alert('Escolha o Equipamento');

			} else if ($("#loteEquip").val() == 0){
				alert('Escolha o lote do equipamento!');
			} else {



				///////////////////////////////////////////////////////////////////////////////////////////////////
					
					$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:$('#txQrcode').val(), tesID: '', verifica_filhotrans: 'produtopai_sistema'}, function(data){
						console.log(data)
					if ($.trim(data) != "ERRO" && $.trim(data) != "ERROII" && $.trim(data) != "ERROIII" && $.trim(data) != "ERROIV" && $.trim(data) != "ERROV"
						&& $.trim(data) != "ERROVI" && $.trim(data) != "ERROVII" && $.trim(data) != "ERROVIII" && $.trim(data) != "ERROIX"){
						
						$.post('transferenciaEstoque_new',{acao: 'transferir', tesID: $.trim(data), setID:$('#slSetor').val(), retiradoPor: 'sistema'},function(data){
							data = $.trim(data);
						});

					}});
				///////////////////////////////////////////////////////////////////////////////////////////////////


				var valores = $("#formSolicitacoesProduto").serialize();
				
				$.post("solicitacoes_new", valores, function(data){

					listaItens($("#txIdSol").val());
					listaItensConsignados($("#txIdSol").val());
					if ($.trim(data) == "OK"){

						// limpa a tela e deixa pronta para o pr?ximo item ao inv?s de fech?-la
						$("#divRestoItem").hide();
						$("#txQrcode").val('');
						$("#txQtde").val('');
						$("#ckQtde").val('');
						$("#qtdeProduct").val('');
						$("#boxQtde").hide();
						$("#txProduto").text('');
						$("#txSetor").text('');
						$("#txQtdmaxima").text('');
						$("#txGmaterial").text('');
						$("#slMEsterilizacao").val('0');
						$("#slEEsterilizacao").val('0');
						$("#slRTecnico").val('0');
						$("#txQrcode").focus();
						$("#qtdProc").text('');
						$("#txReuso").val('');
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").hide();
						$("#btOcorrenciaPro").hide();
						$("#btDescartarPro").hide();
						$("#lbProdutoDescartado").hide();
						$("#lbProdutoNaoCadastrado").hide();
						$("#txQrcode").focus();
					} else{

						var aux1 = $.trim(data).split("*");
						console.log('aux1: ' + aux1);
						//aqui carrega a lista depois de cadastrar um produto composto na solicitação
						if( aux1[0] == "OKCOMPOSTO"){

							// fecha a tela e chama a tela de listagem de produto composto para leitura de seus produtos filhos
							$("#txProdutoPai").text($("#txProduto").text());
							$.post("produtosCompostos", {idpai:$("#txIdproduto").val(), modo:"2", idsol: aux1[1]}, function(data){
								
								var aux = $.trim(data).split("*;*");
								$("#divListaFilhos").html(aux[4]);//lista filhos 1
								$('#sobra').val(aux[6]);
							});
							$.post("solicitacoes_new",{acao:'veridsol', id: $("#txIdproduto").val()}, function(data){
								
								var auxver = $.trim(data).split("*;*");
		
								$('#iditemsol').text(auxver[0]);
							
								$('#txQrcodePai').text(auxver[1]);

							});	
							$("#tlaProduto").modal("hide");
							$("#telaListaProdutos").modal();

						} else {
							// limpa a tela e deixa pronta para o pr?ximo item ao inv?s de fech?-la
						$("#divRestoItem").hide();
						$("#txQrcode").val('');
						$("#txQtde").val('');
						$("#ckQtde").val('');
						$("#qtdeProduct").val('');
						$("#boxQtde").hide();
						$("#txProduto").text('');
						$("#txSetor").text('');
						$("#txQtdmaxima").text('');
						$("#txGmaterial").text('');
						$("#slMEsterilizacao").val('0');
						$("#slEEsterilizacao").val('0');
						$("#slRTecnico").val('0');
						$("#txQrcode").focus();
						$("#qtdProc").text('');
						$("#txReuso").val('');
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").hide();
						$("#btOcorrenciaPro").hide();
						$("#btDescartarPro").hide();
						$("#lbProdutoDescartado").hide();
						$("#lbProdutoNaoCadastrado").hide();
						$("#txQrcode").focus();
						}

					} 
				});
			}							
		}
	});

	$('.qtdeQrcode').hide();
	$('#boxNew').hide();
	
	var intervalo = 0;
	$("#txQrcode").keyup(function(e){
		// oculta mensagens
		$('#lbProdutoinvertido').hide();
		$("#lbProdutoDescartado").hide();
		$("#lbProdutoNaoCadastrado").hide();
		$("#txQtde").val('');

		var qr = $("#txQrcode").val();

		/*
		 * Verifica se o evento ? Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		if($("#txQrcode").val().length > 0){
			// intervalo at? executar a fun??o
			clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				 buscarProduto();
	//------------------------------
		      }, 300);
		}

		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});

	$("#telaListaProdutos").on("shown", function(){
		$("#txQrcodeFilho").val("");
		$("#txFilhoLido").text("");
		$("#lbReprocFilho").hide();
		$("#txQrcodeFilho").focus();
		
		$.post("solicitacoes_new",{acao:'verautorizacao', id: $("#txIdproduto").val(), iditem: $.trim($('#iditemsol').text())}, function(data){
			console.log(data)
			if($.trim(data) == 'nao'){
				$('#naoautorizado').text('não autorizada');
			}else if($.trim(data) == 'sim'){
				$('#autorizado').text('Autorizada');
			}else{
				$('#autorizado').text('-');
			}
		});
	});

	//$("#txQrcodeFilho").keypress(function(e){
	$("#txQrcodeFilho").keyup(function(e){
		/*
		 * Verifica se o evento ? Keycode (IE e outros)
		 * Se não for, pega o evento Which (Firefox)
		*/
		//var tecla = (e.keyCode?e.keyCode:e.which);
		if($("#txQrcodeFilho").val().length > 0){

			// intervalo at? executar a fun??o
			/*clearTimeout(intervalo);
			intervalo = window.setTimeout(function() {
				buscarProdutoFilho();
			}, 500);*/
	
			var qr = $("#txQrcodeFilho").val();
			$.post("produtosCompostos", {acao:'buscarFilho', qrcode:qr, pai:$("#txIdproduto").val(), idses:$("#txId").val()}, function(data){
				//console.log(data);
				if (data.indexOf("NAOPERTENCE") >= 0) {
					var aux = $.trim(data).split("*;*");
					if(aux[1] != " - "){
						alert("Atenção! Este produto pertence a seguinte composição:\n\n" + aux[1] + "\n\nPortanto, não pode ser inserido como item deste produto composto.");
					} else {
						alert("Este produto não pertence a nenhuma composição e não pode ser inserido como item deste produto composto.");
					}
					$("#txQrcodeFilho").val("");
					$("#txQrcodeFilho").focus();
				} else if ($.trim(data) == "JAFOI") {
					alert("Produto já inserido nesta solicitação!");
					$("#txQrcodeFilho").val("");
					$("#txQrcodeFilho").focus();
				} else if ($.trim(data) != "ERRO") {
					var aux = $.trim(data).split("*;*");
					$("#idfilho").val(aux[0]);
					$("#txIdFilhoLido").val(aux[0]);
					$("#txFilhoLido").text(aux[1]);
					$("#qtdProcFilho").text(aux[2]);
					$("#txQtdmaximaFilho").text(aux[3]);
					$("#divFilhoLido").show();
					if(parseInt($("#qtdProcFilho").text()) > parseInt($("#txQtdmaximaFilho").text())) {
						$("#lbReprocFilho").attr("style", "color: red; font-weight: bold;");
						$("#btDescartarFilho").show();
					} else {
						$("#lbReprocFilho").attr("style", "color: #333333; font-weight: bold;");
						/*$("#btAdicionarFilho").show();
						$("#btOcorrenciaFilho").show();
						$("#btAdicionarFilho").focus();*/
					}
					
	
					$iditemsol = $("#linhaFilho_" + $("#txIdFilhoLido").val()).attr('idsol');
					$.post("solicitacoes_new", {acao:"slProd", idSolicitacao:$("#txIdSol").val(), idProduto:$("#txIdFilhoLido").val(),
					 metEsterilizacao:$("#slMEsterilizacao").val(), eqEsterilizacao:$("#slEEsterilizacao").val(), nReuso:$("#qtdProcFilho").text(), rTecnico:$("#slRTecnico").val(),
					 smaid:$('#txSmaID').val(), iditemsol: $iditemsol, loteequipamento: $("#loteEquip").val(), estandar: $(".tipodetergente").val(), loteenzimatico: $('#vn').val(), loteneutro: $('#in').val()},
					 function(data){
						 console.log(data) // OK
						if ($.trim(data) == "OK"){
							listaItens($("#txIdSol").val());
							$("#linhaFilho_" + $("#txIdFilhoLido").val()).hide(500);
							comporPai($("#txIdproduto").val(), 34);
								$.post("transferenciaEstoque_new",{acao:'buscar', qrcode:$('#txQrcodeFilho').val(), tesID: '', verifica_filhotrans: 'transferencia_sistema'}, function(data){
									// $.post('transferenciaEstoque_new',{acao: 'transferir', tesID: $.trim(data), setID:$('#sector_id'), retiradoPor: 'sistema'},function(data){
									// 	alert(data)
									// 	data = $.trim(data);
									// });
								});
							$("#txIdFilhoLido").val("0");
							$("#txFilhoLido").text("");
							$("#divFilhoLido").hide();
							$("#btAdicionarFilho").hide();
							$("#btOcorrenciaFilho").hide();
							$("#lbFilhoDescartado").hide();
							$("#btDescartarFilho").hide();
							$("#txQrcodeFilho").val("");
							$("#txQrcodeFilho").focus();

						} else {
							alert("Erro ao efetuar cadastro!");
						}
					});					
	
					if(aux[4] > 0) { //produto descartado
						/*$("#btAdicionarFilho").hide();
						$("#btDescartarFilho").hide();
						$("#lbFilhoDescartado").show();
						$("#txQrcodeFilho").select();*/
					}
					$("#btAdicionarFilho").focus();
				} else {
					$("#txIdFilhoLido").val("0");
					$("#txFilhoLido").text("");
					$("#divFilhoLido").hide();
					$("#btAdicionarFilho").hide();
					$("#btOcorrenciaFilho").hide();
					$("#lbFilhoDescartado").hide();
					$("#btDescartarFilho").hide();
				}
			});

		}
		// impede o sumbit caso esteja dentro de um form
		e.preventDefault(e);
		return false;
	});

	$("#btAdicionarFilho").click(function(){

		$iditemsol = $("#linhaFilho_" + $("#txIdFilhoLido").val()).attr('idsol');

		$.post("solicitacoes_new", {acao:"slProd", idSolicitacao:$("#txIdSol").val(), idProduto:$("#txIdFilhoLido").val(),
		 metEsterilizacao:$("#slMEsterilizacao").val(), nReuso:$("#qtdProcFilho").text(), rTecnico:$("#slRTecnico").val(),
		 smaid:$('#txSmaID').val(), iditemsol: $iditemsol },
		 function(data){
			if ($.trim(data) == "OK"){
				listaItens($("#txIdSol").val());
				
				$("#linhaFilho_" + $("#txIdFilhoLido").val()).hide(500);
				$("#txIdFilhoLido").val("0");
				$("#txFilhoLido").text("");
				$("#divFilhoLido").hide();
				$("#btAdicionarFilho").hide();
				$("#btOcorrenciaFilho").hide();
				$("#lbFilhoDescartado").hide();
				$("#btDescartarFilho").hide();
				$("#txQrcodeFilho").val("");
				$("#txQrcodeFilho").focus();
				
			} else {
				alert("Erro ao efetuar cadastro!");
			}
		});
	});

	$("#btDescartarFilho").click(function(){
		if(confirm("Tem certeza que deseja descartar esse produto?")) {
			var valores = "idproduto=" + $("#txIdFilhoLido").val()
						+ "&idocorrencia=1"; // idocorrencia = 1, assumindo que id 1 ? a ocorrência de descarte por excesso de reprocessamento (fixo)
			$.post("ocorrenciasprodutos", valores, function(data){
				if($.trim(data) != "ERRO"){
					$("#linhaFilho_" + $("#txIdFilhoLido").val()).hide(500);
					$("#txIdFilhoLido").val("0");
					$("#txFilhoLido").text("");
					$("#divFilhoLido").hide();
					$("#btAdicionarFilho").hide();
					$("#btOcorrenciaFilho").hide();
					$("#lbFilhoDescartado").hide();
					$("#btDescartarFilho").hide();
					$("#txQrcodeFilho").val("");
					$("#txQrcodeFilho").focus();
				} else {
					alert("Erro ao realizar a operação! Tente novamente.");
				}
			});
		}
	});

	$("#btOcorrenciaPro").click(function(){
		lancarOcorrencia($("#txIdproduto").val(), $("#txProduto").text());
		$("#boxQtde").hide();
		$("#txQtde").val('');
		$("#ckQtde").val('');
		$("#qtdeProduct").val('');
	});

	$("#btOcorrenciaFilho").click(function(){
		lancarOcorrencia($("#txIdFilhoLido").val(), $("#txFilhoLido").text());
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
		$.post("ocorrenciasprodutos",{idocorrencia:$("#slOcorrencia").val(), idproduto:$("#txIdproduto").val(), obs:$("#txObs").val(), produtopai: $("#ProdutoPai").val(), produtopaiid: $("#ProdutoPaiId").val() }, function(data){
			if($.trim(data) == "ERRO"){
				alert("Erro ao efetuar cadastro!");
			} else {
				$("#tlaLancarOcorrencia").modal('hide');
				if($.trim(data) == "S") { // descarte do produto
					$("#divRestoItem").hide();
					$("#txIdproduto").val('');
					$("#txCliente").text('');
					$("#txCidade").text('');
					$("#txQtdmaxima").text('0');
					$("#txGmaterial").text('');
					$("#qtdProc").text('');
					$("#txReuso").val('');
					$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
					$("#btSalvarPro").hide();
					$("#btOcorrenciaPro").hide();
					$("#btDescartarPro").hide();
					$("#lbProdutoDescartado").hide();
					$("#lbProdutoNaoCadastrado").show();
					$("#txQrcode").val("");
					$("#txQrcode").focus();
				} else if($.trim(data) == "R") { // anula 1 reuso
					$("#txReuso").val(parseInt($("#qtdProc").text()) - 1);
					$("#qtdProc").text($("#txReuso").val());
				}
			}
		});
	});

	$("#btDescartarPro").click(function(){
		if(confirm("Tem certeza que deseja descartar esse produto?")) {
			var valores = "idproduto=" + $("#txIdproduto").val()
						+ "&idocorrencia=1"; // idocorrencia = 1, assumindo que id 1 ? a ocorrência de descarte (fixo)
			$.post("ocorrenciasprodutos", valores, function(data){
				if($.trim(data) != "ERRO"){
					// limpa a tela e deixa pronta para o pr?ximo item ao inv?s de fech?-la
					$("#divRestoItem").hide();
					$("#txQrcode").val('');
					$("#txProduto").text('');
					$("#txSetor").text('');
					$("#txQtdmaxima").text('');
					$("#txGmaterial").text('');
					$("#slMEsterilizacao").val('0');
					$("#slRTecnico").val('0');
					$("#txQrcode").focus();
					$("#qtdProc").text('');
					$("#txReuso").val('');
					$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
					$("#btSalvarPro").hide();
					$("#btOcorrenciaPro").hide();
					$("#btDescartarPro").hide();
					$("#lbProdutoDescartado").hide();
					$("#lbProdutoNaoCadastrado").hide();
					$("#txQrcode").focus();
				} else {
					alert("Erro ao realizar a operação! Tente novamente.");
				}
			});
		}
	});

	$("#btSalvar").click(function(){
		var valores = $("#formSolicitacoes").serialize();

		$.post("solicitacoes_new", valores, function(data){
			if($.trim(data) != "ERRO"){
				location.href = "solicitacoes";
			} else {
				alert("Erro ao efetuar cadastro!");
			}

	 	});
	});

	$("#btVoltar").click(function(){
		location.href = "solicitacoes";
	});

	$(".nova").live("click", function(){
		var setor = $(this).attr("id");
		$.get("solicitacoes_new?nova=" + setor, function(data){
			
			var split = $.trim(data).split('***');
			data = split[0];
			nSolicitacao = split[1];
			

			if( data == "EXISTE"){
				location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
			} else if(data != "ERRO"){
				location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val(); 
			} else {
				alert("Erro de conexão! Por favor, tente novamente.");
			}
		});
	});

	$(".edit").live("click", function(){
		location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
	});

	$(".delete").live("click", function(){
		if(confirm("Deseja mesmo apagar este registro?")) {
			var tag = $(this);
			$.get("solicitacoes_new?delete=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
				}
				else{
					alert("Erro ao apagar registro!");
				}
			});
		}
	});

	$(".remove").live("click", function(){ //remove item da solicitação
		//alert($(this).attr('id'))
		if(confirm("Deseja mesmo remover este produto da lista?")) {
			var tag = $(this);
			$.get("solicitacoes_new?remove=1&id=" + $(this).attr('id'), function(data){
				if($.trim(data) == "OK"){
					tag.parent().parent().hide(500);
					var aux = $(".txQtdeItens").text().split(": ");
					var qtde = parseInt(aux[1]);
					qtde--;
					countComum();
					countConsginados();
					$(".txQtdeItens").text("Total de produtos: " + qtde);
				} else {
					alert("Erro ao apagar registro!");
				}
			});

			var id_in_solicitacao = $(this).attr('id');
				$.post('transferenciaEstoque_new', {action: 'Remove_from_transf', id_in_solicitacao: id_in_solicitacao}, function(data){ 
			})

		}

		
	});

	$(".filhoPerdido").live("click", function(){
		var aux = $(this).attr("id").split("_");
		var tag = $(this);
		$.get("ocorrenciasprodutos?perdido=" + aux[1], function(data){
			if($.trim(data) == "OCO"){
				alert("não foi encontrada nenhuma ocorrência de perda de material.");
			} else if($.trim(data) == "ERRO"){
				alert("Erro de conexão! Por favor, tente novamente.");
			} else {
				tag.parent().parent().toggleClass("perdido");
				tag.fadeOut(500);
			}
		});
	});

	$('#btSubstituir').click(function() {
		window.location = 'produtos_new?substituir=1&populate=1&id='+$('#txIdproduto').val();
	});
	
	
	//atualiza??es para cliente novo		
	
	//verificar se o checkbox da quantidade foi clicado ou não
	$("#boxQtde").hide();
	/*$('#ckQtde').click(function(){
		if($(this).is(":checked")) {
			$("#boxQtde").show();
		} else {
			$("#boxQtde").hide();
		}
	});*/
	
	$("#txQtde").change(function(){
		var qtdenew = Number($("#txQtde").val());
		var disponivel = Number($("#disponivel").val());

		if(qtdenew > disponivel){
			alert('Atenção: O limite de quantidade do produto é' + $("#disponivel").val());
			$("#btSalvarPro").css("display","none");
		}else{
			$("#btSalvarPro").css("display","inline");			
		}
	});



	$.post('solicitacoes_new', {acao:'getLoteDetergente'}, function(data){
			if($.trim(data) == 'ventilatorio'){
				$('#ventilatorio').prop('checked', true);
				$('#instrumental').prop('checked', false);
			}

			if($.trim(data) == 'instrumental'){
				$('#instrumental').prop('checked', true);
				$('#ventilatorio').prop('checked', false);
			}

			var estandar = $("input[name='estandar']:checked").val()
			//console.log('fgdfgfdg');
			if(estandar == '0'){
			
				$('#vent').hide();
				$('#inst').show();
			}else if(estandar == '1'){
			
				$('#vent').show();
				$('#inst').hide();
			}

			$.post("solicitacoes_new", { acao: 'searchLote', idequip: $( "select#slEEsterilizacao option:checked" ).val(), tipocampo: estandar}, function(data){
				
				if(estandar == '0'){
					$('#in').val(data);
				}else{
					$('#vn').val(data);
				}
				
			});
	});


});

function buscarProduto() {

	//Aqui pegamos o valor da vari?veis na tela ""Adicionar Produto""
	var qr = $("#txQrcode").val();
	var set = $("#slSetor").val();
	var radio = $("input[name='chProduto']:checked").val();

	qrqtde = qr.indexOf('unb') > -1;	

	if(qrqtde == true){
		$('.qtdeQrcode').show();
		$('#boxNew').show();
		$('#divRestoItem').show();
		$('#lbProdutoinvertido').hide();
	}else{
		$.post("solicitacoes_new",{acao:'buscar', qrcode:qr, setor:set, idses:$("#txId").val(), chProduto: radio, qtde: $('#txQtdeqt').val() }, function(data){
			if ($.trim(data) == "PRONTO") {
				alert("Este produto já está pronto para uso (esterilizado)!");
				$("#txQrcode").val("");
				$("#lbProdutoNaoCadastrado").hide();
			} else if ($.trim(data) == "SETOR") {
				alert("Este produto não pertence ao setor da solicitação!");
				$("#txQrcode").val("");
				$("#lbProdutoNaoCadastrado").hide();
			} else if ($.trim(data) == "REPETIDO") {
				alert("Este produto já está incluso nesta solicitação!");
				
				var qrcode = qr.toUpperCase();				

				$('html, body').animate({
					scrollTop: $($("#"+qrcode+"")).offset().top
				}, 500);	
				
				$("."+qrcode+"").css("background-color", "red");					
				
				location.href = '#'+ qr;
				$("#tlaProduto").modal("hide");			
				$("#txQrcode").val("");
				$("#lbProdutoNaoCadastrado").hide();
			} else if ($.trim(data) != "ERRO"){
				data = $.trim(data).split(";");
				
				showinfos(data[0]);
					
			$.post("solicitacoes_new", {acao:'buscarcount', idProduto:data[0], qtdeProduct: data[11]}, function(data){
				if(radio){
					if(radio === 'pn'){
						$("#disponivel").val(data);						
					}else{
						$("#disponivel").val('');	
					}
				}
			});				

				var radio = $("input[name='chProduto']:checked").val();
				
				//console.log(data);
				//verifica se produto tem quantidade, se tiver ele abre as op??es de quantidade
				//console.log(data[11]); //quantidade do produto
				//console.log(data)
				if(data[11] > 0 && radio == 'pn'){
					$("#boxQtde").show();		
				}else{
					$("#boxQtde").hide();			
				}
				
				$("#txIdproduto").val(data[0]);
				$("#txProduto").text(data[1]);

				//get product name id
				$.post('nomesProdutos_new', {acao: 'getIdByName', name:data[1]}, function(data){
					data = $.trim(data);
					$("#pro_img").attr('src', 'img_pro/pro'+data+'_small.png');
					$("#pro_img_big").attr('src', 'img_pro/pro'+data+'.png');
				})

				
				$("#txSetor").text(data[2]);
				//$("#txCidade").text(data[3]);
				$("#txQtdmaxima").text(data[4]);
				$("#txGmaterial").text(data[5]);
				$("#qtdProc").text(data[6]);
				$("#txReuso").val(data[6]);
				$("#slMEsterilizacao").val(data[8]);
				$("#slEEsterilizacao").val(data[12]);
				$("#slRTecnico").val(data[9]);
				$("#qtdeProduct").val(data[11]);
				//$("#loteEquip").val(data[13]);
				$("#divRestoItem").show();
				$("#lbProdutoDescartado").hide();
				$("#lbProdutoNaoCadastrado").hide();
				
				//cleverson matias

				if(parseInt($("#qtdProc").text()) > parseInt($("#txQtdmaxima").text()) && radio == 'pn') {
					
					if($("#qtdeProduct").val() < 2){
						$("#lbReproc").attr("style", "color: red; font-weight: bold;");
						
						$("#btSalvarPro").hide();
						$("#btOcorrenciaPro").hide();
						$("#btDescartarPro").show();
						$("#btSubstituir").show();

						// $("#btDescartarPro").focus();
					}else {
						
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").show();
						if( data[10] == '1' ){ $("#btOcorrenciaPro").hide(); } // se for produto composto não mostra o bot?o 'Lan?ar ocorrência'
							else{ $("#btOcorrenciaPro").show(); }
						$("#btDescartarPro").hide();
						setTimeout(function(){
							$("#lbReproc").hide();
						}, 20);
					}
					
				}else {
					if($("#qtdeProduct").val() < 2){
						
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").show();
						if( data[10] == '1' ){ $("#btOcorrenciaPro").hide(); } // se for produto composto não mostra o bot?o 'Lan?ar ocorrência'
							else{ $("#btOcorrenciaPro").show(); }
						$("#btDescartarPro").hide();
						if(radio == 'pc'){
							setTimeout(function(){
							$("#lbReproc").hide();
						}, 20);
						}
						
					}else{
						$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
						$("#btSalvarPro").show();
						if( data[10] == '1' ){ $("#btOcorrenciaPro").hide(); } // se for produto composto não mostra o bot?o 'Lan?ar ocorrência'
							else{ $("#btOcorrenciaPro").show(); }
						$("#btDescartarPro").hide();
						setTimeout(function(){
							$("#lbReproc").hide();
						}, 20);
					}
				}

				//produto descartado
				if(data[7] > 0) {
					$("#btSalvarPro").hide();
					$("#btOcorrenciaPro").hide();
					$("#btDescartarPro").hide();
					$("#lbProdutoDescartado").show();
					$("#txQrcode").select();
				}
				$("#btSalvarPro").focus();
			} else {
				$("#divRestoItem").hide();
				$("#qtdeProduct").text('');
				$("#txIdproduto").val('');
				$("#txSetor").text('');
				$("#txQtdmaxima").text('0');
				$("#txGmaterial").text('');
				$("#qtdProc").text('');
				$("#txReuso").val('');
				$("#lbReproc").attr("style", "color: #333333; font-weight: none;");
				$("#btSalvarPro").hide();
				$("#btOcorrenciaPro").hide();
				$("#btDescartarPro").hide();
				$("#lbProdutoDescartado").hide();
				
				var chProduto = $("input[name='chProduto']:checked").val()			
				
				if(chProduto == 'pc'){
					$('.type-product').text('consignado');
				}else{
					$('.type-product').text('comun');
				}
				
				$("#lbProdutoinvertido").show();
				
				$("#lbProdutoNaoCadastrado").show();
			}
		});
	}
}

function buscarProdutoFilho() {
	var qr = $("#txQrcodeFilho").val();
	$.post("produtosCompostos", {acao:'buscarFilho', qrcode:qr, pai:$("#txIdproduto").val(), idses:$("#txId").val()}, function(data){
		console.log(qr + $("#txIdproduto").val());
		if (data.indexOf("NAOPERTENCE") >= 0) {
			var aux = $.trim(data).split("*;*");
			if(aux[1] != " - "){
				alert("Atenção! Este produto pertence a seguinte composição:\n\n" + aux[1] + "\n\nPortanto, não pode ser inserido como item deste produto composto.");
			} else {
				alert("Este produto não pertence a nenhuma composição e não pode ser inserido como item deste produto composto.");
			}
			$("#txQrcodeFilho").val("");
			$("#txQrcodeFilho").focus();
		} else if ($.trim(data) == "JAFOI") {

			alert("Produto já inserido nesta solicitação!");
			$("#txQrcodeFilho").val("");
			$("#txQrcodeFilho").focus();
	
		} else if ($.trim(data) != "ERRO") {
			var aux = $.trim(data).split("*;*");
			$("#idfilho").val(aux[0]);
			$("#txIdFilhoLido").val(aux[0]);
			$("#txFilhoLido").text(aux[1]);
			$("#qtdProcFilho").text(aux[2]);
			$("#txQtdmaximaFilho").text(aux[3]);
			$("#divFilhoLido").show();
			if(parseInt($("#qtdProcFilho").text()) > parseInt($("#txQtdmaximaFilho").text())) {
				$("#lbReprocFilho").attr("style", "color: red; font-weight: bold;");
				$("#btDescartarFilho").show();
			} else {
				$("#lbReprocFilho").attr("style", "color: #333333; font-weight: bold;");
				/*$("#btAdicionarFilho").show();
				$("#btOcorrenciaFilho").show();
				$("#btAdicionarFilho").focus();*/
			}
			if(aux[4] > 0) { //produto descartado
				/*$("#btAdicionarFilho").hide();
				$("#btDescartarFilho").hide();
				$("#lbFilhoDescartado").show();
				$("#txQrcodeFilho").select();*/
			}
			$("#btAdicionarFilho").focus();
		} else {
			$("#txIdFilhoLido").val("0");
			$("#txFilhoLido").text("");
			$("#divFilhoLido").hide();
			$("#btAdicionarFilho").hide();
			$("#btOcorrenciaFilho").hide();
			$("#lbFilhoDescartado").hide();
			$("#btDescartarFilho").hide();
		}
	});
}

/***atualiza??es nailson israel**/

$("#modal-backdrop").live('click', function(){
	console.log('adsada')
});

$("#telaListaProdutos").on('hide', function(){
	console.log('asdsad')
	location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
});

$('#btCancelarPro').live('click', function (){
	$("#boxQtde").hide();
	$("#txQtde").val('');
	$("#ckQtde").val('');
	$("#qtdeProduct").val('');
});

//fechaTelaListaProdutos
$('#fechaTelaAdicionarProduto').live('click', function (){
	$("#boxQtde").hide();
	$("#txQtde").val('');
	$("#ckQtde").val('');
	$("#qtdeProduct").val('');
});


$('#fechaTelaListaProdutos1').live('click', function (){
	$('#telaDetail').modal('hide'); 
});

$('#fechaTelaListaProdutos2').live('click', function (){
	$('#telaDetailcomposto').modal('hide'); 
});

$("#fechaPermissao").live('click', function (){
	if($('#sobra').val() != 0){
		$("#telaPermissao").modal();		
	}else{
		$('#telaPermissao').modal('hide');		
	} 
});


$('#voltarcaixa_filho').live('click', function (){
	$('#telaPermissao').modal('hide');
});

$('#voltar').live('click', function (){
	$('#telaPermissao').modal('hide');
	$('#telaListaProdutos').modal('hide');
});


$('#fechaTelaListaProdutos').live('click', function (){
	if($('#sobra').val() != 0){

	var ready = confirm("Faltam " + $('#sobra').val() +  " produtos nesta composição. Para prosseguir você precisa da autorização do responsável.");

		if (ready){
			$("#telaPermissao").modal();
			//location.reload();
		}else{
			$("#txQrcodeFilho").focus();
		}		
	}else{
				location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();		
	}
});

//autorizacao

$('#naoautorizar').live('click', function (){
	if($('#responsavel').val() === ''){
		alert("Atenção! insira o nome do responsável");
	}else if($('#password').val() === ''){
		alert("Atenção! insira a senha.");
	}else{
		
		console.log($('#txQrcodePai').text());
		
		$.post("solicitacoes_new", {acao:"autorizacao", responsavel: $("#responsavel").val(), senha: $("#password").val(), nome_filho: $('#nome_filho').val(), composicao: $('#txProdutoPai').text(), qrcomposicao: $('#txQrcodePai').text(), pagina: 'S', idpai: $("#txIdproduto").val(), modo: 'nao', iditemsol: $.trim($('#iditemsol').text()) }, function(data) {
			console.log(data)
			if($.trim(data) == "OK"){
				location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
			}else{
				alert('Autorização invalida! Por favor, você precisa da Autorização do responsável do turno.');
			}
		});	
	}	
});

$('#autorizar').live('click', function (){
	if($('#responsavel').val() === ''){
		alert("Atenção! insira o nome do responsável");
	}else if($('#password').val() === ''){
		alert("Atenção! insira a senha.");
	}else{
		$.post("solicitacoes_new", {acao:"autorizacao", responsavel: $("#responsavel").val(), senha: $("#password").val(), nome_filho: $('#nome_filho').val(), composicao: $('#txProdutoPai').text(), qrcomposicao: $('#txQrcodePai').text(), pagina: 'S', idpai: $("#txIdproduto").val(), modo: 'sim', iditemsol: $.trim($('#iditemsol').text()) }, function(data) {
			console.log(data)
			if($.trim(data) == "OK"){
				location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
			}else{
				alert('Autorização invalida! Por favor, você precisa da Autorização do responsável do turno.');
			}
		});	
	}	
});




$('#btFechar').live('click', function (){
	if($('#sobra').val() != 0){
		var ready = confirm("Faltam " + $('#sobra').val() +  " produtos nesta composição. Para prosseguir você precisa da autorização do responsável.");
		if (ready){
			$("#telaPermissao").modal();
			//location.reload();
		}else{
			$("#txQrcodeFilho").focus();
		}
	}else{
		location.href = "solicitacoes_new?populate=1&id=" + $('#sector_id').val();
	}
});

function inserir($frm){
	$( $frm ).find('input[name=inserir]').val(1);
	$.post('qrcode', $( $frm ).serialize() , function(data) {
		if( data.suc ){
			alert( data.suc );

			// SE FOR SUBSTITUI??O DE PRODUTO COMPOSTO
			if( $('#formListaProdutos').is(':visible') ){
				// Atualiza já com o novo QrCode
				var idPai = $('#txIdPai').val();
				populaProdutoComposto(idPai);
				countComum();
				countConsginados();
				$('#mdl-substituir').css( "display", "none" );
				$('#mdl-substituir').find('button[type=reset]').click();
			}
		}
		else{
			alert( data.err );
		}
	});
}

function populaProdutoComposto(id){
	//console.log('sadsad')
	//console.log(id);

	$.post("produtosCompostos", {idpai:id, modo:"1"}, function(data){
		var aux = $.trim(data).split("*;*");
		$("#divListaFilhos").html(aux[4]); // lista filhos 2
	});
}

$('.btnSubstituir').live('click', function (){
	// SUBSTITUIR QRCODE - PRODUTO COMPOSTO
	if( $('#formListaProdutos').is(':visible') ){

		var id = $(this).attr('id');

		$.post("produtos_new", {acao:"getPro", id:this.id}, function(data){
			data = JSON.parse(data);

			if(data.pro_id > 0){
				$('input[name="idproduto"]').val( data.pro_id );
				$('input[name="qrcode_atual"]').val( data.pro_qrcode );
			}else{
				alert('não foi possível Localizar o produto !');
				return false;
			}
		});
	}

});

function modalSubs(){
    $('#mdl-substituir').modal('show');
	$('#mdl-substituir').css( "display", "block" )
}

/*****/

function countComporpai(id, iditemsol){
	$('#iditemsol').text(iditemsol);
	$.post("produtosCompostos", {idpai:id, modo:"2", idses:$("#txId").val() , idsol: iditemsol }, function(data){
		var dd = $.trim(data).split("*;*");
		$('#sobra').val(dd[6]);
	});
}

function showinfos($id){
	
	$.post("solicitacoes_new",{acao:'getUltimoEstado', iditemsol: $id }, function(data){
		console.log(data)
		var dt = $.trim(data).split("*;*");
		$("#setor").text(dt[0])
		$("#conferente").text(dt[1])
	});	
}

//aqui carrega com o produto já cadastrado
function comporPai(id , iditemsol ) {

	$('#iditemsol').text(iditemsol);
	$("#divListaFilhos").html('Carregando ...'); // place holder

	$("#txProdutoPai").text('...');

	$.post("produtosCompostos", {idpai:id, modo:"2", idses:$("#txId").val() , idsol: iditemsol }, function(data){
		var aux = $.trim(data).split("*;*");

		$("#divListaFilhos").html(aux[4]); // place holder
		$('#sobra').val(aux[6]);
		$("#txIdproduto").val(aux[0]);
		$("#txIdPai").val(aux[0]);
		$("#txQrcodePai").text(aux[1]);
		$("#txProdutoPai").text(aux[2]);
		$("#slMEsterilizacao").val(aux[5]);
		$("#slEEsterilizacao").val(aux[7]);
		showinfos(aux[0]);	
	});	
}

$("#telaDetailclick").live('click', function (){
	console.log($("#txIdproduto").val())
	comporDetail($("#txIdproduto").val());
});

//carrega detail
function comporDetail(id){
	$.get("solicitacoes_new?iddetail=" + id, function(data){
		$("#divListaDetail").html(data);
	});	
}

//carrega detail
function comporDetail2(id){
	$.get("solicitacoes_new?iddetailconsig=" + id, function(data){
		$("#divListaDetail2").html(data);
	});	
}

function lancarOcorrencia(idProduto, nomeProduto) {
	$.post("ocorrenciasprodutos", {acao:"ocorrenciasProduto", produto:idProduto}, function(data){
		$.post("ocorrenciasprodutos", {acao:'buscar', qrcode:$("#txQrcode").val()}, function(data){
			if ($.trim(data) != "ERRO" && $.trim(data) != ""){
				var aux = $.trim(data).split("*;*");
				$("#idProduto").val(aux[0]);
				if (aux[2] == "*") {
					$("#lbProdutoDescartado").show();
					$("#txQrcode").select();
				} else {
					$.post("ocorrenciasprodutos", {acao:'ocorrenciasProduto', produto:aux[0]}, function(data){
						console.log()
						//$("#produtoAlvo").text(aux[1]);
						$("#produtoPai").text('Caixa: ' + aux[4]);
						$("#ProdutoPai").val(aux[4]);
						$("#ProdutoPaiId").val(aux[5]);
						//$("#divOcorrenciasProduto").html(data);
						//$("#slOcorrencia").val("0");
						//$("#descricaoOcorrencia").text("");
						//$("#txObs").val("");
						//$("#lbObs").hide();
						//$("#btConfirmarOcorrencia").hide();
						//$("#tlaLancarOcorrencia").modal();
					});
				}
			} else {
				$("#lbProdutoNaoCadastrado").show();
			}
		});
		
		$("#produtoAlvo").text(nomeProduto);
		$("#idProdutoAlvo").val(idProduto);
		$("#divOcorrenciasProduto").html(data);
		$("#slOcorrencia").val("0");
		$("#descricaoOcorrencia").text("");
		$("#txObs").val("");
		$("#lbObs").hide();
		$("#btConfirmarOcorrencia").hide();
		$("#tlaLancarOcorrencia").modal();
	});
}

function verificaNPedido() {
	if($("#txId").val() == "") {
		alert("Informe o número do pedido!");
		return false;
	} else {
		return true;
	}
}

function listaItens(id) {
/*	$.post("solicitacoes_new", {idses: }, function(data) {
*/
	$.post("ListaItens", {id:id}, function(data) {
		//console.log(data);
		if($.trim(data) != "error") {
			var aux = $.trim(data).split("*;*");
			$("#lista_itensSE").html(aux[0]);
			//$("#totalComum").text("Comuns: " + aux[1]);
			var tcomun = aux[1];
			countComum();
		} else {
			$("#totalComum").text("Produtos comuns: 0");
		}
	});
}

//preenche consignados
function listaItensConsignados(id) {
	$.post("ListaItenConsignados", {id:id}, function(data) {
		//console.log(data);
		if($.trim(data) != "error") {
			var aux = $.trim(data).split("*;*");
			$("#lista_consig").html(aux[0]);
			var valor = parseInt($("#Tcomun").val()) + parseInt(aux[1]);
			$(".txQtdeItens").html("Total de produtos: " + valor);
			$("#totalConsignado").text("Consignados: " + aux[1]);
			countConsginados();
		} else {
			$("#totalConsignado").text("Produtos consignados: 0");
		}
	});
}

//cleverson
function countComum(){
	$.post("solicitacoes_new", {acao:"contaComum", idses:$("#txIdSol").val()}, function(data) {
		$("#totalComum").text("Comuns: " + data);
		if(data == 0){
			$("#lista_itensSE").html("<tr><td colspan='8'>Nenhum item solicitado.</td></tr>");
		}
		$("#Tcomun").val(data);
	});	
}

function countConsginados(){
	$.post("solicitacoes_new", {acao:"contaConsignado", idses:$("#txIdSol").val()}, function(data) {
		
		$("#totalConsignado").text("Consignados: " + data);
		if(data == 0){
			$("#lista_consig").html("<tr><td colspan='8'>Nenhum item solicitado.</td></tr>");
		}

		var total = parseInt($("#Tcomun").val()) + parseInt(data);
		$(".txQtdeItens").html("Total de produtos: " + total);
		// timeout para esperar os dados do banco
		if(isNaN(total)){
			$(".txQtdeItens").html("Total de produtos" + "<i class='fas fa-spinner cs-spinner'>");
			setTimeout(function(){
				countConsginados();
			}, 500);
			
		}
	});		
}

function countComunQtde(){
	$.post("solicitacoes_new", {acao:"contaComumQtde", idses:$("#txIdSol").val(), idProduto:$("#txIdproduto").val()}, function(data) {
		//console.log(data + 'sad')
		/*$("#totalConsignado").text("Consignados: " + data);
		if(data == 0){
			$("#lista_consig").html("<tr><td colspan='8'>Nenhum item solicitado.</td></tr>");
		}

		var total = parseInt($("#Tcomun").val()) + parseInt(data);
		$(".txQtdeItens").html("Total de produtos: " + total);

		console.log(total);
		// timeout para esperar os dados do banco
		if(isNaN(total)){
			$(".txQtdeItens").html("Total de produtos" + "<i class='fas fa-spinner cs-spinner'>");
			setTimeout(function(){
				countConsginados();
			}, 500);
			
		}*/
	});		
}

// gera uma imagem de 400X400 para cada produto da lista de produtos compostos.
var proKids = function(id){
	console.log($('.exist'));
	$.post('image_popup_js', {img_url:'img_pro/pro'+id+'.png', extra: id}, function(data){
		data = $.trim(data);
		data = data.split('***')
		var divEmpty = $('#show_image'+data[0]+' div');
		var hasClass = divEmpty.hasClass("exist");
		//alert(hasClass)
		if(hasClass == false){
			$('.exist').remove();
			$('#show_image'+data[0]).html(data[1]);
		}else{
			//$('#show_image'+data[0]).empty();
			//$('.exist').remove();
		}

		$('.close_img').click(function(){
			$('.exist').remove();
		})

		$("body").dblclick(function( event ) {
 			if(event.target.getAttribute('id') !== 'img_big'){
  				$('#show_image'+data[0]).empty();
  				$('.exist').remove();
  			}
		});
	})
}

function fileExists(url) {
    if(url){
        var req = new XMLHttpRequest();
        req.open('GET', url, false);
        req.send();
        return req.status==200;
    } else {
        return false;
    }
}


function noenter() {
	return !(window.event && window.event.keyCode == 13);
}

function imgError(image) {
	image.onerror = "";
	image.src = "img_pro/placeholder_small.png";
	return true;
}